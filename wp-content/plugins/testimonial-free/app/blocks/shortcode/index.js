import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { TestimonialShortcodeBlockIcon } from "./icon";
import ShortcodeEdit from "./Edit";

const blockOptions = {
	...metadata,
	icon: TestimonialShortcodeBlockIcon,
	edit: ShortcodeEdit,
	save: () => null,
};

if (isActiveBlock(blockOptions.name)) {
	registerBlockType(blockOptions.name, blockOptions);
}
