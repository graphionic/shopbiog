import { select } from "@wordpress/data";
import { useEffect } from "@wordpress/element";

const getUniqueId = (blocks) => {
	return blocks.reduce(
		(result, block) => {
			if (block?.attributes?.uniqueId && block.name.includes("sp-testimonial-pro")) {
				result.blockIds.push(block.attributes.uniqueId);
				result.clientIds.push(block.clientId);
			}

			if (block.innerBlocks) {
				const { blockIds, clientIds } = getUniqueId(block.innerBlocks);
				result.blockIds = [...result.blockIds, ...blockIds];
				result.clientIds = [...result.clientIds, ...clientIds];
			}
			return result;
		},
		{ blockIds: [], clientIds: [] }
	);
};

const checkDuplicate = (blockIds, block_id, currentIndex) => {
	const getFiltered = blockIds.filter((el) => el === block_id);
	return getFiltered.length > 1 && currentIndex === blockIds.lastIndexOf(block_id);
};

const useUniqueId = (clientId, uniqueId, setAttributes, prefix = "sp-real-", saveIdForChild = false) => {
	// generate uniqueId based on client id.
	useEffect(() => {
		if (!clientId) {
			return;
		}
		const getStore = select("core/block-editor");
		const getAllBlocks = getStore?.getBlocks ? getStore.getBlocks() : null;
		const { blockIds, clientIds } = getAllBlocks ? getUniqueId(getAllBlocks) : { blockIds: [], clientIds: [] };
		const isDuplicateUniqueId = checkDuplicate(blockIds, uniqueId, clientIds.indexOf(clientId));

		if (!uniqueId || isDuplicateUniqueId) {
			const shortClientId = clientId.split("-")?.pop();
			const unique_id = `${prefix}${shortClientId}`;
			let updatedAttr = { uniqueId: unique_id };
			if (saveIdForChild) {
				const parent_id = clientId?.substring(clientId?.length - 6, clientId?.length);
				updatedAttr = { ...updatedAttr, parentId: parent_id };
			}
			setAttributes(updatedAttr);
		}
	}, [clientId, uniqueId]);
};

export default useUniqueId;
