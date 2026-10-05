/**
 * Pro blocks registration.
 *
 * Registers every Pro-only block (bento-grid, marquee, masonry,
 * polaroid-grid, trustpilot-reviews) from a single config loop. Each block
 * renders a static preview via the shared ProBlockEdit; save returns null.
 */
import { registerBlockType } from "@wordpress/blocks";
import { isActiveBlock } from "@testimonial/controls";
import ProBlockEdit from "./ProBlockEdit";
import proBlocks from "./config";

proBlocks.forEach(({ previewImage, ...metadata }) => {
	if (!isActiveBlock(metadata.name)) {
		return;
	}

	registerBlockType(metadata.name, {
		...metadata,
		edit: ProBlockEdit,
		save: () => null,
	});
});
