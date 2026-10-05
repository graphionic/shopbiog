import { createContext, useContext, useState, useCallback, useEffect } from "@wordpress/element";

const DEFAULT_STATE = { accordion: "defaultOpen", tab: "general" };

// How long the opened-from-canvas panel keeps the `active` class (matches the
// sp-real-panel-highlight animation in blocks/editor.scss).
const HIGHLIGHT_DURATION = 800;

// Safe fallback so consumers rendered outside a provider (e.g. a card preview in
// a picker) don't crash on destructuring — no-ops instead of real state.
const NO_OP_CONTEXT = {
	activeTab: DEFAULT_STATE.tab,
	toggleActiveTab: () => {},
	openedPanelBody: DEFAULT_STATE.accordion,
	togglePanelBody: () => {},
	highlightedPanel: "",
};

export const TogglePanelBodyContext = createContext(NO_OP_CONTEXT);

export const TogglePanelBodyProvider = ({ children }) => {
	const [openedPanelBody, setOpenedPanelBody] = useState(DEFAULT_STATE.accordion);
	const [activeTab, setActiveTab] = useState(DEFAULT_STATE.tab);
	// Panel to flash after a canvas click opened it. Held in state — not found
	// with a DOM query — so the class lands on the panel that was actually
	// opened rather than on whichever panel happens to be first in the document.
	const [highlightedPanel, setHighlightedPanel] = useState("");

	// TOGGLE PANEL BODY. `event` marks a canvas-driven open (card section click).
	const togglePanelBody = useCallback(
		(panel, event = false) => {
			if (event) {
				// Same section clicked again: leave the panel — and the tab the
				// user is on — untouched.
				if (openedPanelBody === panel) {
					return;
				}

				setOpenedPanelBody(panel);
				setActiveTab(DEFAULT_STATE.tab);
				setHighlightedPanel(panel);
				return;
			}

			setOpenedPanelBody((prev) => (prev === panel ? "" : panel));
			// RESET DEFAULT TAB.
			setActiveTab(DEFAULT_STATE.tab);
		},
		[openedPanelBody]
	);

	// DROP THE HIGHLIGHT once the animation has played (cleared on unmount too).
	useEffect(() => {
		if (!highlightedPanel) {
			return undefined;
		}

		const timer = setTimeout(() => setHighlightedPanel(""), HIGHLIGHT_DURATION);
		return () => clearTimeout(timer);
	}, [highlightedPanel]);

	// TOGGLE ACTIVE TAB.
	const toggleActiveTab = useCallback(
		(tabName) => {
			setActiveTab(tabName);
		},
		[openedPanelBody]
	);
	// CONTEXT VALUE.
	const contextValue = {
		activeTab,
		toggleActiveTab,
		openedPanelBody,
		togglePanelBody,
		highlightedPanel,
	};

	return <TogglePanelBodyContext.Provider value={contextValue}>{children}</TogglePanelBodyContext.Provider>;
};

export const useTogglePanelBody = () => useContext(TogglePanelBodyContext);
