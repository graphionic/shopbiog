/**
 * Constants for Smart Design Library
 */

// API Endpoints
export const API_ENDPOINTS = {
	PATTERNS: "/real-testimonial/v2/get_premade_patterns",
	WISHLIST: "/real-testimonial/v2/save_wishlist_item",
	SINGLE_PATTERN: "https://demo.realtestimonials.io/wp-json/real-testimonials/v1/single-pattern",
	UPGRADE_URL: "https://realtestimonials.io/pricing/",
};

// CSS Classes
export const CSS_CLASSES = {
	MODAL: "sp-real-patterns-builder-modal",
	POPUP_OPEN: "sp-real-patterns-popup-open",
	TOOLBAR_LIBRARY: "sp-real-patterns-toolbar-design-library",
	PATTERN_GRID: "sp-real-pattern-grid",
	PATTERN_COL2: "sp-real-pattern-col2",
	PATTERN_COL3: "sp-real-pattern-col3",
};

// Default Values
export const DEFAULTS = {
	COLUMN: "3",
	SEARCH_QUERY: "",
	TREND: "default",
	FREE_PRO: "all",
	DEBOUNCE_DELAY: 200,
	SKELETON_COUNT: 25,
};

// Auto-open the library when the editor is opened from the dashboard
// (e.g. the "Start with Ready Patterns" button on the Quick Start page).
export const AUTO_OPEN = {
	QUERY_ARG: "realpatterns",
	// Optional block slug to preselect in the Block Type facet. Set by the
	// dashboard Blocks cards via `generatePatternLibraryLink()`.
	BLOCK_ARG: "rtp_pattern_block",
	POLL_INTERVAL: 200,
	MAX_ATTEMPTS: 50,
	EDITOR_READY_SELECTOR: ".edit-post-header-toolbar, .editor-header__toolbar, .edit-site-header-edit-mode__start",
};

// Keyboard Keys
export const KEYBOARD_KEYS = {
	ESCAPE: 27,
	ENTER: "Enter",
	SPACE: " ",
};

// Filter Options
export const FILTER_OPTIONS = {
	TREND: [
		{ value: "default", label: "Sort By" },
		{ value: "popular", label: "Popular" },
		{ value: "latest", label: "Latest" },
	],
};
