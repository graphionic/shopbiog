import { __ } from "@wordpress/i18n";
import { CarouselBlockIcon } from "../blocks/carousel/icon";
import { GridBlockIcon } from "../blocks/grid/icon";
import { SubmissionFormBlockIcon } from "../blocks/testimonial-submission-form/icon";
import {
	MasonryBlockIcon,
	MarqueeBlockIcon,
	BentoGridBlockIcon,
	PolaroidGridBlockIcon,
} from "../blocks/proBlocks/icons";
import { SliderBlockIcon } from "../blocks/slider/icon";
import { TestimonialShortcodeBlockIcon } from "../blocks/shortcode/icon";
import { TestimonialFormShortcodeBlockIcon } from "../blocks/shortcode-form/icon";
import { AjaxPaginationBlockIcon } from "../blocks/ajax-pagination/icon";
import {
	AjaxSearchBlockIcon,
	FilterByGroupBlockIcon,
	FilterByRatingBlockIcon,
	LiveFrontendFilterBlockIcon,
	ReviewSummaryBlockIcon,
} from "../icons/proBlocksIcon";

export const docsBaseUrl = "https://docs.realtestimonials.io/guide/";

export const generateDocLink = (link) => {
	return `${docsBaseUrl}${link}`;
};

/**
 * Deep-link into a fresh page with the Ready Patterns library auto-opened and its
 * Block Type facet preset to `blockSlug`. Replaces the old demo.realtestimonials
 * links, which pointed at a host that never resolved.
 *
 * The query-arg shape lives here only — the editor side reads the same arg names
 * from `AUTO_OPEN` in `app/ready-patterns/constants.js`.
 *
 * @param {string} adminUrl  Trailing-slashed wp-admin URL (dashboardInfo.adminUrl).
 * @param {string} blockSlug Pattern block slug, e.g. "carousel".
 * @returns {string} The post-new.php URL.
 */
export const generatePatternLibraryLink = (adminUrl, blockSlug) => {
	return `${adminUrl}post-new.php?post_type=page&realpatterns=true&rtp_pattern_block=${blockSlug}`;
};

export const testimonialBlocksInfo = {
	"sp-testimonial-pro/live-frontend-filter": {
		Icon: LiveFrontendFilterBlockIcon,
		title: __("Live Frontend Filter", "testimonial-free"),
		docLink: generateDocLink("blocks/live-frontend-filter"),
	},
	"sp-testimonial-pro/filter-by-group": {
		Icon: FilterByGroupBlockIcon,
		title: __("Filter By Group", "testimonial-free"),
		docLink: generateDocLink("blocks/filter-by-group"),
	},
	"sp-testimonial-pro/filter-by-rating": {
		Icon: FilterByRatingBlockIcon,
		title: __("Filter By Rating", "testimonial-free"),
		docLink: generateDocLink("blocks/filter-by-rating"),
	},
	"sp-testimonial-pro/review-summary": {
		Icon: ReviewSummaryBlockIcon,
		title: __("AI Review Summary", "testimonial-free"),
		docLink: generateDocLink("blocks/review-summary"),
	},
	"sp-testimonial-pro/ajax-testimonial-search": {
		Icon: AjaxSearchBlockIcon,
		title: __("Ajax Testimonial Search", "testimonial-free"),
		docLink: generateDocLink("blocks/ajax-testimonial-search"),
	},
	"sp-testimonial-pro/ajax-pagination": {
		Icon: AjaxPaginationBlockIcon,
		title: __("Ajax Pagination", "testimonial-free"),
		docLink: generateDocLink("blocks/ajax-pagination"),
	},
	// parent blocks.
	"sp-testimonial-pro/carousel": {
		Icon: CarouselBlockIcon,
		title: __("Carousel", "testimonial-free"),
		docLink: generateDocLink("blocks/carousel"),
		patternSlug: "carousel",
	},
	"sp-testimonial-pro/marquee": {
		Icon: MarqueeBlockIcon,
		title: __("Marquee", "testimonial-free"),
		docLink: generateDocLink("blocks/marquee"),
		patternSlug: "marquee",
	},
	"sp-testimonial-pro/slider": {
		Icon: SliderBlockIcon,
		title: __("Slider", "testimonial-free"),
		docLink: generateDocLink("blocks/slider"),
		patternSlug: "slider",
	},
	"sp-testimonial-pro/grid": {
		Icon: GridBlockIcon,
		title: __("Grid", "testimonial-free"),
		docLink: generateDocLink("blocks/grid"),
		patternSlug: "grid",
	},
	"sp-testimonial-pro/bento-grid": {
		Icon: BentoGridBlockIcon,
		title: __("Bento Grid", "testimonial-free"),
		docLink: generateDocLink("blocks/bento-grid"),
		patternSlug: "bento-grid",
	},
	"sp-testimonial-pro/polaroid-grid": {
		Icon: PolaroidGridBlockIcon,
		title: __("Polaroid Grid", "testimonial-free"),
		docLink: generateDocLink("blocks/polaroid-grid"),
		patternSlug: "polaroid-grid",
	},
	"sp-testimonial-pro/masonry": {
		Icon: MasonryBlockIcon,
		title: __("Masonry", "testimonial-free"),
		docLink: generateDocLink("blocks/masonry"),
		patternSlug: "masonry",
	},
	"sp-testimonial-pro/testimonial-submission-form": {
		Icon: SubmissionFormBlockIcon,
		title: __("Testimonial Submission Form", "testimonial-free"),
		docLink: generateDocLink("blocks/submission-form"),
		patternSlug: "testimonial-submission-form",
	},
	// shortcode blocks.
	"sp-testimonial-pro/shortcode": {
		Icon: TestimonialShortcodeBlockIcon,
		title: __("Real Testimonials Shortcode", "testimonial-free"),
		docLink: generateDocLink("blocks/real-testimonials-shortcode"),
	},
	"sp-testimonial-pro/form": {
		Icon: TestimonialFormShortcodeBlockIcon,
		title: __("Testimonials Form Shortcode", "testimonial-free"),
		docLink: generateDocLink("blocks/testimonials-form-shortcode"),
	},
};

export const pricingPageUrl = "https://realtestimonials.io/pricing/";
