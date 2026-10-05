import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import {
	BackgroundControl,
	BoxShadow,
	ColorPicker,
	Divider,
	FlatBorder,
	Spacing,
	ButtonGroup,
	TypographyNew,
} from "@testimonial/components";
import { checkIsActiveCardItem, componentSectionHeader } from "@testimonial/controls";

// Card Design Style Tabs
export const CardDesignStyleTab = ({ attributes, setAttributes }) => {
	const [colorState, setColorState] = useState("normal");

	const {
		cardBackground,
		cardBorder,
		cardBorderWidth,
		cardBorderRadius,
		cardPadding,
		cardBoxShadow,
		cardBoxShadowHover,
	} = attributes;

	return (
		<>
			<ButtonGroup
				attributes={colorState}
				items={[
					{ label: "Normal", value: "normal" },
					{ label: "Hover", value: "hover" },
				]}
				onClick={(e) => setColorState(e)}
			/>
			<BackgroundControl
				label={__("Background Type", "testimonial-free")}
				attributes={cardBackground}
				attributesKey={"cardBackground"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<FlatBorder
				attributes={{
					border: cardBorder,
					borderWidth: cardBorderWidth,
				}}
				attributesKey={{
					border: "cardBorder",
					borderWidth: "cardBorderWidth",
				}}
				defaultValue={{
					unit: "px",
					value: {
						top: "0",
						right: "0",
						bottom: "0",
						left: "0",
					},
				}}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<BoxShadow
				label={__("Box Shadow", "testimonial-free")}
				attributes={colorState === "normal" ? cardBoxShadow : cardBoxShadowHover}
				attributesKey={colorState === "normal" ? "cardBoxShadow" : "cardBoxShadowHover"}
				setAttributes={setAttributes}
			/>
			<Divider />
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={cardBorderRadius}
				attributesKey={"cardBorderRadius"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 8, right: 8, bottom: 8, left: 8 },
				}}
				indicator="radius"
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={cardPadding}
				attributesKey={"cardPadding"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 20, right: 20, bottom: 20, left: 20 },
				}}
			/>
		</>
	);
};

// Testimonial Content Style Tabs
export const TestimonialContentStyleTab = ({ attributes, setAttributes }) => {
	const {
		titleTypography,
		titleColors,
		titleFontSize,
		titleLineHeight,
		titleLetterSpacing,
		excerptTypography,
		excerptColors,
		excerptFontSize,
		excerptLineHeight,
		excerptLetterSpacing,
		titleMargin,
		excerptMargin,
	} = attributes;

	return (
		<>
			{componentSectionHeader(__("Testimonial Title", "testimonial-free"))}
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: titleTypography,
					typographyKey: "titleTypography",
					fontSize: titleFontSize,
					fontSizeKey: "titleFontSize",
					letterSpacing: titleLetterSpacing,
					letterSpacingKey: "titleLetterSpacing",
					lineHeight: titleLineHeight,
					lineHeightKey: "titleLineHeight",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{ unit: "px", value: 20 }}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={titleColors}
				attributesKey={"titleColors"}
				setAttributes={setAttributes}
				activeState={"normal"}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={titleMargin}
				attributesKey={"titleMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 0, right: 0, bottom: 12, left: 0 },
				}}
			/>
			<Divider />
			{componentSectionHeader(__("Testimonial Text", "testimonial-free"))}
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: excerptTypography,
					typographyKey: "excerptTypography",
					fontSize: excerptFontSize,
					fontSizeKey: "excerptFontSize",
					letterSpacing: excerptLetterSpacing,
					letterSpacingKey: "excerptLetterSpacing",
					lineHeight: excerptLineHeight,
					lineHeightKey: "excerptLineHeight",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{ unit: "px", value: 16 }}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={excerptColors}
				attributesKey={"excerptColors"}
				setAttributes={setAttributes}
				activeState={"normal"}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={excerptMargin}
				attributesKey={"excerptMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 0, right: 0, bottom: 12, left: 0 },
				}}
			/>
		</>
	);
};

// Star Rating Style Tabs
export const StarRatingStyleTab = ({ attributes, setAttributes }) => {
	const { ratingIconColor, ratingIconEmptyColor, ratingIconMargin } = attributes;

	return (
		<>
			<ColorPicker
				label={__("Fill Star Color", "testimonial-free")}
				value={ratingIconColor}
				attributesKey={"ratingIconColor"}
				setAttributes={setAttributes}
			/>
			<ColorPicker
				label={__("Empty Star Color", "testimonial-free")}
				value={ratingIconEmptyColor}
				attributesKey={"ratingIconEmptyColor"}
				setAttributes={setAttributes}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={ratingIconMargin}
				attributesKey={"ratingIconMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 0, right: 0, bottom: 12, left: 0 },
				}}
			/>
		</>
	);
};

// Reviewer Image Style Tabs
export const ReviewerImageStyleTab = ({ attributes, setAttributes }) => {
	const { imageBorderRadius, imageBorder, imageBorderWidth, imageBoxShadow, imageMargin } = attributes;

	return (
		<>
			<FlatBorder
				attributes={{
					border: imageBorder,
					borderWidth: imageBorderWidth,
				}}
				attributesKey={{
					border: "imageBorder",
					borderWidth: "imageBorderWidth",
				}}
				defaultValue={{
					unit: "px",
					value: {
						top: "0",
						right: "0",
						bottom: "0",
						left: "0",
					},
				}}
				setAttributes={setAttributes}
			/>
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={imageBorderRadius}
				attributesKey={"imageBorderRadius"}
				setAttributes={setAttributes}
				units={["px", "%"]}
				defaultValue={{
					unit: "px",
					value: { top: 8, right: 8, bottom: 8, left: 8 },
				}}
				indicator="radius"
			/>
			<BoxShadow
				label={__("Box Shadow", "testimonial-free")}
				attributes={imageBoxShadow}
				attributesKey={"imageBoxShadow"}
				setAttributes={setAttributes}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={imageMargin}
				attributesKey={"imageMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 0, right: 16, bottom: 0, left: 0 },
				}}
			/>
		</>
	);
};

// Reviewer Details Style Tabs
export const ReviewerDetailsStyleTab = ({ attributes, setAttributes }) => {
	const {
		cardContents,
		nameTypography,
		nameColors,
		nameFontSize,
		nameLineHeight,
		nameLetterSpacing,
		nameMargin,
		designationTypography,
		designationColors,
		designationFontSize,
		designationLineHeight,
		designationLetterSpacing,
		designationMargin,
	} = attributes;

	return (
		<>
			{checkIsActiveCardItem(cardContents, "reviewer_name") && (
				<>
					{componentSectionHeader(__("Reviewer Name", "testimonial-free"))}
					<TypographyNew
						label={__("Typography", "testimonial-free")}
						attributes={{
							typography: nameTypography,
							typographyKey: "nameTypography",
							fontSize: nameFontSize,
							fontSizeKey: "nameFontSize",
							letterSpacing: nameLetterSpacing,
							letterSpacingKey: "nameLetterSpacing",
							lineHeight: nameLineHeight,
							lineHeightKey: "nameLineHeight",
						}}
						setAttributes={setAttributes}
						fontSizeDefault={{ unit: "px", value: 18 }}
					/>
					<ColorPicker
						label={__("Color", "testimonial-free")}
						value={nameColors}
						attributesKey={"nameColors"}
						setAttributes={setAttributes}
						activeState={"normal"}
					/>
					<Spacing
						label={__("Margin", "testimonial-free")}
						attributes={nameMargin}
						attributesKey={"nameMargin"}
						setAttributes={setAttributes}
						units={["px", "%", "em"]}
						defaultValue={{
							unit: "px",
							value: { top: 0, right: 0, bottom: 4, left: 0 },
						}}
					/>
					<Divider />
				</>
			)}
			{checkIsActiveCardItem(cardContents, "designation") && (
				<>
					{componentSectionHeader(__("Designation", "testimonial-free"))}
					<TypographyNew
						label={__("Typography", "testimonial-free")}
						attributes={{
							typography: designationTypography,
							typographyKey: "designationTypography",
							fontSize: designationFontSize,
							fontSizeKey: "designationFontSize",
							letterSpacing: designationLetterSpacing,
							letterSpacingKey: "designationLetterSpacing",
							lineHeight: designationLineHeight,
							lineHeightKey: "designationLineHeight",
						}}
						setAttributes={setAttributes}
						fontSizeDefault={{ unit: "px", value: 14 }}
					/>
					<ColorPicker
						label={__("Color", "testimonial-free")}
						value={designationColors}
						attributesKey={"designationColors"}
						setAttributes={setAttributes}
						activeState={"normal"}
					/>
					<Spacing
						label={__("Margin", "testimonial-free")}
						attributes={designationMargin}
						attributesKey={"designationMargin"}
						setAttributes={setAttributes}
						units={["px", "%", "em"]}
						defaultValue={{
							unit: "px",
							value: { top: 0, right: 0, bottom: 4, left: 0 },
						}}
					/>
				</>
			)}
		</>
	);
};

export const NavigationArrowStyleTab = ({ attributes, setAttributes }) => {
	const {
		navIconColors,
		navIconBackground,
		navIconBorderRadius,
		navIconPadding,
		navIconBorderWidth,
		navIconBorder,
		navIconBoxShadow,
		navIconBoxShadowHover,
	} = attributes;
	const [colorState, setColorState] = useState("normal");

	return (
		<>
			{/* title background */}
			<ButtonGroup
				attributes={colorState}
				items={[
					{ label: "Normal", value: "normal" },
					{ label: "Hover", value: "hover" },
				]}
				onClick={(e) => setColorState(e)}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={navIconColors}
				attributesKey={"navIconColors"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<ColorPicker
				label={__("Background Color", "testimonial-free")}
				value={navIconBackground}
				attributesKey={"navIconBackground"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<FlatBorder
				attributes={{
					border: navIconBorder,
					borderWidth: navIconBorderWidth,
				}}
				attributesKey={{
					border: "navIconBorder",
					borderWidth: "navIconBorderWidth",
				}}
				defaultValue={{
					unit: "px",
					value: {
						top: "1",
						right: "1",
						bottom: "1",
						left: "1",
					},
				}}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<BoxShadow
				label={__("Box Shadow", "testimonial-free")}
				attributes={colorState === "normal" ? navIconBoxShadow : navIconBoxShadowHover}
				attributesKey={colorState === "normal" ? "navIconBoxShadow" : "navIconBoxShadowHover"}
				setAttributes={setAttributes}
				defaultValue={{
					value: { top: 0, right: 1, bottom: 4, left: 0 },
					unit: "outset",
					color: "#0000001A",
				}}
			/>
			<Divider />
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={navIconBorderRadius}
				attributesKey={"navIconBorderRadius"}
				setAttributes={setAttributes}
				defaultValue={{
					unit: "px",
					value: {
						top: "4",
						right: "4",
						bottom: "4",
						left: "4",
					},
				}}
				indicator="radius"
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={navIconPadding}
				attributesKey={"navIconPadding"}
				setAttributes={setAttributes}
				defaultValue={{
					unit: "px",
					value: {
						top: "12",
						right: "12",
						bottom: "12",
						left: "12",
					},
				}}
			/>
		</>
	);
};
export const PaginationDotsStyleTab = ({ attributes, setAttributes }) => {
	const { paginationDotsColors, paginationDotsMargin } = attributes;
	const [colorState, setColorState] = useState("normal");

	return (
		<>
			<ButtonGroup
				attributes={colorState}
				items={[
					{ label: "Normal", value: "normal" },
					{ label: "Hover & Active", value: "active" },
				]}
				onClick={(e) => setColorState(e)}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={paginationDotsColors}
				attributesKey={"paginationDotsColors"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<Divider />
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={paginationDotsMargin}
				attributesKey={"paginationDotsMargin"}
				setAttributes={setAttributes}
				defaultValue={{
					unit: "px",
					value: {
						top: "24",
						right: "0",
						bottom: "0",
						left: "0",
					},
				}}
			/>
		</>
	);
};
