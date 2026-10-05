/**
 * Pro block metadata.
 *
 * One entry per Pro-only block. Mirrors the former per-block `block.json`
 * files, converted to JS so icons/preview components can be referenced
 * directly and every block registered from a single loop (see index.js).
 */
import { __ } from "@wordpress/i18n";
import {
	BentoGridBlockIcon,
	BentoGridBlockPreviewImage,
	MarqueeBlockIcon,
	MarqueeBlockPreviewImage,
	MasonryBlockIcon,
	MasonryBlockPreviewImage,
	PolaroidGridBlockIcon,
	PolaroidGridBlockPreviewImage,
} from "./icons";

// baseMetadata.
const baseMetadata = {
	apiVersion: 3,
	category: "sp-testimonial-pro-blocks",
	supports: {
		align: ["wide", "full"],
		customClassName: true,
	},
	attributes: {
		isPreview: {
			type: "boolean",
			default: false,
		},
		align: {
			type: "string",
			default: "wide",
		},
	},
	example: {
		attributes: {
			isPreview: true,
		},
	},
};

const proBlocks = [
	{
		...baseMetadata,
		name: "sp-testimonial-pro/bento-grid",
		title: __("Bento Grid", "testimonial-free"),
		description: __("Modern mixed-size testimonial cards in a stylish bento grid.", "testimonial-free"),
		icon: BentoGridBlockIcon,
		previewImage: BentoGridBlockPreviewImage,
	},
	{
		...baseMetadata,
		name: "sp-testimonial-pro/marquee",
		title: __("Marquee", "testimonial-free"),
		description: __("Display testimonials in a continuous horizontal marquee.", "testimonial-free"),
		icon: MarqueeBlockIcon,
		previewImage: MarqueeBlockPreviewImage,
	},
	{
		...baseMetadata,
		name: "sp-testimonial-pro/masonry",
		title: __("Masonry", "testimonial-free"),
		description: __("Dynamic masonry layout for testimonials with varying content lengths.", "testimonial-free"),
		icon: MasonryBlockIcon,
		previewImage: MasonryBlockPreviewImage,
	},
	{
		...baseMetadata,
		name: "sp-testimonial-pro/polaroid-grid",
		title: __("Polaroid Grid", "testimonial-free"),
		description: __("Display testimonials in a stylish Polaroid-style photo grid layout.", "testimonial-free"),
		icon: PolaroidGridBlockIcon,
		previewImage: PolaroidGridBlockPreviewImage,
	},
];

export default proBlocks;
