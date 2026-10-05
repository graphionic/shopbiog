import { useDispatch, useSelect } from "@wordpress/data";
import { createBlock } from "@wordpress/blocks";
import { useEffect, useRef } from "@wordpress/element";

const TESTIMONIAL_GROUP_BLOCK = "sp-testimonial-pro/testimonial-group";
const LIVE_FILTER_BLOCK = "sp-testimonial-pro/live-frontend-filter";
const AJAX_SEARCH_BLOCK = "sp-testimonial-pro/ajax-testimonial-search";
const AJAX_PAGINATION_BLOCK = "sp-testimonial-pro/ajax-pagination";

const ADDON_BLOCKS = [LIVE_FILTER_BLOCK, AJAX_SEARCH_BLOCK, AJAX_PAGINATION_BLOCK];

/**
 * Hook to dynamically wrap the parent block with testimonial-group and manage
 * the live frontend filter, ajax search, and ajax pagination child blocks.
 *
 * @param {string} clientId   - Current parent block client ID
 * @param {Object} attributes - Current parent block attributes (cardDesign + addon toggles)
 */
const useRealChildBlocks = (clientId, attributes) => {
	const { cardDesign, enableLiveFrontendFilter, enableAjaxTestimonialSearch, enableAjaxPagination } = attributes;

	// Toggle state at this block's first render. An addon already on here arrived with
	// the block's insert defaults; one that flips on later is a deliberate inspector action.
	const togglesAtMount = useRef(null);
	if (null === togglesAtMount.current) {
		togglesAtMount.current = {
			[LIVE_FILTER_BLOCK]: !!enableLiveFrontendFilter,
			[AJAX_SEARCH_BLOCK]: !!enableAjaxTestimonialSearch,
			[AJAX_PAGINATION_BLOCK]: !!enableAjaxPagination,
		};
	}

	const { replaceBlocks, selectBlock, removeBlocks } = useDispatch("core/block-editor");
	const currentBlock = useSelect((select) => select("core/block-editor").getBlock(clientId), [clientId]);

	const parentClientId = useSelect(
		(select) => {
			const { getBlock, getBlockParents } = select("core/block-editor");
			const parentIds = getBlockParents(clientId);

			if (!parentIds?.length) {
				return null;
			}

			return (
				parentIds.find((parentId) => {
					const parentBlock = getBlock(parentId);
					return parentBlock?.name === TESTIMONIAL_GROUP_BLOCK;
				}) || null
			);
		},
		[clientId]
	);

	const parentBlock = useSelect(
		(select) => parentClientId && select("core/block-editor").getBlock(parentClientId),
		[parentClientId]
	);

	useEffect(() => {
		if (!currentBlock) {
			return;
		}

		// No card design picked yet → skip wrapping / child block creation.
		if (!cardDesign) {
			return;
		}

		const isParentBlockActive = parentBlock?.name === TESTIMONIAL_GROUP_BLOCK;
		const anyEnabled = enableLiveFrontendFilter || enableAjaxTestimonialSearch || enableAjaxPagination;

		const buildBeforeAddons = () => {
			const list = [];
			if (enableLiveFrontendFilter) {
				list.push(createBlock(LIVE_FILTER_BLOCK));
			}
			if (enableAjaxTestimonialSearch) {
				list.push(createBlock(AJAX_SEARCH_BLOCK));
			}
			return list;
		};

		const buildAfterAddons = () => {
			const list = [];
			if (enableAjaxPagination) {
				list.push(createBlock(AJAX_PAGINATION_BLOCK));
			}
			return list;
		};

		// CASE 1: Wrap parent block with testimonial-group + addon children
		if (!isParentBlockActive && anyEnabled) {
			const beforeAddons = buildBeforeAddons();
			const afterAddons = buildAfterAddons();
			const mainBlock = createBlock(currentBlock.name, currentBlock.attributes, currentBlock.innerBlocks);

			const newParentBlock = createBlock(
				TESTIMONIAL_GROUP_BLOCK,
				{
					align: currentBlock?.attributes?.align,
					uniqueId: currentBlock?.attributes?.uniqueId || "",
				},
				[...beforeAddons, mainBlock, ...afterAddons]
			);

			replaceBlocks([clientId], newParentBlock);

			// Addon whose toggle the user flipped on after mount → focus it (after-main
			// first, the order the old `lastAddon` used). Nothing flipped → this is the
			// insert-time wrap, so the main layout block keeps focus.
			const justToggledOn = [...afterAddons.slice().reverse(), ...beforeAddons.slice().reverse()].find(
				(block) => !togglesAtMount.current[block.name]
			);
			selectBlock(justToggledOn ? justToggledOn.clientId : mainBlock.clientId);
			return;
		}

		// CASE 2: Unwrap when all addon toggles are turned off
		if (isParentBlockActive && !anyEnabled) {
			const mainBlock = parentBlock.innerBlocks.find((block) => block.name === currentBlock.name);
			if (mainBlock) {
				const newBlock = createBlock(
					mainBlock.name,
					{
						...mainBlock.attributes,
						align: parentBlock?.attributes?.align,
					},
					mainBlock.innerBlocks
				);
				replaceBlocks([parentClientId], [newBlock]);
			}
			return;
		}

		// CASE 3: Already wrapped → sync addon children with toggles
		if (isParentBlockActive) {
			const existingBlocks = parentBlock.innerBlocks || [];
			const nonAddon = existingBlocks.filter((b) => !ADDON_BLOCKS.includes(b.name));

			const hasFilter = existingBlocks.some((b) => b.name === LIVE_FILTER_BLOCK);
			const hasSearch = existingBlocks.some((b) => b.name === AJAX_SEARCH_BLOCK);
			const hasAjaxPagination = existingBlocks.some((b) => b.name === AJAX_PAGINATION_BLOCK);

			const needsFilter = enableLiveFrontendFilter && !hasFilter;
			const needsSearch = enableAjaxTestimonialSearch && !hasSearch;
			const needsAjaxPagination = enableAjaxPagination && !hasAjaxPagination;
			const dropFilter = !enableLiveFrontendFilter && hasFilter;
			const dropSearch = !enableAjaxTestimonialSearch && hasSearch;
			const dropAjaxPagination = !enableAjaxPagination && hasAjaxPagination;

			if (
				!needsFilter &&
				!needsSearch &&
				!needsAjaxPagination &&
				!dropFilter &&
				!dropSearch &&
				!dropAjaxPagination
			) {
				return;
			}

			const cloneOrCreate = (blockName) => {
				const existing = existingBlocks.find((b) => b.name === blockName);
				return existing
					? createBlock(blockName, existing.attributes, existing.innerBlocks)
					: createBlock(blockName);
			};

			const newBeforeAddons = [];
			if (enableLiveFrontendFilter) {
				newBeforeAddons.push(cloneOrCreate(LIVE_FILTER_BLOCK));
			}
			if (enableAjaxTestimonialSearch) {
				newBeforeAddons.push(cloneOrCreate(AJAX_SEARCH_BLOCK));
			}

			const newAfterAddons = [];
			if (enableAjaxPagination) {
				newAfterAddons.push(cloneOrCreate(AJAX_PAGINATION_BLOCK));
			}

			const updatedParentBlock = createBlock(TESTIMONIAL_GROUP_BLOCK, parentBlock.attributes, [
				...newBeforeAddons,
				...nonAddon,
				...newAfterAddons,
			]);
			replaceBlocks([parentClientId], updatedParentBlock);

			const newlyAdded =
				(needsAjaxPagination && newAfterAddons.find((b) => b.name === AJAX_PAGINATION_BLOCK)) ||
				(needsFilter && newBeforeAddons.find((b) => b.name === LIVE_FILTER_BLOCK)) ||
				(needsSearch && newBeforeAddons.find((b) => b.name === AJAX_SEARCH_BLOCK));
			if (newlyAdded) {
				selectBlock(newlyAdded.clientId);
			}
		}
	}, [
		cardDesign,
		enableLiveFrontendFilter,
		enableAjaxTestimonialSearch,
		enableAjaxPagination,
		clientId,
		currentBlock,
		parentBlock,
		parentClientId,
		replaceBlocks,
		selectBlock,
		removeBlocks,
	]);
};

export default useRealChildBlocks;
