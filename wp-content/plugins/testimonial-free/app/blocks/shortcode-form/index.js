import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { TestimonialFormShortcodeBlockIcon } from "./icon";
import FormEdit from "./Edit";

const blockOptions = {
	...metadata,
	icon: TestimonialFormShortcodeBlockIcon,
	edit: FormEdit,
	save: () => null,
};

if (isActiveBlock(blockOptions.name)) {
	registerBlockType(blockOptions.name, blockOptions);
}
