import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { SliderBlockIcon } from "./icon";
import EditorWrapper from "../shared/EditorWrapper";

const blockOptions = {
	...metadata,
	icon: SliderBlockIcon,
	edit: EditorWrapper,
	save: () => null,
};

const registerBlockTypeFn = () => {
	if (isActiveBlock(blockOptions.name)) {
		registerBlockType(blockOptions.name, blockOptions);
	}
};

registerBlockTypeFn();
