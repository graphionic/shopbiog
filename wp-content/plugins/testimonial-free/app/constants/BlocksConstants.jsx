import { __ } from "@wordpress/i18n";
import {
	AlignCenter,
	AlignLeft,
	AlignRight,
	Dots,
	DynamicDots,
	Fraction,
	Numbers,
	Scrollbar,
	Stepper,
	Strokes,
} from "@testimonial/icons";
import {
	ArrowLine,
	ArrowMinimal,
	ArrowOutline,
	ArrowSend,
	ArrowSolid,
	ChevronBold,
	ChevronBorderLine,
	ChevronOutline,
	ChevronSolid,
	DoubleChevron,
	DoubleChevronOutline,
	TriangleOutline,
} from "../components";

export const borderStyles = [
	{
		label: <span className="sp-real-border-none">None</span>,
		value: "none",
	},
	{
		label: <span className="sp-real-border-solid"></span>,
		value: "solid",
	},
	{
		label: <span className="sp-real-border-dashed"></span>,
		value: "dashed",
	},
	{
		label: <span className="sp-real-border-dotted"></span>,
		value: "dotted",
	},
	{
		label: <span className="sp-real-border-double"></span>,
		value: "double",
	},
];

export const cardContentLabels = {
	reviewer_image: __("Reviewer Image", "testimonial-free"),
	testimonial_title: __("Testimonial Title", "testimonial-free"),
	testimonial_text: __("Testimonial Text", "testimonial-free"),
	rating: __("Rating", "testimonial-free"),
	reviewer_name: __("Reviewer Name", "testimonial-free"),
	designation: __("Designation", "testimonial-free"),
	company_info: __("Company Info", "testimonial-free"),
	location: __("Location", "testimonial-free"),
	date: __("Date", "testimonial-free"),
	social_profiles: __("Social Profiles", "testimonial-free"),
	custom_fields: __("Custom Fields", "testimonial-free"),
};

export const SWIPER_PAGINATION_DOTS_OPTIONS = [
	{ label: "Dots", value: "dots", icon: <Dots /> },
	{
		label: "Stepper",
		value: "stepper",
		icon: <Stepper />,
	},
	{
		label: "Dynamic",
		value: "dynamic",
		icon: <DynamicDots />,
		isPro: true,
	},
	{
		label: "Strokes",
		value: "strokes",
		icon: <Strokes />,
		isPro: true,
	},
	{
		label: "Scrollbar",
		value: "scrollbar",
		icon: <Scrollbar />,
		isPro: true,
	},
	{
		label: "Fraction",
		value: "fraction",
		icon: <Fraction />,
		isPro: true,
	},
	{
		label: "Numbers",
		value: "number",
		icon: <Numbers />,
		isPro: true,
	},
];

export const textAlignmentOptions = [
	{ label: <AlignLeft />, value: "left", tooltip: "Left" },
	{ label: <AlignCenter />, value: "center", tooltip: "Center" },
	{ label: <AlignRight />, value: "right", tooltip: "Right" },
];

/**
 * Arrow icon options for Next/Previous Icon picker.
 * Each option: { label, value, icon: Component }
 */
export const ARROW_ICON_OPTIONS = [
	{ label: "Chevron Solid", value: "chevron-solid", icon: <ChevronSolid /> },
	{ label: "Chevron Outline", value: "chevron-outline", icon: <ChevronOutline /> },
	{ label: "Chevron Bold", value: "chevron-bold", icon: <ChevronBold /> },
	{ label: "Arrow Solid", value: "arrow-solid", icon: <ArrowSolid /> },
	{ label: "Arrow Minimal", value: "arrow-minimal", icon: <ArrowMinimal /> },
	{ label: "Double Chevron", value: "double-chevron", icon: <DoubleChevron />, isPro: true },
	{ label: "Arrow Outline", value: "arrow-outline", icon: <ArrowOutline />, isPro: true },
	{ label: "Chevron Border Line", value: "chevron-border-line", icon: <ChevronBorderLine />, isPro: true },
	{ label: "Double Chevron Outline", value: "double-chevron-outline", icon: <DoubleChevronOutline />, isPro: true },
	{ label: "Triangle Outline", value: "triangle-outline", icon: <TriangleOutline />, isPro: true },
	{ label: "Arrow Line", value: "arrow-line", icon: <ArrowLine />, isPro: true },
	{ label: "Arrow Send", value: "arrow-send", icon: <ArrowSend />, isPro: true },
];

export const cardItemsPositions = {
	"design-one": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
	"design-two": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
	"design-three": [1, 2, 4, 3, 5, 6, 7, 8, 9, 10, 11],
	"design-four": [1, 5, 4, 6, 2, 3, 7, 8, 9, 10, 11],
	"design-five": [4, 2, 3, 1, 5, 6, 7, 8, 9, 10, 11],
	"design-six": [4, 2, 3, 1, 5, 6, 7, 8, 9, 10, 11],
};
