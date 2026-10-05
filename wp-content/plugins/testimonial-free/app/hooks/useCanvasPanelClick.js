import { useCallback } from "@wordpress/element";
import { useDispatch } from "@wordpress/data";
import useTogglePanelBody from "./useTogglePanelBody";

/**
 * Canvas element (root class) → inspector panel key (see blocks/shared/Inspector.jsx).
 *
 * Clicking one of these in the editor canvas opens the panel that controls it.
 * Only the card sections this plugin renders are listed: company info, reviewer
 * logo, location, flag badge, date, custom fields and social profiles are Pro-only,
 * and there is no "social-media" panel here to open.
 *
 * Card classes mirror the section roots in templates/card/TemplateParts.jsx, the
 * swiper ones the arrows/dots emitted by blocks/shared/CarouselRender.jsx — the
 * arrow entry is the button itself, not the `.sp-real-swiper-nav-arrows` bar that
 * holds it: that bar is absolutely positioned across the slides, so mapping it
 * would claim clicks that land on a card. The
 * click resolves through closest() so nested targets (links, icons, badges,
 * swiper bullets) map to their owning section — which is why only the outermost
 * class of each section needs an entry.
 */
export const CANVAS_PANEL_MAP = {
	".sp-real-testimonial-client-title": "testimonial-content",
	".sp-real-testimonial-content": "testimonial-content",
	".sp-real-client-rating": "star-rating",
	".sp-real-client-image": "reviewer-image",
	".sp-real-testimonial-client-name": "reviewer-details",
	".sp-real-client-designation": "reviewer-details",
	".sp-real-swiper-navigation": "navigation-arrow",
	".sp-real-swiper-pagination": "pagination-dots",
};

const CANVAS_SELECTOR = Object.keys(CANVAS_PANEL_MAP).join(", ");

// Resolve the inspector panel key for a matched element.
const panelKeyFor = (element) => {
	const selector = Object.keys(CANVAS_PANEL_MAP).find((s) => element.matches(s));
	return selector ? CANVAS_PANEL_MAP[selector] : undefined;
};

/**
 * Click handler that opens the inspector panel behind the clicked canvas element.
 *
 * Attach to any canvas wrapper — the card root, the swiper arrows, the pagination
 * container. Unmapped targets fall through untouched, so nesting two handlers is
 * harmless.
 *
 * @return {Function} onClick handler.
 */
const useCanvasPanelClick = () => {
	const { togglePanelBody } = useTogglePanelBody();
	// Reveal the block settings sidebar when it is closed. Go through the store
	// action rather than core/interface directly: the store owns the
	// complementary-area scope, which core has changed over time (it is "core"
	// in current WP), so dispatching the action stays correct across versions.
	// openGeneralSidebar lives in core/edit-post (post editor) and core/edit-site
	// (site editor) — verified in WP 7.1; core/editor has never defined it and is
	// checked last only for the day core moves it there. useDispatch returns
	// undefined for a store missing on this screen, so guard before destructuring.
	const postEditorActions = useDispatch("core/edit-post") || {};
	const siteEditorActions = useDispatch("core/edit-site") || {};
	const editorActions = useDispatch("core/editor") || {};
	const openSidebar =
		postEditorActions.openGeneralSidebar ||
		siteEditorActions.openGeneralSidebar ||
		editorActions.openGeneralSidebar;

	return useCallback(
		(event) => {
			// Allow modified clicks to follow links (open in new tab) untouched.
			if (event.metaKey || event.ctrlKey) {
				return;
			}

			const target = event.target.closest(CANVAS_SELECTOR);
			if (!target) {
				return;
			}

			// Links inside the preview must not navigate the editor canvas.
			event.preventDefault();

			const panelKey = panelKeyFor(target);
			if (!panelKey) {
				return;
			}

			// "edit-post/block" is still the block-inspector area name in both editors.
			if (openSidebar) {
				openSidebar("edit-post/block");
			}
			togglePanelBody(panelKey, event);
		},
		[openSidebar, togglePanelBody]
	);
};

export default useCanvasPanelClick;
