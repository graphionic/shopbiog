/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { registerPlugin } from "@wordpress/plugins";
import { debounce } from "@wordpress/compose";
import { subscribe } from "@wordpress/data";
import { createRoot } from "@wordpress/element";
import { getModulesSettings } from "@testimonial/controls";
import { AUTO_OPEN, CSS_CLASSES, DEFAULTS } from "./constants";
import { RealTestimonialLogo } from "./Icons";
import Library from "./Library";
import "./editor.scss";

/**
 * Add Prebuilt Library button to Gutenberg toolbar
 */

let modalRoot;

export function ToolbarLibrary() {
	const renderButton = (selector) => {
		// Avoid adding duplicate buttons.
		if (selector.querySelector(`.${CSS_CLASSES.TOOLBAR_LIBRARY}`)) {
			return;
		}
		const patternButton = document.createElement("div");
		patternButton.classList.add(CSS_CLASSES.TOOLBAR_LIBRARY);
		selector.appendChild(patternButton);

		const root = createRoot(patternButton);
		root.render(
			<span id="sp-real-patterns-library-modal-button" className="popup-button" onClick={onInsertButtonClick}>
				<RealTestimonialLogo fill="#ffffff" />
				{__("Ready Patterns Library", "testimonial-free")}
			</span>
		);
	};

	const removeModal = () => {
		const modal = document.querySelector(`.${CSS_CLASSES.MODAL}`);
		if (modal) {
			// Unmount React component first.
			if (modalRoot) {
				modalRoot.unmount();
			}

			// Then remove modal from DOM.
			modal.remove();
			document.body.classList.remove(CSS_CLASSES.POPUP_OPEN);
		}
	};

	/**
	 * @param {string} initialBlockType Optional block slug to preselect in the
	 *                                  Block Type facet (sidebar stays usable).
	 */
	const openModal = (initialBlockType = "") => {
		// If modal already exists, don't create another.
		if (document.querySelector(`.${CSS_CLASSES.MODAL}`)) {
			return;
		}
		const node = document.createElement("div");
		node.className = `${CSS_CLASSES.MODAL} sp-real-patterns-blocks-layouts`;
		document.body.appendChild(node);

		modalRoot = createRoot(node);
		modalRoot.render(<Library isShow={true} onClose={removeModal} initialBlockType={initialBlockType} />);
		document.body.classList.add(CSS_CLASSES.POPUP_OPEN);

		// Optional: close when clicking outside
		setTimeout(() => {
			node.addEventListener("click", (event) => {
				if (event.target === node) {
					removeModal();
				}
			});
		}, 0);
	};

	const onInsertButtonClick = (e) => {
		e.preventDefault();
		openModal();
	};

	/**
	 * Drop the auto-open query arg so a reload/permalink no longer reopens the library.
	 */
	const clearAutoOpenArg = () => {
		if (!window.history || !window.history.replaceState) {
			return;
		}
		const url = new URL(window.location.href);
		url.searchParams.delete(AUTO_OPEN.QUERY_ARG);
		url.searchParams.delete(AUTO_OPEN.BLOCK_ARG);
		window.history.replaceState({}, "", url.toString());
	};

	/**
	 * Open the library automatically when the editor was reached from the
	 * "Start with Ready Patterns" button (post-new.php?...&realpatterns=true).
	 */
	const maybeAutoOpen = () => {
		const params = new URLSearchParams(window.location.search);
		if ("true" !== params.get(AUTO_OPEN.QUERY_ARG)) {
			return;
		}
		// Read the optional block slug before the arg is stripped from the URL.
		const initialBlockType = params.get(AUTO_OPEN.BLOCK_ARG) || "";
		clearAutoOpenArg();

		let attempts = 0;
		const timer = setInterval(() => {
			attempts++;
			if (document.querySelector(AUTO_OPEN.EDITOR_READY_SELECTOR)) {
				clearInterval(timer);
				openModal(initialBlockType);
				return;
			}
			if (attempts >= AUTO_OPEN.MAX_ATTEMPTS) {
				clearInterval(timer);
			}
		}, AUTO_OPEN.POLL_INTERVAL);
	};

	/**
	 * Deep links are an explicit request for the library (the dashboard Blocks
	 * "Demo" link, the Quick Start CTA), so they open it regardless of the
	 * `pattern_library` module — the module only governs the always-on toolbar
	 * button below.
	 */
	maybeAutoOpen();

	if (!getModulesSettings("pattern_library")) {
		return null;
	}

	const debouncedRender = debounce(() => {
		const editToolbar = document.querySelector(".edit-post-header-toolbar");
		if (editToolbar) {
			renderButton(editToolbar);
		}
	}, DEFAULTS.DEBOUNCE_DELAY);

	const unsubscribe = subscribe(() => {
		debouncedRender();
		unsubscribe();
	});

	return null;
}

registerPlugin("sp-real-patterns-toolbar-library", {
	render: ToolbarLibrary,
});
