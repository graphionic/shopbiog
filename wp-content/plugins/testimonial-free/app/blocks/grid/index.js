import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import metadata from "./block.json";
import { GridBlockIcon } from "./icon";
import EditorWrapper from "../shared/EditorWrapper";

const blockOptions = {
	...metadata,
	icon: GridBlockIcon,
	edit: EditorWrapper,
	save: () => null,
};

const registerBlockTypeFn = () => {
	if (isActiveBlock(blockOptions.name)) {
		registerBlockType(blockOptions.name, blockOptions);
	}
};

registerBlockTypeFn();
