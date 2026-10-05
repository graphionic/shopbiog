import { useEffect, useRef } from "@wordpress/element";
import { inArray, useDeviceType } from "@testimonial/controls";
import Swiper from "swiper";
import { Navigation, Pagination, Autoplay, Mousewheel, Keyboard, EffectCube, EffectFlip } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

const EFFECT_MODULES = {
	cube: EffectCube,
	flip: EffectFlip,
};

const EFFECT_CONFIGS = {
	cube: { slideShadows: true, shadow: true, shadowOffset: 20, shadowScale: 0.94 },
	flip: { slideShadows: true, limitRotation: true },
};

// resolveEffect.
const resolveEffect = (carouselStyle, slidingEffect) => {
	return slidingEffect || "slide";
};

// layoutFor — per-device slidesPerView / slidesPerGroup / spaceBetween.
const layoutFor = (device, { columns, slideToScroll, columnGap }, groupedEffect) => {
	if (groupedEffect) {
		return { slidesPerView: 1, slidesPerGroup: 1, spaceBetween: 0 };
	}
	const baseColumns = Number(columns?.device?.[device]);
	const perView = Number.isFinite(baseColumns) ? baseColumns : 1;
	const group = Number(slideToScroll?.device?.[device]);
	const gap = Number(columnGap?.device?.[device]);
	return {
		slidesPerView: perView,
		slidesPerGroup: Number.isFinite(group) ? Math.trunc(group) : 1,
		spaceBetween: Number.isFinite(gap) ? Math.trunc(gap) : 0,
	};
};

const useSwiperConfig = (attributes, testimonials = []) => {
	const {
		uniqueId,
		slidingEffect,
		carouselStyle,
		enableNavigationArrow,
		enablePaginationDots,
		paginationStyle,
		sliderAutoPlay,
		tabAndKeyNavigation,
		mouseWheelControl,
		carouselDirection,
		pauseOnHover,
		infiniteLoop,
		freeScrollMode,
		carouselAutoplayDelay,
		carouselSpeed,
		slideToScroll,
		columnGap,
		columns,
		align,
		cardContents,
	} = attributes;

	const swiperRef = useRef(null);
	const swiperInstanceRef = useRef(null);
	const navNextEl = useRef(null);
	const navPrevEl = useRef(null);
	const paginationRef = useRef(null);
	const currentDevice = useDeviceType();

	// Coerce effect against carousel style — see resolveEffect (SwiperConfig::resolve_effect).
	const effect = resolveEffect(carouselStyle, slidingEffect);
	const groupedEffect = inArray(["cube", "flip"], effect);
	// Effective swiper direction — carouselDirection applies only under autoplay,
	// so RTL never re-orders/re-aligns a static carousel.
	const swiperDir = sliderAutoPlay && carouselDirection === "rtl" ? "rtl" : "ltr";
	const layoutOptions = { columns, slideToScroll, columnGap };
	const swiperSpeed = carouselSpeed?.value ?? 600;
	const columnsValue = columns?.device?.[currentDevice];
	const groupSize = groupedEffect ? Math.max(1, Math.floor(typeof columnsValue === "number" ? columnsValue : 1)) : 1;
	const groupGap = groupedEffect ? layoutFor(currentDevice, layoutOptions, false).spaceBetween : 0;
	const shouldGroupSlides = groupedEffect && groupSize > 1;
	const testimonialsCount = testimonials.length;
	const testimonialsKey = testimonials.map((t) => t?.id ?? "").join(",");
	// Toggling a card content on/off flips its is_active flag without changing.
	const cardContentsKey = Array.isArray(cardContents)
		? cardContents.map((c) => `${c?.id}:${c?.is_active ? 1 : 0}`).join(",")
		: "";

	const testimonialGroups = [];
	if (shouldGroupSlides) {
		for (let index = 0; index < testimonials.length; index += groupSize) {
			testimonialGroups.push(testimonials.slice(index, index + groupSize));
		}
	}

	useEffect(() => {
		if (!swiperRef.current || testimonialsCount === 0) {
			return;
		}

		const modules = [Navigation];
		if (enablePaginationDots) {
			modules.push(Pagination);
		}
		if (sliderAutoPlay) {
			modules.push(Autoplay);
		}
		if (mouseWheelControl) {
			modules.push(Mousewheel);
		}
		if (tabAndKeyNavigation) {
			modules.push(Keyboard);
		}
		if (EFFECT_MODULES[effect]) {
			modules.push(EFFECT_MODULES[effect]);
		}

		const paginationEl = paginationRef?.current;
		let pagination = false;

		if (enablePaginationDots) {
			pagination = {
				el: paginationEl,
				clickable: true,
				type: "bullets",
				dynamicBullets: paginationStyle === "dynamic",
			};
		}

		const swiperParams = {
			effect,
			direction: "horizontal",
			rtl: swiperDir === "rtl",
			loop: infiniteLoop,
			grabCursor: true,
			followFinger: true,
			watchOverflow: true,
			a11y: { scrollOnFocus: false },
			centeredSlides: false,
			watchSlidesProgress: effect !== "slide",
			allowTouchMove: false,
			simulateTouch: false,
			resistance: true,
			resistanceRatio: 0.85,
			speed: swiperSpeed,
			autoplay: sliderAutoPlay
				? {
						reverseDirection: carouselDirection === "rtl",
						delay: carouselAutoplayDelay?.value ?? 600,
						pauseOnMouseEnter: !!pauseOnHover,
						disableOnInteraction: false,
						stopOnLastSlide: !infiniteLoop,
					}
				: false,
			keyboard: tabAndKeyNavigation ? { enabled: true, onlyInViewport: true } : false,
			// forceToAxis:false so a normal vertical wheel (deltaY) drives a horizontal carousel too.
			mousewheel: mouseWheelControl ? { forceToAxis: false, sensitivity: 1, releaseOnEdges: false } : false,
			freeMode: freeScrollMode ? { enabled: true, sticky: false, momentum: true, momentumBounce: true } : false,
			// Editor preview keys off the selected device (useDeviceType), not the
			// window width. Gutenberg's device preview resizes the editor canvas, not
			// the browser window, so window-based Swiper breakpoints never fire in the
			// editor. The frontend keeps px breakpoints (SwiperConfig.php); this hook
			// is editor-only, so re-init per selected device instead.
			...layoutFor(currentDevice, layoutOptions, groupedEffect),
			modules,
			navigation: enableNavigationArrow ? { nextEl: navNextEl?.current, prevEl: navPrevEl?.current } : false,
			pagination,
			on: {
				resize(swiper) {
					if (swiper) {
						swiper.update();
					}
				},
			},
		};

		if (EFFECT_CONFIGS[effect]) {
			swiperParams[`${effect}Effect`] = EFFECT_CONFIGS[effect];
		}

		swiperInstanceRef.current = new Swiper(swiperRef.current, swiperParams);

		return () => {
			if (swiperInstanceRef.current) {
				swiperInstanceRef.current.destroy(true, true);
				swiperInstanceRef.current = null;
			}
		};
	}, [
		testimonialsCount,
		testimonialsKey,
		uniqueId,
		currentDevice,
		effect,
		carouselStyle,
		enableNavigationArrow,
		enablePaginationDots,
		paginationStyle,
		sliderAutoPlay,
		tabAndKeyNavigation,
		mouseWheelControl,
		carouselDirection,
		pauseOnHover,
		infiniteLoop,
		freeScrollMode,
		carouselAutoplayDelay?.value,
		carouselSpeed?.value,
		slideToScroll?.device?.Desktop,
		slideToScroll?.device?.Tablet,
		slideToScroll?.device?.Mobile,
		columnGap?.device?.Desktop,
		columnGap?.device?.Tablet,
		columnGap?.device?.Mobile,
		columns?.device?.Desktop,
		columns?.device?.Tablet,
		columns?.device?.Mobile,
		groupSize,
		align,
		cardContentsKey,
	]);

	return {
		effect,
		swiperDir,
		swiperRef,
		navNextEl,
		navPrevEl,
		paginationRef,
		shouldGroupSlides,
		testimonialGroups,
		groupSize,
		groupGap,
	};
};

export default useSwiperConfig;
