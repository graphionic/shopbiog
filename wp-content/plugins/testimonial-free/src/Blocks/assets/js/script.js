/**
 * Real Testimonials — frontend runtime.
 *
 * Swiper carousel/slider init, ajax pagination, and the testimonial submission
 * form. Enqueued raw (no transpile) — keep this file browser-native JS.
 */
(() => {
	'use strict';

	// ------------------------------------------------------------------
	// Shared helpers
	// ------------------------------------------------------------------

	const toInt = (value, fallback = 0) => parseInt(value, 10) || fallback;

	const onReady = (callback) => {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', callback);
		} else {
			callback();
		}
	};

	/**
	 * Delegated document-level listener — covers ajax-loaded cards too.
	 *
	 * @param {string}   type     Event type (click, submit, …)
	 * @param {string}   selector Closest-match selector for the real target
	 * @param {Function} handler  (event, matchedElement) =>
	 */
	const delegate = (type, selector, handler) => {
		document.addEventListener(type, (event) => {
			const matched = event.target.closest(selector);
			if (matched) {
				handler(event, matched);
			}
		});
	};

	const ajaxPost = async (body) => {
		const response = await fetch(sp_real_localize_data.ajaxUrl, {
			method: 'POST',
			body,
		});
		return response.json();
	};

	// ------------------------------------------------------------------
	// Carousel (Swiper)
	// ------------------------------------------------------------------

	const EFFECT_CONFIGS = {
		cube: {
			slideShadows: true,
			shadow: true,
			shadowOffset: 20,
			shadowScale: 0.94,
		},
		flip: {
			slideShadows: true,
			limitRotation: true,
		},
	};

	// Effects that render one slide at a time — multi-column layouts are
	// emulated by grouping cards into a single slide.
	const GROUPED_EFFECTS = ['cube', 'flip'];

	// Resolve a swiper setting for the current viewport (base value
	// overridden by the largest matching breakpoint).
	const getBreakpointSetting = (settings, key) => {
		const width = window.innerWidth || 0;
		const breakpoints = settings.breakpoints || {};

		return Object.keys(breakpoints)
			.map(Number)
			.sort((a, b) => a - b)
			.reduce(
				(value, breakpoint) =>
					width >= breakpoint &&
						breakpoints[breakpoint]?.[key] !== undefined
						? breakpoints[breakpoint][key]
						: value,
				settings[key]
			);
	};

	const groupEffectSlides = (sliderEl, settings) => {
		if (!GROUPED_EFFECTS.includes(settings.effect)) {
			return;
		}

		const wrapper = sliderEl.querySelector('.swiper-wrapper');
		const groupSize = Math.max(
			1,
			Math.floor(Number(getBreakpointSetting(settings, 'slidesPerView')) || 1)
		);

		// Bail if single-column, or already grouped — the dataset flag guards
		// against a second init (e.g. resize) re-wrapping the cards.
		if (!wrapper || groupSize <= 1 || wrapper.dataset.spRealEffectGrouped) {
			return;
		}

		const groupGap =
			Number(getBreakpointSetting(settings, 'spaceBetween')) || 0;
		const slides = Array.from(wrapper.children);
		wrapper.dataset.spRealEffectGrouped = 'true';

		slides.forEach((slide) => {
			slide.classList.remove('swiper-slide', 'sp-real-swiper-slide');
			slide.classList.add('sp-real-swiper-effect-group-item');
			slide.style.width = '';
		});

		for (let index = 0; index < slides.length; index += groupSize) {
			const group = document.createElement('div');
			group.className =
				'sp-real-swiper-slide swiper-slide sp-real-swiper-effect-group';
			group.style.setProperty(
				'--sp-real-carousel-effect-columns',
				groupSize
			);
			group.style.setProperty(
				'--sp-real-carousel-effect-gap',
				`${groupGap}px`
			);

			slides
				.slice(index, index + groupSize)
				.forEach((slide) => group.appendChild(slide));
			wrapper.appendChild(group);
		}

		// Grouped cards travel as one slide — collapse per-view settings.
		const collapse = (target) => {
			target.slidesPerView = 1;
			target.slidesPerGroup = 1;
			target.spaceBetween = 0;
		};
		collapse(settings);
		Object.values(settings.breakpoints || {}).forEach(collapse);
	};

	const buildPaginationConfig = (paginationEl, paginationStyle) => {
		if (!paginationEl) {
			return false;
		}

		// Dots, dynamic, stepper — all bullets with different styles.
		return {
			el: paginationEl,
			clickable: true,
			type: 'bullets',
			dynamicBullets: paginationStyle === 'dynamic',
		};
	};

	/**
	 * Initialize a testimonial carousel Swiper.
	 *
	 * @param {Element} block - DOM context to search within
	 */
	const initSlider = (block) => {
		const sliderEl = block.querySelector(
			'.sp-testimonial-swiper[data-swiper-settings]'
		);
		if (!sliderEl) {
			return;
		}

		let settings;
		try {
			settings = JSON.parse(sliderEl.dataset.swiperSettings);
		} catch (e) {
			// eslint-disable-next-line no-console
			console.error('Invalid swiper settings:', e);
			return;
		}

		const parent = sliderEl.parentElement;
		const paginationEl =
			sliderEl.querySelector('.sp-real-swiper-pagination') ||
			parent?.querySelector('.sp-real-swiper-pagination');
		const paginationStyle = settings?.paginationStyle;
		const nextEl = parent?.querySelector('.sp-real-nav-next');
		const prevEl = parent?.querySelector('.sp-real-nav-prev');
		const hasNav = settings.navigation && nextEl && prevEl;

		groupEffectSlides(sliderEl, settings);

		const swiperConfig = {
			...settings,
			navigation: hasNav ? { nextEl, prevEl } : false,
			pagination: buildPaginationConfig(paginationEl, paginationStyle),
			on: {
				resize(swiper) {
					swiper.update();
				},
			},
		};

		const effectConfig = EFFECT_CONFIGS[settings.effect];
		if (effectConfig) {
			swiperConfig[`${settings.effect}Effect`] = effectConfig;
		}
		// eslint-disable-next-line no-undef
		new Swiper(sliderEl, swiperConfig);
	};

	// ------------------------------------------------------------------
	// Ajax query — shared fetch for search / filter / pagination
	// ------------------------------------------------------------------

	/**
	 * Query testimonials via admin-ajax and render into the cards wrapper.
	 *
	 * @param {Object}   queryData           Query params merged into the request
	 * @param {Element}  mainBlock           The .sp-real-block-frontend element
	 * @param {Object}   [options]
	 * @param {string}   [options.mode]      'replace' (default) or 'append'
	 * @param {Function} [options.onSuccess] Called with res.data on success
	 * @return {Promise<Object|undefined>} Parsed ajax response
	 */
	const queryTestimonials = async (queryData, mainBlock, options = {}) => {
		const { mode = 'replace', onSuccess } = options;
		const cardsWrapper = mainBlock.querySelector('.sp-real-cards-wrapper');

		try {
			const res = await ajaxPost(
				new URLSearchParams({
					action: 'sp_real_query_testimonials',
					nonce: sp_real_localize_data.nonce,
					...queryData,
				})
			);
			if (!res?.success || !cardsWrapper) {
				return res;
			}
			const html = res.data?.html ?? '';
			if (mode === 'append') {
				if (html) {
					cardsWrapper.insertAdjacentHTML('beforeend', html);
				}
			} else {
				cardsWrapper.innerHTML = html;
			}
			onSuccess?.(res.data || {});
			return res;
		} catch {
			// Swallow — UI keeps prior state.
		}
	};

	// ------------------------------------------------------------------
	// Group block child blocks — pagination
	// ------------------------------------------------------------------

	const initAjaxPagination = (qs, state) => {
		if (!qs('.sp-real-ajax-pagination')) {
			return;
		}
		const { mainBlock } = state;
		const paginationWrapper = qs('.sp-real-pagination-wrapper');
		const cardsWrapper = qs('.sp-real-cards-wrapper');
		if (!paginationWrapper || !cardsWrapper) {
			return;
		}

		// initialLimit = cards already in DOM from first render; totalItems =
		// full matching count. Both feed the totalPages math below.
		const initialLimit = toInt(mainBlock?.dataset.limit);
		const totalItems = toInt(mainBlock?.dataset.totalPosts);

		const paginationData = JSON.parse(
			paginationWrapper.dataset.pagination || '{}'
		);
		const itemsPerPage = parseInt(paginationData?.itemsPerPage);
		const { paginationButtonType, paginationNumberType } = paginationData;

		let currentPage = 1;
		let isLoading = false;
		// Initially rendered cards beyond one page reduce the remaining pages.
		const computeTotalPages = (total) =>
			total > 0
				? Math.ceil(
					(initialLimit - itemsPerPage > 0
						? total - itemsPerPage
						: total) / itemsPerPage
				)
				: 0;
		let totalPages = computeTotalPages(totalItems);
		state.query = { ...state.query, per_page: itemsPerPage };

		// Per-style UI re-sync hooks, run when search/filter swap the result
		// set (each pagination style registers its own below).
		const resetHandlers = [];
		state.pagination = {
			reset(total) {
				totalPages = computeTotalPages(toInt(total));
				currentPage = 1;
				resetHandlers.forEach((handler) => handler());
			},
		};

		const goToPage = (page, mode = 'replace') => {
			if (isLoading) {
				return;
			}
			isLoading = true;
			return queryTestimonials(
				{ ...state.query, per_page: itemsPerPage, current_page: page },
				mainBlock,
				{ mode }
			).finally(() => {
				isLoading = false;
			});
		};

		// Load-more button.
		if (paginationButtonType === 'load-more') {
			const loadMoreWrap = qs('.sp-real-load-more-button');
			const loadMoreBtn = loadMoreWrap?.querySelector(
				'a.sp-real-pagination-item'
			);
			const endMessage = qs('.sp-real-pagination-ending');

			const showEndState = () => {
				if (loadMoreBtn) {
					loadMoreBtn.style.display = 'none';
				}
				endMessage?.classList.remove('sp-d-hidden');
			};

			// Hide button when already on last page (or no extra pages);
			// restore it when a reset brings more pages back.
			const syncLoadMoreState = () => {
				if (totalPages <= 1) {
					showEndState();
					return;
				}
				if (loadMoreBtn) {
					loadMoreBtn.style.display = '';
				}
				endMessage?.classList.add('sp-d-hidden');
			};
			syncLoadMoreState();
			resetHandlers.push(syncLoadMoreState);

			if (loadMoreWrap && loadMoreBtn) {
				loadMoreWrap.addEventListener('click', (e) => {
					const button = e.target.closest('a.sp-real-pagination-item');
					if (!button || button.classList.contains('sp-d-hidden')) {
						return;
					}
					e.preventDefault();
					const nextPage = currentPage + 1;
					if (nextPage > totalPages) {
						showEndState();
						return;
					}
					// goToPage returns undefined when its isLoading guard trips,
					// so only spin up the loading state when a request started.
					const request = goToPage(nextPage, 'append');
					if (request) {
						button.classList.add('sp-real-is-loading');
						button.setAttribute('aria-busy', 'true');
						request.finally(() => {
							button.classList.remove('sp-real-is-loading');
							button.setAttribute('aria-busy', 'false');
						});
					}
					currentPage = nextPage;
					if (currentPage >= totalPages) {
						showEndState();
					}
				});
			}
		}

		// Numbered pagination.
		if (paginationButtonType === 'number') {
			const buttonsWrapper = qs('.sp-real-pagination-buttons');
			if (!buttonsWrapper) {
				return;
			}
			// PHP renders prev/next stubs — keep them, manage numbered between.
			const prevBtn = buttonsWrapper.querySelector('.prev-button');
			const nextBtn = buttonsWrapper.querySelector('.next-button');

			const renderPageButtons = () => {
				// Drop stale numbered buttons (keep the prev/next stubs).
				buttonsWrapper
					.querySelectorAll('.sp-real-pagination-item')
					.forEach((b) => {
						if (b !== prevBtn && b !== nextBtn) {
							b.remove();
						}
					});

				if (totalPages <= 1 || paginationNumberType === 'prev-next') {
					return;
				}
				for (let p = 1; p <= totalPages; p++) {
					const btn = document.createElement('a');
					btn.href = '#';
					btn.className =
						'sp-real-pagination-item' +
						(p === currentPage ? ' current' : '');
					btn.dataset.value = p;
					btn.textContent = p;
					if (nextBtn) {
						buttonsWrapper.insertBefore(btn, nextBtn);
					} else {
						buttonsWrapper.appendChild(btn);
					}
				}
			};

			const syncNavState = () => {
				prevBtn?.classList.toggle('sp-d-disabled', currentPage <= 1);
				nextBtn?.classList.toggle(
					'sp-d-disabled',
					totalPages > 0 && currentPage >= totalPages
				);
				buttonsWrapper
					.querySelectorAll('.sp-real-pagination-item')
					.forEach((b) => {
						if (b === prevBtn || b === nextBtn) {
							return;
						}
						b.classList.toggle(
							'current',
							toInt(b.dataset.value) === currentPage
						);
					});
				buttonsWrapper.dataset.page = currentPage;
			};
			renderPageButtons();
			syncNavState();
			resetHandlers.push(() => {
				renderPageButtons();
				syncNavState();
			});

			buttonsWrapper.addEventListener('click', (e) => {
				const btn = e.target.closest('.sp-real-pagination-item');
				if (!btn) {
					return;
				}
				e.preventDefault();
				if (btn.classList.contains('sp-d-disabled')) {
					return;
				}
				let page = currentPage;
				if (btn.classList.contains('prev-button')) {
					page = Math.max(1, currentPage - 1);
				} else if (btn.classList.contains('next-button')) {
					page = totalPages
						? Math.min(totalPages, currentPage + 1)
						: currentPage + 1;
				} else if (btn.dataset.value) {
					page = toInt(btn.dataset.value, 1);
				}
				if (page === currentPage) {
					return;
				}
				currentPage = page;
				goToPage(currentPage, 'replace');
				syncNavState();
			});
		}
	};

	const initGroupBlock = (block) => {
		const qs = (selector) => block.querySelector(selector);
		const mainBlock = qs('.sp-real-block-frontend:not(.sp-real-testimonial-submission-form)');
		if (!mainBlock) {
			return;
		}
		// Shared mutable query state across the group's child blocks.
		const state = {
			mainBlock,
			query: {
				unique_id: mainBlock.id,
				post_id: mainBlock.dataset?.postId,
			},
		};
		initAjaxPagination(qs, state);
	};

	// ------------------------------------------------------------------
	// TESTIMONIAL SUBMISSION FORM — ajax submit, messages
	// ------------------------------------------------------------------

	const showFormMessage = (form, type, text) => {
		form.querySelectorAll('.sp-real-tsf-message').forEach((m) => {
			const match = m.classList.contains(`sp-real-tsf-message__${type}`);
			m.hidden = !match;
			if (!match) {
				return;
			}
			// Preserve the block's configured default, then show a
			// server-provided reason (nonce / captcha / insert failure) when one
			// is returned — otherwise fall back to the default.
			if (m.dataset.defaultMessage === undefined) {
				m.dataset.defaultMessage = m.textContent.trim();
			}
			m.textContent = text || m.dataset.defaultMessage;
		});
	};

	// Required-fields notice — reveal only when the user attempts to submit
	// with an empty required field, hide again once the form validates.
	// `invalid` fires per-field during native validation and does not bubble,
	// so listen in the capture phase at the document level.
	const toggleRequiredNotice = (form, show) => {
		const notice = form?.querySelector('.sp-real-tsf-required-notice');
		if (notice) {
			notice.hidden = !show;
		}
	};

	document.addEventListener(
		'invalid',
		(event) => {
			const form = event.target.closest('.sp-real-tsf-form');
			toggleRequiredNotice(form, true);
		},
		true
	);
	delegate('submit', '.sp-real-tsf-form', (event, form) => {
		toggleRequiredNotice(form, false);
	});

	// Delegated ajax submit — covers forms cloned into the shared modal
	// (e.g. the review-summary "Write a Review" popup) as well as inline blocks.
	delegate('submit', '.sp-real-tsf-form.sp-real-tsf-ajax', async (event, form) => {
		event.preventDefault();
		const data = new FormData(form);
		data.append('action', 'sp_real_tsf_submit');

		try {
			const res = await ajaxPost(data);
			if (!res?.success) {
				// wp_send_json_error nests the payload under `data`.
				showFormMessage(form, 'error', res?.data?.message);
				return;
			}
			showFormMessage(form, 'success', res?.data?.message);
			form.reset();
		} catch {
			showFormMessage(form, 'error');
		}
	});

	// ------------------------------------------------------------------
	// Boot
	// ------------------------------------------------------------------

	/**
	 * Main initialization function for Real Testimonials Pro blocks.
	 * Called on DOMContentLoaded and by page builder integrations.
	 */
	const spRealTestimonialInit = () => {
		document
			.querySelectorAll('.sp-real-testimonial-block')
			.forEach((block) => {
				initSlider(block);
			});

		document
			.querySelectorAll('.sp-real-testimonial-group')
			.forEach(initGroupBlock);

		// Non-AJAX submit lands here with a status flag in the URL; PHP has
		// already rendered the message for this load, so strip the flag so a
		// reload (or bookmark/share) doesn't re-show it.
		const url = new URL(window.location.href);
		if (
			url.searchParams.has('sp_real_tsf_submitted') ||
			url.searchParams.has('sp_real_tsf_error')
		) {
			url.searchParams.delete('sp_real_tsf_submitted');
			url.searchParams.delete('sp_real_tsf_error');
			window.history.replaceState({}, '', url);
		}
	};

	// Boot on DOM ready.
	onReady(spRealTestimonialInit);

	// Expose init function globally for page builder integrations.
	window.spRealTestimonialInit = spRealTestimonialInit;
})();

// ------------------------------------------------------------------
// Elementor integration
// ------------------------------------------------------------------

(function () {
	// Elementor frontend hook - triggers when widget is ready in editor/frontend.
	window.addEventListener('elementor/frontend/init', function () {
		if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction(
				'frontend/element_ready/real_testimonial_pro_saved_template.default',
				function () {
					if (typeof window.spRealTestimonialInit === 'function') {
						window.spRealTestimonialInit();
					}
				}
			);
		}
	});

	// Elementor preview editor - watch for DOM changes.
	if (typeof ElementorPreview !== 'undefined' || document.body.classList.contains('elementor-editor-preview')) {
		const observer = new MutationObserver(function (mutations) {
			mutations.forEach(function (mutation) {
				if (mutation.addedNodes.length) {
					mutation.addedNodes.forEach(function (node) {
						if (node.nodeType === 1) {
							// Direct match.
							if (node.classList && node.classList.contains('sp-real-testimonial-block')) {
								if (typeof window.spRealTestimonialInit === 'function') {
									window.spRealTestimonialInit();
								}
								return;
							}
							// Nested match.
							const blocks = node.querySelectorAll && node.querySelectorAll('.sp-real-testimonial-block, .sp-real-testimonial-group');
							if (blocks && blocks.length > 0) {
								if (typeof window.spRealTestimonialInit === 'function') {
									window.spRealTestimonialInit();
								}
							}
						}
					});
				}
			});
		});

		observer.observe(document.body, {
			childList: true,
			subtree: true
		});
	}

	// Fallback: Re-initialize on window load for Elementor.
	window.addEventListener('load', function () {
		if (document.body.classList.contains('elementor-editor-preview')) {
			if (typeof window.spRealTestimonialInit === 'function') {
				window.spRealTestimonialInit();
			}
		}
	});
})();
