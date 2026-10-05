import { __ } from "@wordpress/i18n";
import {
	ButtonGroup,
	InputControl,
	SelectControl,
	SPRangeControl,
	ToggleControl,
	SortableItem,
	Divider,
	SelectDropdown,
	PresetPicker,
} from "@testimonial/components";
import {
	ARROW_ICON_OPTIONS,
	cardContentLabels,
	SWIPER_PAGINATION_DOTS_OPTIONS,
	RATING_ICON_STYLES,
	RatingIcon,
	textAlignmentOptions,
} from "@testimonial/constants";
import { restrictToVerticalAxis } from "@dnd-kit/modifiers";
import { DndContext, PointerSensor, useSensor, useSensors } from "@dnd-kit/core";
import { arrayMove, SortableContext, verticalListSortingStrategy } from "@dnd-kit/sortable";
import { componentSectionHeader, getModulesSettings, inArray, useDeviceType } from "@testimonial/controls";
import { carouselTemplateIcons } from "../carousel/icon";
import { cardDesignIcons } from "./Icons";
import { applyCardDesign } from "./cardDesign";
import { ProFeaturesBox, TestimonialContentProNote, ReviewerDetailsProNote } from "../../components/proControls";

// Card-content slots that are Pro-only in the free plugin. They stay in the
// `cardContents` list (data preserved) but render as a locked Pro toggle row.
const PRO_CARD_CONTENTS = ["company_info", "location", "date", "social_profiles", "custom_fields"];

// Layouts Tabs
export const LayoutsGeneralTab = ({ attributes, setAttributes }) => {
	const {
		blockName,
		cardDesign,
		carouselStyle,
		slidingEffect,
		columns,
		columnGap,
		rowGap,
		enableNavigationArrow,
		enablePaginationDots,
		enableSEOSchemaMarkup,
		// Marquee-only.
		marqueeDisplayStyle,
		marqueeRows,
		fadeSliderEdges,
		enableAjaxPagination,
	} = attributes;

	const isMarquee = blockName === "marquee";
	const isSlider = blockName === "slider";

	// Slider exposes every sliding effect directly (no carousel-style preset).
	const sliderSlidingEffects = [
		{ label: "Slide", value: "slide" },
		{ label: "Flip", value: "flip" },
		{ label: "Cube", value: "cube" },
		{ label: "Spring (Pro)", value: "creative", disabled: true },
		{ label: "Fade (Pro)", value: "fade", disabled: true },
		{ label: "Ken Burns (Pro)", value: "ken-burns", disabled: true },
	];

	const carouselSlidingEffects = [{ label: "Slide", value: "slide" }];

	if ("default" === carouselStyle) {
		carouselSlidingEffects.push(
			{ label: "Cube", value: "cube" },
			{ label: "Flip", value: "flip" },
			{ label: "Spring (Pro)", value: "creative", disabled: true },
			{ label: "Fade (Pro)", value: "fade", disabled: true }
		);
	}

	const onChangeCardDesign = (newDesign) => applyCardDesign(newDesign, attributes, setAttributes);

	let proFeatureList = [
		__("AI generated review summary 🔥", "testimonial-free"),
		__("Instant result with Ajax search", "testimonial-free"),
		__("Filter by groups, specific items", "testimonial-free"),
		__("Filter based on star rating", "testimonial-free"),
		__("Shuffle testimonials with random order", "testimonial-free"),
		__("Drag & drop to arrange testimonials your way", "testimonial-free"),
	];

	if ("carousel" === blockName) {
		proFeatureList = [
			__("3 dynamic carousel styles", "testimonial-free"),
			__("10+ premium card designs", "testimonial-free"),
			__("3+ smooth sliding effects", "testimonial-free"),
			__("Vertical layout for flexible display", "testimonial-free"),
			__("Partial slide view for a modern look", "testimonial-free"),
			...proFeatureList,
		];
	} else if ("slider" === blockName) {
		proFeatureList = [
			__("10+ premium card designs", "testimonial-free"),
			__("3+ smooth sliding effects", "testimonial-free"),
			__("Vertical layout for flexible display", "testimonial-free"),
			...proFeatureList,
		];
	} else {
		proFeatureList = [__("10+ premium card designs", "testimonial-free"), ...proFeatureList];
	}

	return (
		<>
			{"carousel" === blockName && (
				<PresetPicker
					label={__("Select Carousel Style", "testimonial-free")}
					items={carouselTemplateIcons}
					attributes={carouselStyle}
					attributesKey={"carouselStyle"}
					setAttributes={setAttributes}
					onClick={(value) => {
						setAttributes({
							carouselStyle: value,
							slidingEffect: "slide",
						});
					}}
				/>
			)}
			<PresetPicker
				label={__("Select Card Style", "testimonial-free")}
				items={cardDesignIcons}
				attributes={cardDesign}
				onClick={onChangeCardDesign}
			/>
			{"carousel" === blockName && (
				<>
					{carouselStyle === "default" && (
						<SelectControl
							label={__("Sliding Effects", "testimonial-free")}
							items={carouselSlidingEffects}
							attributes={slidingEffect}
							attributesKey={"slidingEffect"}
							setAttributes={setAttributes}
						/>
					)}
					<ButtonGroup
						label={__("Display Direction", "testimonial-free")}
						items={[
							{ label: "Horizontal", value: "horizontal" },
							{ label: "Vertical", value: "vertical", isPro: true },
						]}
						attributes={"horizontal"}
						attributesKey={"carouselDisplayStyle"}
						setAttributes={setAttributes}
					/>
					<ToggleControl label={__("Partial Slide View", "testimonial-free")} isPro />
				</>
			)}
			{isSlider && (
				<SelectControl
					label={__("Sliding Effects", "testimonial-free")}
					items={sliderSlidingEffects}
					attributes={slidingEffect}
					attributesKey={"slidingEffect"}
					setAttributes={setAttributes}
				/>
			)}
			{isMarquee && (
				<ButtonGroup
					label={__("Display Direction", "testimonial-free")}
					items={[
						{ label: "Horizontal", value: "horizontal" },
						{ label: "Vertical", value: "vertical" },
					]}
					attributes={marqueeDisplayStyle}
					attributesKey={"marqueeDisplayStyle"}
					setAttributes={setAttributes}
				/>
			)}
			{!isSlider && (
				<>
					<SPRangeControl
						label={__("Columns", "testimonial-free")}
						attributes={columns}
						attributesKey={"columns"}
						setAttributes={setAttributes}
						max={20}
						min={1}
						defaultValue={{ unit: "", value: 3 }}
						units={false}
					/>
					<SPRangeControl
						label={__("Column Gap", "testimonial-free")}
						attributes={columnGap}
						attributesKey={"columnGap"}
						setAttributes={setAttributes}
						max={100}
						defaultValue={{ unit: "px", value: 24 }}
					/>
				</>
			)}
			{isMarquee && (
				<SPRangeControl
					label={__("Row", "testimonial-free")}
					attributes={marqueeRows}
					attributesKey={"marqueeRows"}
					setAttributes={setAttributes}
					max={3}
					defaultValue={{ unit: "", value: 1 }}
					units={false}
				/>
			)}
			{inArray(["grid", "masonry", "marquee", "bento-grid"], blockName) && (
				<SPRangeControl
					label={__("Row Gap", "testimonial-free")}
					attributes={rowGap}
					attributesKey={"rowGap"}
					setAttributes={setAttributes}
					max={100}
					defaultValue={{ unit: "px", value: 24 }}
				/>
			)}
			{isMarquee && (
				<ToggleControl
					label={__("Fade Slider Edges", "testimonial-free")}
					attributes={fadeSliderEdges}
					attributesKey={"fadeSliderEdges"}
					setAttributes={setAttributes}
				/>
			)}
			{inArray(["carousel", "slider"], blockName) && (
				<>
					<ToggleControl
						label={__("Navigation Arrow", "testimonial-free")}
						attributes={enableNavigationArrow}
						attributesKey={"enableNavigationArrow"}
						setAttributes={setAttributes}
					/>
					<ToggleControl
						label={__("Pagination Dots", "testimonial-free")}
						attributes={enablePaginationDots}
						attributesKey={"enablePaginationDots"}
						setAttributes={setAttributes}
					/>
				</>
			)}
			{inArray(["grid", "bento-grid", "polaroid-grid", "masonry"], blockName) && (
				<ToggleControl
					label={__("Ajax Pagination", "testimonial-free")}
					attributes={enableAjaxPagination}
					attributesKey={"enableAjaxPagination"}
					setAttributes={setAttributes}
				/>
			)}
			{/* child blocks — Pro-only features (locked in the free plugin). */}
			<ToggleControl label={__("AI Review Summary", "testimonial-free")} isPro />
			{!isSlider && (
				<>
					<ToggleControl label={__("Live Frontend Filter", "testimonial-free")} isPro />
					<ToggleControl label={__("Ajax Testimonial Search", "testimonial-free")} isPro />
				</>
			)}
			{"carousel" === blockName && getModulesSettings("seo_schema_markup") && (
				<ToggleControl
					label={__("SEO Schema Markup", "testimonial-free")}
					attributes={enableSEOSchemaMarkup}
					attributesKey={"enableSEOSchemaMarkup"}
					setAttributes={setAttributes}
				/>
			)}
			<ProFeaturesBox
				description={__(
					"Create stunning testimonial layouts that build trust and engagement",
					"testimonial-free"
				)}
				features={proFeatureList}
			/>
		</>
	);
};

export const SliderSettingsGeneralTab = ({ attributes, setAttributes }) => {
	const {
		blockName,
		sliderAutoPlay,
		carouselAutoplayDelay,
		carouselSpeed,
		slideToScroll,
		carouselDirection,
		pauseOnHover,
		infiniteLoop,
		tabAndKeyNavigation,
		mouseWheelControl,
		freeScrollMode,
		reverseMultiRowDirection,
		marqueeDisplayStyle,
	} = attributes;

	if (blockName === "marquee") {
		return (
			<>
				<SPRangeControl
					label={__("Sliding Speed", "testimonial-free")}
					attributes={carouselSpeed}
					attributesKey={"carouselSpeed"}
					setAttributes={setAttributes}
					min={3000}
					max={120000}
					step={500}
					defaultValue={{ unit: "ms", value: 25000 }}
					units={["ms"]}
				/>
				<ButtonGroup
					label={__("Sliding Direction", "testimonial-free")}
					items={[
						{ label: marqueeDisplayStyle === "vertical" ? "Bottom to Top" : "Right to Left", value: "rtl" },
						{ label: marqueeDisplayStyle === "vertical" ? "Top to Bottom" : "Left to Right", value: "ltr" },
					]}
					attributes={carouselDirection}
					attributesKey={"carouselDirection"}
					setAttributes={setAttributes}
				/>
				<ToggleControl
					label={
						marqueeDisplayStyle === "horizontal"
							? __("Reverse Multi-Row Direction", "testimonial-free")
							: __("Reverse Column-Row Direction", "testimonial-free")
					}
					attributes={reverseMultiRowDirection}
					attributesKey={"reverseMultiRowDirection"}
					setAttributes={setAttributes}
				/>
				<ToggleControl
					label={__("Pause on Hover", "testimonial-free")}
					attributes={pauseOnHover}
					attributesKey={"pauseOnHover"}
					setAttributes={setAttributes}
				/>
			</>
		);
	}

	return (
		<>
			<ToggleControl
				label={__("AutoPlay", "testimonial-free")}
				attributes={sliderAutoPlay}
				attributesKey={"sliderAutoPlay"}
				setAttributes={setAttributes}
			/>
			{sliderAutoPlay && (
				<SPRangeControl
					label={__("Autoplay Delay", "testimonial-free")}
					attributes={carouselAutoplayDelay}
					attributesKey={"carouselAutoplayDelay"}
					setAttributes={setAttributes}
					min={100}
					max={10000}
					step={100}
					defaultValue={{ unit: "ms", value: 900 }}
					units={["ms"]}
				/>
			)}
			<SPRangeControl
				label={__("Carousel Speed", "testimonial-free")}
				attributes={carouselSpeed}
				attributesKey={"carouselSpeed"}
				setAttributes={setAttributes}
				min={100}
				max={10000}
				step={50}
				defaultValue={{ unit: "ms", value: 900 }}
				units={["ms"]}
			/>
			<InputControl
				label={__("Slide to Scroll", "testimonial-free")}
				attributes={slideToScroll}
				attributesKey={"slideToScroll"}
				setAttributes={setAttributes}
				min={1}
				max={10}
				defaultValue={{ unit: "", value: 1 }}
				flex={true}
				inputType={"number"}
			/>
			{sliderAutoPlay && (
				<>
					<ButtonGroup
						label={__("Carousel Direction", "testimonial-free")}
						items={[
							{ label: "Right to Left", value: "rtl" },
							{ label: "Left to Right", value: "ltr" },
						]}
						attributes={carouselDirection}
						attributesKey={"carouselDirection"}
						setAttributes={setAttributes}
					/>
					<ToggleControl
						label={__("Pause on Hover", "testimonial-free")}
						attributes={pauseOnHover}
						attributesKey={"pauseOnHover"}
						setAttributes={setAttributes}
					/>
				</>
			)}
			<ToggleControl
				label={__("Infinite Loop", "testimonial-free")}
				attributes={infiniteLoop}
				attributesKey={"infiniteLoop"}
				setAttributes={setAttributes}
			/>
			<ToggleControl label={__("Adaptive Height", "testimonial-free")} isPro={true} />
			<ToggleControl
				label={__("Tab and Key Navigation", "testimonial-free")}
				attributes={tabAndKeyNavigation}
				attributesKey={"tabAndKeyNavigation"}
				setAttributes={setAttributes}
			/>
			<ToggleControl
				label={__("MouseWheel Control", "testimonial-free")}
				attributes={mouseWheelControl}
				attributesKey={"mouseWheelControl"}
				setAttributes={setAttributes}
			/>
			<ToggleControl
				label={__("Free Scroll Mode", "testimonial-free")}
				attributes={freeScrollMode}
				attributesKey={"freeScrollMode"}
				setAttributes={setAttributes}
			/>
		</>
	);
};

// Query Builder Tabs
// Free plugin exposes only "Latest" ordering. Groups / Specific / Star-Rating
// filtering, random order and drag-and-drop ordering are Pro-only (shown as
// locked options that link to the upgrade page).
export const QueryBuilderGeneralTab = ({ attributes, setAttributes }) => {
	const { limit, orderBy, order } = attributes;

	return (
		<>
			<SelectControl
				label={__("Filter Testimonials", "testimonial-free")}
				items={[
					{ label: __("Latest", "testimonial-free"), value: "latest" },
					{ label: __("Groups (Pro)", "testimonial-free"), value: "groups", disabled: true },
					{ label: __("Specific (Pro)", "testimonial-free"), value: "specific", disabled: true },
					{
						label: __("Based on Star Rating (Pro)", "testimonial-free"),
						value: "star-rating",
						disabled: true,
					},
				]}
				attributes={"latest"}
				attributesKey={"filterBy"}
				setAttributes={setAttributes}
			/>
			<InputControl
				label={__("Display Limit", "testimonial-free")}
				attributes={limit}
				attributesKey={"limit"}
				setAttributes={setAttributes}
				flex={true}
				inputType={"number"}
			/>
			<ToggleControl label={__("Random Order", "testimonial-free")} isPro />
			<SelectControl
				label={__("Order By", "testimonial-free")}
				items={[
					{ label: __("Date", "testimonial-free"), value: "date" },
					{ label: __("Title", "testimonial-free"), value: "title" },
					{ label: __("Modified", "testimonial-free"), value: "modified" },
					{ label: __("Testimonial ID", "testimonial-free"), value: "ID" },
					{ label: __("Drag & Drop (Pro)", "testimonial-free"), value: "menu_order", disabled: true },
				]}
				attributes={orderBy}
				attributesKey={"orderBy"}
				setAttributes={setAttributes}
			/>
			<ButtonGroup
				label={__("Order Direction", "testimonial-free")}
				items={[
					{ label: __("Ascending", "testimonial-free"), value: "ASC" },
					{ label: __("Descending", "testimonial-free"), value: "DESC" },
				]}
				attributes={order}
				attributesKey={"order"}
				setAttributes={setAttributes}
			/>
			<ProFeaturesBox
				title={__("Pro Features", "testimonial-free")}
				description={__("Show the right testimonials with smart filtering and sorting", "testimonial-free")}
				features={[
					__("Filter by groups, specific items", "testimonial-free"),
					__("Filter based on star rating", "testimonial-free"),
					__("Shuffle testimonials with random order", "testimonial-free"),
					__("Drag & drop to arrange testimonials your way", "testimonial-free"),
				]}
			/>
		</>
	);
};

// Card Design Tabs
export const CardDesignGeneralTab = ({ attributes, setAttributes }) => {
	const { cardContents, cardAlignment, cardHoverEffect, cardHoverTransition } = attributes;

	// drag and drop functions.
	const sensors = useSensors(
		useSensor(PointerSensor, {
			activationConstraint: { distance: 5 },
		})
	);

	const handleDragEnd = (event) => {
		const { active, over } = event;
		if (active && over && active.id !== over.id) {
			const oldIndex = cardContents.findIndex((i) => active.id === i.id);
			const newIndex = cardContents.findIndex((i) => over.id === i.id);

			setAttributes({
				cardContents: arrayMove(cardContents, oldIndex, newIndex),
			});
		}
	};

	return (
		<>
			{/* card contents */}
			<div className="sp-real-card-content-dnd sp-real-component-mb">
				<div className="sp-real-card-content-dnd-contents">
					<DndContext sensors={sensors} onDragEnd={handleDragEnd} modifiers={[restrictToVerticalAxis]}>
						<SortableContext items={cardContents} strategy={verticalListSortingStrategy}>
							{cardContents?.map(({ id, name, is_active }, index) => (
								<SortableItem key={id} id={id}>
									<ToggleControl
										key={index}
										updated={true}
										label={cardContentLabels[name]}
										attributes={is_active}
										onChange={(newVal) => {
											const updated = cardContents?.map((i) => {
												if (i.id === id) {
													return { ...i, is_active: newVal };
												}
												return i;
											});
											setAttributes({ cardContents: updated });
										}}
									/>
								</SortableItem>
							))}
						</SortableContext>
					</DndContext>
					{PRO_CARD_CONTENTS?.map((name) => (
						<ToggleControl key={name} label={cardContentLabels[name]} updated={true} isPro />
					))}
				</div>
			</div>
			<ButtonGroup
				label={__("Alignment", "testimonial-free")}
				items={textAlignmentOptions}
				attributes={cardAlignment}
				attributesKey={"cardAlignment"}
				setAttributes={setAttributes}
			/>
			<SelectControl
				label={__("Hover Effect", "testimonial-free")}
				items={[
					{ label: "None", value: "none" },
					{ label: "Scale Up", value: "scale-up" },
					{ label: "Slide Up", value: "slide-up" },
					{ label: "Slide Down", value: "slide-down" },
					{ label: "Slide Left", value: "slide-left" },
					{ label: "Slide Right", value: "slide-right" },
					{ label: "Scale Down", value: "scale-down" },
				]}
				attributes={cardHoverEffect}
				attributesKey={"cardHoverEffect"}
				setAttributes={setAttributes}
			/>
			{cardHoverEffect !== "none" && (
				<SPRangeControl
					label={__("Transition Duration", "testimonial-free")}
					attributes={cardHoverTransition}
					attributesKey={"cardHoverTransition"}
					setAttributes={setAttributes}
					max={2000}
					step={50}
					defaultValue={{ unit: "ms", value: 300 }}
				/>
			)}
			<ProFeaturesBox
				title={__("Pro Features", "testimonial-free")}
				description={__("Show the right testimonials with smart filtering and sorting", "testimonial-free")}
				features={[
					__("Add company info, location, and date", "testimonial-free"),
					__("Social profiles for better credibility", "testimonial-free"),
					__("Custom fields for extra information", "testimonial-free"),
					__("Apply 6+ hover effects for interactive cards", "testimonial-free"),
				]}
			/>
		</>
	);
};

// Testimonial Content Tabs
export const TestimonialContentGeneralTab = ({ attributes, setAttributes }) => {
	const { titleTag, titleLength, excerptLength, stripAllHTMLTags } = attributes;

	return (
		<>
			{componentSectionHeader(__("Testimonial Title", "testimonial-free"))}
			<ButtonGroup
				label={__("HTML Tag", "testimonial-free")}
				items={[
					{ label: "H1", value: "h1" },
					{ label: "H2", value: "h2" },
					{ label: "H3", value: "h3" },
					{ label: "H4", value: "h4" },
					{ label: "H5", value: "h5" },
					{ label: "H6", value: "h6" },
					{ label: "Span", value: "span" },
				]}
				attributes={titleTag}
				attributesKey={"titleTag"}
				setAttributes={setAttributes}
			/>
			<ButtonGroup
				label={__("Title Length", "testimonial-free")}
				items={[
					{ label: "Full", value: "full" },
					{ label: "Limited", value: "limited", isPro: true },
				]}
				attributes={titleLength}
				attributesKey={"titleLength"}
				setAttributes={setAttributes}
			/>
			<ToggleControl label={__("Add Quote Symbol", "testimonial-free")} isPro />
			<Divider />
			{componentSectionHeader(__("Testimonial Text", "testimonial-free"))}
			<ButtonGroup
				label={__("Text Length", "testimonial-free")}
				items={[
					{ label: "Full", value: "full" },
					{ label: "Limited", value: "limited", isPro: true },
				]}
				attributes={excerptLength}
				attributesKey={"excerptLength"}
				setAttributes={setAttributes}
			/>
			<ToggleControl label={__("Add Quote Symbol", "testimonial-free")} isPro />
			<ToggleControl
				label={__("Strip All HTML Tags", "testimonial-free")}
				attributes={stripAllHTMLTags}
				attributesKey={"stripAllHTMLTags"}
				setAttributes={setAttributes}
			/>
			<TestimonialContentProNote />
		</>
	);
};

// Star Rating Tabs
export const StarRatingGeneralTab = ({ attributes, setAttributes }) => {
	const { ratingIconSet, ratingIconSize, ratingIconGap } = attributes;

	return (
		<>
			<div className="sp-real-rating-style-label">{__("Select Rating Icon Style", "testimonial-free")}</div>
			<div className="sp-real-rating-style-picker">
				{RATING_ICON_STYLES.map((style) => {
					const isSelected =
						ratingIconSet?.active === style.active && ratingIconSet?.inactive === style.inactive;
					const isPro = style?.isPro ?? false;

					return (
						<button
							key={style.id}
							type="button"
							className={`sp-real-rating-style sp-d-flex sp-align-center sp-justify-center sp-gap-4px sp-cursor-pointer${isSelected ? " is-selected" : ""}${isPro ? " sp-d-disabled" : ""}`}
							aria-pressed={isSelected}
							onClick={() => {
								if (!isPro) {
									setAttributes({
										ratingIconSet: { active: style.active, inactive: style.inactive },
									});
								}
							}}
						>
							<span className="sp-real-rating-style-active">
								<RatingIcon name={style.active} />
							</span>
							<span className="sp-real-rating-style-inactive">
								<RatingIcon name={style.inactive} />
							</span>
						</button>
					);
				})}
			</div>
			<SPRangeControl
				label={__("Icon Size", "testimonial-free")}
				attributes={ratingIconSize}
				attributesKey={"ratingIconSize"}
				setAttributes={setAttributes}
				min={10}
				max={50}
				defaultValue={{ unit: "px", value: 16 }}
			/>
			<SPRangeControl
				label={__("Gap Between Icons", "testimonial-free")}
				attributes={ratingIconGap}
				attributesKey={"ratingIconGap"}
				setAttributes={setAttributes}
				max={20}
				defaultValue={{ unit: "px", value: 4 }}
			/>
		</>
	);
};

// Reviewer Image Tabs
export const ReviewerImageGeneralTab = ({ attributes, setAttributes }) => {
	const {
		imageResolution,
		aspectRatio,
		aspectRatioCustom,
		imageWidth,
		imageHeight,
		load2xInRetinaDisplay,
		reviewerFallbackImages,
	} = attributes;

	const isOriginalRatio = aspectRatio === "original";

	return (
		<>
			<SelectControl
				label={__("Image Resolution", "testimonial-free")}
				items={[
					{ label: __("Original", "testimonial-free"), value: "original" },
					{ label: __("Thumbnail", "testimonial-free"), value: "thumbnail" },
					{ label: __("Medium", "testimonial-free"), value: "medium" },
					{ label: __("Large", "testimonial-free"), value: "large" },
				]}
				attributes={imageResolution}
				attributesKey={"imageResolution"}
				setAttributes={setAttributes}
			/>
			<SelectControl
				label={__("Aspect Ratio", "testimonial-free")}
				items={[
					{ label: "1:1", value: "1:1" },
					{ label: "16:9", value: "16:9" },
					{ label: "4:3", value: "4:3" },
					{ label: "3:2", value: "3:2" },
					{ label: "21:9", value: "21:9" },
					{ label: __("Original", "testimonial-free"), value: "original" },
					{ label: __("Custom", "testimonial-free"), value: "custom" },
				]}
				attributes={aspectRatio}
				attributesKey={"aspectRatio"}
				setAttributes={setAttributes}
			/>
			{aspectRatio === "custom" && (
				<InputControl
					label={__("Custom Aspect Ratio", "testimonial-free")}
					placeholder="e.g. 5:4"
					attributes={aspectRatioCustom}
					attributesKey={"aspectRatioCustom"}
					setAttributes={setAttributes}
				/>
			)}
			<SPRangeControl
				label={__("Image Width", "testimonial-free")}
				attributes={imageWidth}
				attributesKey={"imageWidth"}
				setAttributes={setAttributes}
				min={30}
				max={500}
				defaultValue={{ unit: "px", value: 70 }}
			/>
			{isOriginalRatio && (
				<SPRangeControl
					label={__("Image Height", "testimonial-free")}
					attributes={imageHeight}
					attributesKey={"imageHeight"}
					setAttributes={setAttributes}
					min={30}
					max={500}
					defaultValue={{ unit: "px", value: 70 }}
				/>
			)}
			<SelectControl
				label={__("Reviewer Fallback Images", "testimonial-free")}
				items={[
					{ label: __("No Fallback Image", "testimonial-free"), value: "none" },
					{ label: __("Mystery Person (Pro)", "testimonial-free"), value: "mystery_person", disabled: true },
					{
						label: __("Smart Text Avatars (Pro)", "testimonial-free"),
						value: "smart_text_avatar",
						disabled: true,
					},
					{ label: __("Gravatar (Pro)", "testimonial-free"), value: "gravatar", disabled: true },
					{ label: __("Custom (Pro)", "testimonial-free"), value: "custom", disabled: true },
				]}
				attributes={reviewerFallbackImages}
				attributesKey={"reviewerFallbackImages"}
				setAttributes={setAttributes}
			/>
			<ToggleControl
				label={__("Load 2x in Retina Display", "testimonial-free")}
				attributes={load2xInRetinaDisplay}
				attributesKey={"load2xInRetinaDisplay"}
				setAttributes={setAttributes}
			/>
			<ToggleControl label={__("Show Video Testimonial", "testimonial-free")} isPro />
		</>
	);
};

// Reviewer Details Tabs
export const ReviewerDetailsGeneralTab = ({ attributes, setAttributes }) => {
	const { nameHtmlTag } = attributes;
	return (
		<>
			<ButtonGroup
				label={__("Reviewer Name HTML Tag", "testimonial-free")}
				items={[
					{ label: "H1", value: "h1" },
					{ label: "H2", value: "h2" },
					{ label: "H3", value: "h3" },
					{ label: "H4", value: "h4" },
					{ label: "H5", value: "h5" },
					{ label: "H6", value: "h6" },
					{ label: "Span", value: "span" },
				]}
				attributes={nameHtmlTag}
				attributesKey={"nameHtmlTag"}
				setAttributes={setAttributes}
			/>
			<ToggleControl label={__("Verified Badge", "testimonial-free")} isPro />
			<ToggleControl label={__("Link Designation to Custom URL", "testimonial-free")} isPro />
			<ReviewerDetailsProNote />
		</>
	);
};

// Motion Effects Tabs
export const MotionEffectsGeneralTab = () => {
	return (
		<>
			<ProFeaturesBox
				title={__("Pro Features", "testimonial-free")}
				description={__(
					"Unlock Pro-level motion effects for your testimonial section or page",
					"testimonial-free"
				)}
				features={[
					__("Modern Scrolling Effect", "testimonial-free"),
					__("Directional scrolling effect control", "testimonial-free"),
					__("25+ Entrance Animations", "testimonial-free"),
					__("Animation speed and delay control", "testimonial-free"),
					__("Exclude effects on responsive devices", "testimonial-free"),
				]}
			/>
		</>
	);
};

export const NavigationArrowGeneralTab = ({ attributes, setAttributes }) => {
	const { navIconVisibleOnHover, navIconSize, navIconGap, navIconPosition, navIconName, navOffsetX, navOffsetY } =
		attributes;
	const deviceType = useDeviceType();
	const onChangeNavPosition = (newVal) => {
		const x = "vertical_center" === newVal ? { value: -22, unit: "px" } : { value: 0, unit: "px" };
		const y = "vertical_center" === newVal ? { value: 50, unit: "%" } : { value: -12, unit: "%" };
		const updatedAttr = {
			navIconPosition: newVal,
			navOffsetX: {
				...navOffsetX,
				device: {
					...navOffsetX.device,
					[deviceType]: x?.value,
				},
				unit: {
					...navOffsetX.unit,
					[deviceType]: x?.unit,
				},
			},
			navOffsetY: {
				...navOffsetY,
				device: {
					...navOffsetY.device,
					[deviceType]: y?.value,
				},
				unit: {
					...navOffsetY.unit,
					[deviceType]: y?.unit,
				},
			},
		};
		setAttributes(updatedAttr);
	};

	return (
		<>
			<div className="sp-real-component-mb">
				<span className="sp-real-component-title sp-mb-8px">Arrow Style</span>
				<div className="sp-real-nav-icon-picker sp-w-full sp-d-grid sp-grid-cols-6 sp-gap-8px sp-align-center">
					{ARROW_ICON_OPTIONS?.map(({ icon, value, isPro }, index) => (
						<span
							key={index}
							className={`sp-real-nav-icon-picker__icon sp-d-flex sp-align-center sp-justify-center${navIconName === value ? " active" : ""}${isPro ? " sp-d-disabled" : ""}`}
							onClick={() => {
								if (!isPro) {
									setAttributes({ navIconName: value });
								}
							}}
						>
							{icon}
						</span>
					))}
				</div>
			</div>
			<SPRangeControl
				label={__("Size", "testimonial-free")}
				attributes={navIconSize}
				attributesKey={"navIconSize"}
				setAttributes={setAttributes}
				units={["Px", "%", "Em"]}
				defaultValue={{ unit: "px", value: 16 }}
				max={100}
			/>
			{navIconPosition !== "vertical_center" && (
				<SPRangeControl
					label={__("Gap", "testimonial-free")}
					attributes={navIconGap}
					attributesKey={"navIconGap"}
					setAttributes={setAttributes}
					units={["Px"]}
					defaultValue={{ unit: "px", value: 10 }}
					max={100}
				/>
			)}
			<ToggleControl
				label={__("Show on Hover", "testimonial-free")}
				attributes={navIconVisibleOnHover}
				attributesKey={"navIconVisibleOnHover"}
				setAttributes={setAttributes}
			/>
			<Divider />
			<SelectControl
				label={__("Arrow Position", "testimonial-free")}
				items={[
					{ label: "Vertical Center", value: "vertical_center" },
					{ label: "Top Left", value: "top_left" },
					{ label: "Top Center", value: "top_center" },
					{ label: "Top Right", value: "top_right" },
					{ label: "Bottom Left", value: "bottom_left" },
					{ label: "Bottom center", value: "bottom_center" },
					{ label: "Bottom Right", value: "bottom_right" },
				]}
				attributes={navIconPosition}
				onChange={onChangeNavPosition}
			/>
			<SPRangeControl
				label={__("Offset X", "testimonial-free")}
				attributes={navOffsetX}
				attributesKey={"navOffsetX"}
				setAttributes={setAttributes}
				units={["px", "%"]}
				defaultValue={{ unit: "px", value: -22 }}
				min={-200}
				max={200}
			/>
			<SPRangeControl
				label={__("Offset Y", "testimonial-free")}
				attributes={navOffsetY}
				attributesKey={"navOffsetY"}
				setAttributes={setAttributes}
				units={["px", "%"]}
				defaultValue={{ unit: "%", value: 50 }}
				min={-200}
				max={200}
			/>
		</>
	);
};

export const PaginationDotsGeneralTab = ({ attributes, setAttributes }) => {
	const { paginationStyle, paginationDotsWidth, paginationDotsHeight, paginationDotsSpaceBetween } = attributes;
	const device = useDeviceType();

	const paginationSize = {
		stepper: {
			width: 30,
			height: 8,
		},
	};

	const paginationDotChangeHandler = (newValue) => {
		const height = paginationSize[newValue]?.height || 12;
		const width = paginationSize[newValue]?.width || 12;

		setAttributes({
			paginationStyle: newValue,
			paginationDotsWidth: {
				...paginationDotsWidth,
				device: {
					...paginationDotsWidth.device,
					[device]: width,
				},
			},
			paginationDotsHeight: {
				...paginationDotsHeight,
				device: {
					...paginationDotsHeight.device,
					[device]: height,
				},
			},
		});
	};

	return (
		<>
			<SelectDropdown
				label={__("Pagination Style", "testimonial-free")}
				attributes={paginationStyle}
				options={SWIPER_PAGINATION_DOTS_OPTIONS}
				onClick={paginationDotChangeHandler}
			/>
			<SPRangeControl
				label={__("Width", "testimonial-free")}
				attributes={paginationDotsWidth}
				attributesKey={"paginationDotsWidth"}
				setAttributes={setAttributes}
				units={["Px", "%", "Em"]}
				defaultValue={{ unit: "px", value: paginationSize[paginationStyle]?.width || 12 }}
				max={200}
			/>
			<SPRangeControl
				label={__("Height", "testimonial-free")}
				attributes={paginationDotsHeight}
				attributesKey={"paginationDotsHeight"}
				setAttributes={setAttributes}
				units={["Px", "%", "Em"]}
				defaultValue={{ unit: "px", value: paginationSize[paginationStyle]?.height || 12 }}
				max={200}
			/>
			<SPRangeControl
				label={__("Space Between Dots", "testimonial-free")}
				attributes={paginationDotsSpaceBetween}
				attributesKey={"paginationDotsSpaceBetween"}
				setAttributes={setAttributes}
				units={["Px", "%", "Em"]}
				defaultValue={{ unit: "px", value: 8 }}
				max={100}
			/>
		</>
	);
};
