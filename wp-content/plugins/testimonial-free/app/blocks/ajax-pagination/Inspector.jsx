import { __ } from "@wordpress/i18n";
import { PanelBody } from "@wordpress/components";
import { useState } from "@wordpress/element";
import {
	TabControls,
	InputControl,
	SPRangeControl,
	ButtonGroup,
	TypographyNew,
	ColorPicker,
	Divider,
	Spacing,
	FlatBorder,
	SelectControl,
} from "@testimonial/components";
import { useTogglePanelBody } from "@testimonial/hooks";
import { AdvancedTab, VisibilityTab } from "../shared/AdvancedTab";
import { textAlignmentOptions } from "@testimonial/constants";

const AjaxPaginationGeneralTab = ({ attributes, setAttributes }) => {
	const {
		itemPerPage,
		paginationButtonType,
		loadMoreLabel,
		endingMessage,
		paginationNumberType,
		paginationNumberGap,
		paginationAlignment,
	} = attributes;

	return (
		<>
			<SPRangeControl
				label={__("Items to Show Per Click", "testimonial-free")}
				attributes={itemPerPage}
				attributesKey={"itemPerPage"}
				setAttributes={setAttributes}
				max={50}
				min={1}
				units={false}
				defaultValue={{ value: 4 }}
			/>
			<ButtonGroup
				label={__("Pagination Type", "testimonial-free")}
				items={[
					{ label: __("Button", "testimonial-free"), value: "load-more" },
					{ label: __("Number", "testimonial-free"), value: "number" },
				]}
				attributes={paginationButtonType}
				attributesKey={"paginationButtonType"}
				setAttributes={setAttributes}
			/>
			{paginationButtonType === "load-more" && (
				<>
					<InputControl
						label={__("Button Label", "testimonial-free")}
						attributes={loadMoreLabel}
						attributesKey={"loadMoreLabel"}
						setAttributes={setAttributes}
					/>
					<InputControl
						label={__("Ending Message", "testimonial-free")}
						attributes={endingMessage}
						attributesKey={"endingMessage"}
						setAttributes={setAttributes}
					/>
				</>
			)}
			{paginationButtonType === "number" && (
				<>
					<SelectControl
						label={__("Number Type", "testimonial-free")}
						items={[
							{ label: __("Number", "testimonial-free"), value: "number" },
							{ label: __("Number with Arrow", "testimonial-free"), value: "number-arrow" },
							{
								label: __("Number with Next & Previous", "testimonial-free"),
								value: "number-prev-next-arrow",
							},
							{ label: __("Previous & Next", "testimonial-free"), value: "prev-next" },
						]}
						attributes={paginationNumberType}
						attributesKey={"paginationNumberType"}
						setAttributes={setAttributes}
					/>
					<SPRangeControl
						label={__("Gap", "testimonial-free")}
						attributes={paginationNumberGap}
						attributesKey={"paginationNumberGap"}
						setAttributes={setAttributes}
						max={100}
						min={1}
						units={["px", "em"]}
						defaultValue={{ value: 4, unit: "px" }}
					/>
				</>
			)}
			<ButtonGroup
				label={__("Alignment", "testimonial-free")}
				attributes={paginationAlignment}
				attributesKey={"paginationAlignment"}
				setAttributes={setAttributes}
				items={textAlignmentOptions}
			/>
		</>
	);
};

const AjaxPaginationStyleTab = ({ attributes, setAttributes }) => {
	const {
		paginationButtonType,
		paginationTypography,
		paginationFontSize,
		paginationLineHeight,
		paginationLetterSpacing,
		paginationColor,
		paginationBgColor,
		paginationBorder,
		paginationBorderWidth,
		paginationBorderRadius,
		paginationPadding,
		paginationMargin,
	} = attributes;
	const [colorState, setColorState] = useState("normal");

	return (
		<>
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: paginationTypography,
					typographyKey: "paginationTypography",
					fontSize: paginationFontSize,
					fontSizeKey: "paginationFontSize",
					lineHeight: paginationLineHeight,
					lineHeightKey: "paginationLineHeight",
					letterSpacing: paginationLetterSpacing,
					letterSpacingKey: "paginationLetterSpacing",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{ unit: "px", value: 14 }}
			/>
			<ButtonGroup
				items={[
					{ label: __("Normal", "testimonial-free"), value: "normal" },
					{
						label:
							paginationButtonType === "load-more"
								? __("Hover", "testimonial-free")
								: __("Hover & Active", "testimonial-free"),
						value: "hover",
					},
				]}
				attributes={colorState}
				onClick={(value) => setColorState(value)}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={paginationColor}
				attributesKey={"paginationColor"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<ColorPicker
				label={__("Background Color", "testimonial-free")}
				value={paginationBgColor}
				attributesKey={"paginationBgColor"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<FlatBorder
				attributes={{
					border: paginationBorder,
					borderWidth: paginationBorderWidth,
				}}
				attributesKey={{
					border: "paginationBorder",
					borderWidth: "paginationBorderWidth",
				}}
				defaultValue={{
					unit: "px",
					value: { top: "1", right: "1", bottom: "1", left: "1" },
				}}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<Divider />
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={paginationBorderRadius}
				attributesKey={"paginationBorderRadius"}
				setAttributes={setAttributes}
				defaultValue={{
					unit: "px",
					value: { top: "4", right: "4", bottom: "4", left: "4" },
				}}
				indicator="radius"
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={paginationPadding}
				attributesKey={"paginationPadding"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: "10", right: "20", bottom: "10", left: "20" },
				}}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={paginationMargin}
				attributesKey={"paginationMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: "32", right: "0", bottom: "0", left: "0" },
				}}
			/>
		</>
	);
};

const Inspector = ({ attributes, setAttributes }) => {
	const { togglePanelBody, openedPanelBody, activeTab, toggleActiveTab } = useTogglePanelBody();

	return (
		<>
			<PanelBody
				title={__("Ajax Pagination", "testimonial-free")}
				opened={openedPanelBody === "defaultOpen"}
				onToggle={() => togglePanelBody("defaultOpen")}
				initialOpen={true}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					GeneralTab={AjaxPaginationGeneralTab}
					StyleTab={AjaxPaginationStyleTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>
			<PanelBody
				title={__("Advanced Settings", "testimonial-free")}
				opened={openedPanelBody === "advanced-settings"}
				onToggle={() => {
					togglePanelBody("advanced-settings");
					toggleActiveTab("visibility");
				}}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					VisibilityTab={VisibilityTab}
					AdvancedTab={AdvancedTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>
		</>
	);
};

export default Inspector;
