/* eslint-disable no-nested-ternary */
/**
 * Real Testimonials Pro - Divi 5 Module
 *
 * React component for Divi 5 Visual Builder
 * Uses useFetch pattern following official Divi 5 Dynamic Module example
 *
 * @package Real_Testimonials_Pro
 */

import metadata from "./module.json";
import { SettingsContent } from "./settings-content";
import * as moduleTestimonialIcon from "./icons/module-testimonial";

// Access React from global scope (Divi provides this).
const React = window?.vendor?.React;
const { useEffect, useRef } = React || {};

// Access Divi globals - these are provided by Divi in the window object
const ModuleContainer = window?.divi?.module?.ModuleContainer;
const StyleContainer = window?.divi?.module?.StyleContainer;
const elementClassnames = window?.divi?.module?.elementClassnames;
const registerModule = window?.divi?.moduleLibrary?.registerModule;
const addAction = window?.vendor?.wp?.hooks?.addAction;
const addFilter = window?.vendor?.wp?.hooks?.addFilter;
const useFetch = window?.divi?.rest?.useFetch;

// ModuleStyles.
const ModuleStyles = ({ elements, settings, mode, state, noStyleTag }) => {
	return (
		<StyleContainer mode={mode} state={state} noStyleTag={noStyleTag}>
			{elements.style({
				attrName: "module",
				styleProps: { disabledOn: { disabledModuleVisibility: settings?.disabledModuleVisibility } },
			})}
		</StyleContainer>
	);
};

// ModuleScriptData.
const ModuleScriptData = ({ elements }) => {
	return <>{elements.scriptData({ attrName: "module" })}</>;
};

// moduleClassnames.
const moduleClassnames = ({ classnamesInstance, attrs }) => {
	classnamesInstance.add(elementClassnames({ attrs: attrs?.module?.decoration ?? {} }));
};

// EditRenderer.
const EditRenderer = ({ attrs, id, name, elements }) => {
	const templateId = attrs?.templateId?.innerContent?.desktop?.value || "0";

	// useFetch hook for fetching testimonial HTML from REST API.
	const { fetch, response, isLoading } = useFetch(null);

	// Reference for handling fetch abort (following Divi 5 pattern).
	const fetchAbortRef = useRef(null);

	// Track last fetched template ID to prevent duplicate requests.
	const lastFetchedRef = useRef(null);

	// Track which templates have had fonts loaded to prevent duplicates.
	const loadedFontsRef = useRef(new Set());

	// Load testimonial HTML when templateId changes (following Divi 5 pattern).
	useEffect(() => {
		// Don't fetch if no template selected.
		if (!templateId || templateId === "0") {
			return;
		}

		// Prevent duplicate requests for the same template ID.
		if (lastFetchedRef.current === templateId) {
			return;
		}

		// Mark this template as being fetched.
		lastFetchedRef.current = templateId;

		// Abort previous fetch if there's any.
		if (fetchAbortRef.current) {
			fetchAbortRef.current.abort();
		}

		// Create new AbortController instance.
		fetchAbortRef.current = new AbortController();

		// Fetch testimonial HTML from REST API (following Divi 5 useFetch pattern).
		fetch({
			method: "GET",
			restRoute: `/sp-rtp/divi5/v1/testimonial-html?template_id=${templateId}`,
		})
			.then(() => {
				setTimeout(() => {
					if (typeof window !== "undefined" && typeof window.spRealTestimonialInit === "function") {
						window.spRealTestimonialInit();
					}
				}, 0);
			})
			.catch((error) => {
				// Only log non-abort errors.
				if (error.name !== "AbortError") {
					console.error("Real Testimonials Pro: Error loading testimonial", error);
				}
			});

		// Cleanup function - abort fetch on unmount or templateId change.
		return () => {
			if (fetchAbortRef.current) {
				fetchAbortRef.current.abort();
				fetchAbortRef.current = null;
			}
		};
		// Only depend on templateId - NOT fetch (causes infinite loop)
	}, [templateId]);

	// Load Google Fonts when response is received.
	// NOTE: Dynamic CSS is now inline in the HTML response from REST API.
	useEffect(() => {
		// Only load fonts when not loading and we have a successful response.
		if (isLoading || !response || !response.success || !templateId || templateId === "0") {
			return;
		}

		// Load Google Fonts if available.
		if (response.css_info && response.css_info.fonts && Array.isArray(response.css_info.fonts)) {
			response.css_info.fonts.forEach((font) => {
				// Create a unique key for this font.
				const fontKey = font;
				if (!loadedFontsRef.current.has(fontKey)) {
					loadedFontsRef.current.add(fontKey);
					const fontId = `rtp-font-${font.replace(/:/g, "-")}`;
					if (!document.getElementById(fontId)) {
						const fontLink = document.createElement("link");
						fontLink.id = fontId;
						fontLink.rel = "stylesheet";
						fontLink.href = `https://fonts.googleapis.com/css?family=${font}`;
						document.head.appendChild(fontLink);
					}
				}
			});
		}
	}, [response, isLoading, templateId]);

	// Compute testimonial HTML from response.
	let testimonialHtml = '<div style="padding:20px;text-align:center;color:#999;">Failed to load testimonial</div>';

	if (response && response.success && response.html) {
		testimonialHtml = response.html;
	} else if (response && !response.success) {
		testimonialHtml = '<div style="padding:20px;text-align:center;color:#999;">Failed to load testimonial</div>';
	} else if (!response) {
		testimonialHtml = '<div style="padding:20px;text-align:center;color:#999;">No response from server</div>';
	}

	// Following Divi 5 pattern: conditional rendering inside return statement.
	return (
		<ModuleContainer
			attrs={attrs}
			elements={elements}
			id={id}
			moduleClassName="rtp_divi5_testimonial"
			name={name}
			scriptDataComponent={ModuleScriptData}
			stylesComponent={ModuleStyles}
			classnamesFunction={moduleClassnames}
		>
			{elements.styleComponents({ attrName: "module" })}
			<div className="et_pb_module_inner rtp-divi5-testimonial-wrapper" data-template-id={templateId}>
				{!templateId || templateId === "0" ? (
					<div
						style={{
							padding: "20px",
							textAlign: "center",
							color: "#999",
							borderRadius: "4px",
							border: "1px dotted #ddd",
						}}
					>
						<p style={{ fontSize: "14px", margin: "0" }}>Please select a saved template</p>
					</div>
				) : isLoading ? (
					<div
						style={{
							padding: "20px",
							textAlign: "center",
							color: "#666",
							borderRadius: "4px",
							border: "1px dotted #ddd",
						}}
					>
						<p>Loading template...</p>
					</div>
				) : (
					<div dangerouslySetInnerHTML={{ __html: testimonialHtml }} />
				)}
			</div>
		</ModuleContainer>
	);
};

/**
 * Real Testimonials Pro Module Definition
 * Exported for Divi 5 to pick up
 */
export const spRealDivi5Testimonial = {
	metadata,
	renderers: {
		edit: EditRenderer,
	},
	settings: {
		content: SettingsContent,
	},
};

// Also register via hooks for compatibility
addAction("divi.moduleLibrary.registerModuleLibraryStore.after", "rtp.divi5Testimonial", () => {
	registerModule(spRealDivi5Testimonial.metadata, spRealDivi5Testimonial);
});

/**
 * Publish the addon icon under the name module.json's `moduleIcon` points at.
 *
 * Divi 5 resolves module icons through this registry, not the PHP
 * `et_builder_module_icons` filter (that one only feeds Divi 4). Spreading the
 * incoming map is required — returning a bare object wipes every other
 * module's icon out of the library.
 */
if (addFilter) {
	addFilter("divi.iconLibrary.icon.map", "rtp.divi5Testimonial", (icons) => ({
		...icons,
		[moduleTestimonialIcon.name]: moduleTestimonialIcon,
	}));
}
