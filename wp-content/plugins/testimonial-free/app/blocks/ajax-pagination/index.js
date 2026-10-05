import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { AjaxPaginationBlockIcon } from "./icon";
import AjaxPaginationEdit from "./Edit";

const blockOptions = {
	...metadata,
	icon: AjaxPaginationBlockIcon,
	edit: AjaxPaginationEdit,
	save: () => null,
};

const registerBlockTypeFn = () => {
	if (isActiveBlock(blockOptions.name)) {
		registerBlockType(blockOptions.name, blockOptions);
	}
};

registerBlockTypeFn();
