import { __ } from "@wordpress/i18n";
import { parse } from "@wordpress/blocks";
import { useState, useEffect, useRef, useCallback } from "@wordpress/element";
import StarterSites from "./StarterSites";
import PreviewDrawer from "./PreviewDrawer";
import ErrorBoundary from "./ErrorBoundary";
import { RealTestimonialLogo } from "./Icons";
import { API_ENDPOINTS, KEYBOARD_KEYS } from "./constants";
import "./editor.scss";

// Default query-attribute values — reset on insert so an imported pattern
// queries the user's own testimonials instead of the demo's saved selection.
const getDefaultAttributes = () => ({
	filterBy: "latest",
	includesItems: [],
	excludesItems: [],
	limit: "6",
	enableRandomOrder: false,
	orderBy: "date",
	order: "DESC",
});

/**
 * Reset query-related attributes to defaults after parsing, before insertion.
 * Recurses through innerBlocks. Only touches sp-testimonial-pro/* blocks.
 *
 * @param {Array} blocks - Parsed block objects.
 * @returns {Array} Modified blocks.
 */
const modifyBlockAttributes = (blocks) => {
	if (!Array.isArray(blocks)) {
		return blocks;
	}

	return blocks.map((block) => {
		const modifiedBlock = {
			...block,
			attributes: { ...block.attributes },
		};

		if (block.name && block.name.startsWith("sp-testimonial-pro/") && block.attributes) {
			const defaults = getDefaultAttributes();
			Object.keys(defaults).forEach((attrName) => {
				if (modifiedBlock.attributes.hasOwnProperty(attrName)) {
					const defaultValue = defaults[attrName];
					modifiedBlock.attributes[attrName] = Array.isArray(defaultValue)
						? JSON.parse(JSON.stringify(defaultValue))
						: defaultValue;
				}
			});
		}

		if (Array.isArray(block.innerBlocks) && block.innerBlocks.length > 0) {
			modifiedBlock.innerBlocks = modifyBlockAttributes(block.innerBlocks);
		}

		return modifiedBlock;
	});
};

// Main Library Component.
const Library = (props) => {
	const [wishListArr, setWishlistArr] = useState([]);
	// Pattern currently shown in the preview drawer (null = drawer closed). A ref
	// mirrors it so the document-level ESC handler (registered once) can read the
	// latest value without a stale closure.
	const [previewPattern, setPreviewPattern] = useState(null);
	const previewPatternRef = useRef(null);
	const modalRef = useRef(null);

	const openPreview = useCallback((pattern) => {
		previewPatternRef.current = pattern;
		setPreviewPattern(pattern);
	}, []);
	const closePreview = useCallback(() => {
		previewPatternRef.current = null;
		setPreviewPattern(null);
	}, []);
	const isBlockPattern = props.currentBlockName ? true : false;
	const currentBlockName = props.currentBlockName || "all";
	// `initialBlockType` only *preselects* the Block Type facet — unlike
	// `currentBlockName` it keeps the sidebar visible and `designs` holding the
	// full catalog, so the user can widen back out to "All Patterns".
	const initialBlockType = !isBlockPattern && props.initialBlockType ? props.initialBlockType : "";
	// The preselect is a one-shot: once consumed, a later refetch (the Refresh
	// button) must keep whatever facet the user has since chosen.
	const preselectAppliedRef = useRef(false);

	const [state, setState] = useState({
		isPopup: props.isShow || false,
		designs: [],
		reloadId: "",
		reload: false,
		error: false,
		fetching: false,
		designFilter: initialBlockType || currentBlockName || "all",
		current: [],
		sidebarOpen: true,
		templatekitCol: "sp-real-pattern-col3",
		blocks: [],
		categoryTypes: [],
		freeCount: false,
		proCount: false,
		loading: false,
	});

	const { isPopup, designFilter, reload, reloadId } = state;
	// Flatten the grouped `patterns` object ({ blockSlug: items[] }) into a single
	// array, tagging each item with its block slug so it can be filtered by block.
	// The item's own `category` (numeric category-type ids) is preserved untouched.
	const handleDesignData = (patternsData) => {
		const transformedData = [];
		for (const blockSlug in patternsData) {
			if (!Array.isArray(patternsData[blockSlug])) {
				continue;
			}
			patternsData[blockSlug].forEach((item) => {
				transformedData.push({
					...item,
					blockCategory: blockSlug,
				});
			});
		}
		return transformedData;
	};

	// Fetch Premade pattern data from Location Weather REST API.
	const fetchTemplates = async () => {
		setState((prev) => ({ ...prev, loading: true, error: false }));
		try {
			const response = await wp.apiFetch({
				path: API_ENDPOINTS.PATTERNS,
				method: "POST",
				data: { type: "get_data" },
			});

			if (!response) {
				throw new Error(__("No response received from server", "testimonial-free"));
			}

			if (response.success && response.data) {
				const parsed = JSON.parse(response.data);

				// New API shape: { meta_data: { blocks, categories }, patterns: { blockSlug: [] } }.
				// Block types and category types both come from meta_data — nothing hard-coded.
				// Each meta entry carries its own { label, value, count }, so the filter UI
				// reads count straight off the object — no separate count maps.
				const meta = parsed && parsed.meta_data ? parsed.meta_data : {};
				const blocks = Array.isArray(meta.blocks) ? meta.blocks : [];
				const categoryTypes = Array.isArray(meta.categories) ? meta.categories : [];
				const patternsData =
					parsed && parsed.patterns && typeof parsed.patterns === "object" ? parsed.patterns : {};

				const designData = handleDesignData(patternsData);
				const freeCount = designData.filter((data) => !data.pro).length;
				const proCount = designData.filter((data) => data.pro).length;

				let categorisedData = [];
				const dataForCategory = isBlockPattern && currentBlockName;

				if (dataForCategory) {
					categorisedData = patternsData[currentBlockName] || [];
				}

				// The preselect arrives from a URL arg, so only honour it when the
				// catalog actually advertises that block — an unknown slug would
				// otherwise leave the grid permanently empty.
				const isKnownBlockType =
					initialBlockType && blocks.some((block) => String(block.value) === String(initialBlockType));
				const preselected = isKnownBlockType ? initialBlockType : "all";

				setState((prev) => {
					// First load honours the preselect; a refetch keeps the live facet.
					const nextFilter = preselectAppliedRef.current ? prev.designFilter : preselected;

					return {
						...prev,
						// `designs` always holds the full catalog outside single-block mode,
						// so switching the facet back to "All Patterns" still works.
						current: dataForCategory ? categorisedData : filterByCategoryKey(designData, nextFilter),
						designs: dataForCategory ? categorisedData : designData,
						designFilter: dataForCategory ? prev.designFilter : nextFilter,
						blocks,
						categoryTypes,
						freeCount,
						proCount,
						loading: false,
						error: false,
					};
				});
				preselectAppliedRef.current = true;
			} else {
				throw new Error(response.message || __("Failed to load patterns", "testimonial-free"));
			}
		} catch (error) {
			console.error("Error fetching templates:", error);
			setState((prev) => ({
				...prev,
				loading: false,
				error: error.message || __("An unexpected error occurred", "testimonial-free"),
			}));
		}
	};

	// Force fetch and refresh local JSON cache
	const fetchAllData = async () => {
		setState((prev) => ({ ...prev, fetching: true }));

		try {
			const response = await wp.apiFetch({
				path: API_ENDPOINTS.PATTERNS,
				method: "POST",
				data: { type: "refresh" }, // tells PHP to re-fetch from remote
			});

			if (!response) {
				throw new Error(__("No response received from server", "testimonial-free"));
			}

			if (response.success) {
				// after successful refresh, reload data
				await fetchTemplates();
			} else {
				throw new Error(response.message || __("Failed to refresh patterns", "testimonial-free"));
			}
		} catch (error) {
			console.error("Error fetching all data:", error);
			setState((prev) => ({
				...prev,
				error: error.message || __("Failed to refresh data", "testimonial-free"),
			}));
		} finally {
			setState((prev) => ({ ...prev, fetching: false }));
		}
	};

	// Close modal
	const closeModal = () => {
		const element = document.querySelector(".sp-real-patterns-builder-modal");
		if (element) {
			element.remove();
		}
		setState((prev) => ({ ...prev, isPopup: false }));
	};

	// Clicking the dimmed backdrop (outside the modal box) closes the modal — but
	// never while the preview drawer is open (the drawer owns that interaction).
	const handleBackdropMouseDown = (e) => {
		if (previewPatternRef.current) {
			return;
		}
		if (modalRef.current && !modalRef.current.contains(e.target)) {
			closeModal();
		}
	};

	// Handle ESC key press
	const handleKeyDown = (e) => {
		if (e.keyCode === KEYBOARD_KEYS.ESCAPE) {
			if (previewPatternRef.current) {
				closePreview();
			} else {
				closeModal();
			}
		}
	};

	// Insert block into editor
	const insertBlock = async (templateID) => {
		if (!templateID) {
			return;
		}
		// delete block if user insert pattern from layout selector popup.
		if (props?.removeBlock) {
			props?.removeBlock();
		}
		setState((prev) => ({
			...prev,
			reload: true,
			reloadId: templateID,
		}));
		try {
			const response = await fetch(API_ENDPOINTS.SINGLE_PATTERN, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: new URLSearchParams({
					license: "",
					template_id: templateID,
				}),
			});

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}
			const jsonData = await response.json();
			if (jsonData.success && jsonData.rawData) {
				const blockEditor = wp.data.dispatch("core/block-editor");
				if (blockEditor && blockEditor.insertBlocks) {
					// Parse the raw block data - parse() returns an array of blocks
					let blocks = [];
					try {
						blocks = parse(jsonData.rawData);
					} catch (parseError) {
						throw new Error(__("Failed to parse block data", "testimonial-free"));
					}

					// Validate that blocks is an array and not empty
					if (!Array.isArray(blocks)) {
						throw new Error(__("Invalid block data format", "testimonial-free"));
					}

					if (blocks.length === 0) {
						throw new Error(__("No blocks found in pattern", "testimonial-free"));
					}

					// Reset query attrs so the pattern uses the user's content.
					blocks = modifyBlockAttributes(blocks);

					// Insert all blocks into the editor
					// insertBlocks accepts an array of block objects
					blockEditor.insertBlocks(blocks);

					closeModal();
					setState((prev) => ({
						...prev,
						isPopup: false,
						reload: false,
						reloadId: "",
						error: false,
					}));
				} else {
					throw new Error(__("Block editor is not available", "testimonial-free"));
				}
			} else {
				throw new Error(jsonData.message || __("Failed to import pattern", "testimonial-free"));
			}
		} catch (error) {
			setState((prev) => ({
				...prev,
				error: error.message || __("Failed to import pattern", "testimonial-free"),
				reload: false,
			}));
		}
	};

	// Handle block import
	// `isPro` patterns build on Pro-only blocks — the grid card and the preview
	// drawer both offer an Upgrade link instead of Insert, and this is the
	// backstop so no caller can import one anyway.
	const handleBlockImport = (templateID, isPro = false) => {
		if (isPro) {
			return;
		}
		insertBlock(templateID);
	};

	// Split archive data by block slug (set on each item as `blockCategory`).
	const filterByCategoryKey = (data = [], key = "") => {
		// Return early if no data or not an array
		if (!Array.isArray(data) || data.length === 0) {
			return [];
		}

		// If key is empty or 'all', return all data
		if (!key || key === "all") {
			return data;
		}

		return data.filter((item) => item?.blockCategory === key);
	};

	// Handle wishlist actions
	const handleWishlistAction = async (id, action = "", type = "") => {
		try {
			const response = await wp.apiFetch({
				path: API_ENDPOINTS.WISHLIST,
				method: "POST",
				data: { id, action, type },
			});

			if (!response) {
				throw new Error(__("No response received from server", "testimonial-free"));
			}

			if (response.success) {
				const wishlist = Array.isArray(response.wishListArr)
					? response.wishListArr
					: Object.values(response.wishListArr || {});
				setWishlistArr(wishlist);
			} else {
				throw new Error(response.message || __("Failed to update wishlist", "testimonial-free"));
			}
		} catch (error) {
			console.error("Error updating wishlist:", error);
			// Optionally show user-friendly error message
		}
	};

	// Initialize on mount
	useEffect(() => {
		handleWishlistAction("", "", "fetchData");
		fetchTemplates();
		document.addEventListener("keydown", handleKeyDown);

		return () => {
			document.removeEventListener("keydown", handleKeyDown);
		};
	}, []);

	return (
		<>
			{isPopup && (
				<ErrorBoundary
					onError={(error, errorInfo) => {
						console.error("Library Error Boundary:", error, errorInfo);
					}}
					onRetry={fetchTemplates}
				>
					<div className="sp-real-ready-patterns-backdrop" onMouseDown={handleBackdropMouseDown}>
						<div
							className="sp-real-ready-patterns-modal"
							ref={modalRef}
							role="dialog"
							aria-modal="true"
							aria-label={__("Ready Patterns Library", "testimonial-free")}
						>
							<header className="sp-real-ready-patterns-header">
								<div className="sp-real-ready-patterns-brand">
									<RealTestimonialLogo />
									<h2 className="sp-real-ready-patterns-title">
										{__("Ready Patterns Library", "testimonial-free")}
									</h2>
								</div>
								<div className="sp-real-ready-patterns-header-actions">
									<button
										type="button"
										className="sp-real-ready-patterns-close"
										onClick={closeModal}
										aria-label={__("Close", "testimonial-free")}
									>
										<span className="dashicons dashicons-no-alt" />
									</button>
								</div>
							</header>

							<StarterSites
								filterValue={designFilter}
								currentBlockName={props.currentBlockName}
								isSingleBlock={isBlockPattern}
								state={state}
								setState={setState}
								_fetchFile={fetchAllData}
								_changeVal={handleBlockImport}
								filterByCategoryKey={filterByCategoryKey}
								setWListAction={handleWishlistAction}
								wishListArr={wishListArr}
								setWishlistArr={setWishlistArr}
								onPreview={openPreview}
							/>

							{previewPattern ? (
								<PreviewDrawer
									pattern={previewPattern}
									onClose={closePreview}
									onInsert={() => handleBlockImport(previewPattern.ID, previewPattern.pro)}
									isInserting={reload && reloadId === previewPattern.ID}
								/>
							) : null}
						</div>
					</div>
				</ErrorBoundary>
			)}
		</>
	);
};

export default Library;
