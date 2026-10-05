import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { SubmissionFormBlockIcon } from "./icon";
import Edit from "./Edit";

const blockOptions = {
	...metadata,
	icon: SubmissionFormBlockIcon,
	edit: Edit,
	save: () => null,
};

const registerBlockTypeFn = () => {
	if (isActiveBlock(blockOptions.name)) {
		registerBlockType(blockOptions.name, blockOptions);
	}
};

registerBlockTypeFn();
