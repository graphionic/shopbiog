import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { TestimonialGroupIcon } from "./icon";
import Edit from "./Edit";
import Save from "./Save";

const blockOptions = {
	...metadata,
	icon: TestimonialGroupIcon,
	edit: Edit,
	save: Save,
};

const registerBlockTypeFn = () => {
	if (isActiveBlock(blockOptions.name)) {
		registerBlockType(blockOptions.name, blockOptions);
	}
};

registerBlockTypeFn();
