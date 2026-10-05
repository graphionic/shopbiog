import { __ } from "@wordpress/i18n";
import { Spacing, ButtonGroup, ColorPicker } from "@testimonial/components";
import { borderStyles } from "@testimonial/constants";
import "./editor.scss";

const FlatBorder = ({
	label = __("Border Style", "testimonial-free"),
	attributes,
	attributesKey,
	setAttributes,
	defaultValue = {
		unit: "px",
		value: {
			top: "0",
			right: "0",
			bottom: "0",
			left: "0",
		},
	},
	activeState = "normal",
}) => {
	const { border, borderWidth } = attributes;

	const borderColorKeyMaps = {
		normal: "color",
		hover: "hoverColor",
		active: "activeColor",
	};
	const borderColorKey = borderColorKeyMaps[activeState];

	const borderColor = (newColor) => {
		setAttributes({
			[attributesKey.border]: {
				...attributes?.border,
				[borderColorKey]: newColor,
			},
		});
	};

	return (
		<div className="sp-real-border-component">
			{activeState === "normal" && (
				<ButtonGroup
					label={label}
					attributes={border?.style}
					items={borderStyles}
					onClick={(newStyle) => {
						setAttributes({
							[attributesKey.border]: {
								...attributes.border,
								style: newStyle,
							},
						});
					}}
				/>
			)}
			{border?.style !== "none" && (
				<>
					{activeState === "normal" && (
						<Spacing
							label={__("Border Width", "testimonial-free")}
							attributes={borderWidth}
							attributesKey={attributesKey.borderWidth}
							setAttributes={setAttributes}
							defaultValue={defaultValue}
							rangeStep={0.5}
							rangeMax={2}
						/>
					)}
					{/* Border Color */}
					<ColorPicker
						label={__("Border Color", "testimonial-free")}
						value={border[borderColorKey]}
						onChange={borderColor}
					/>
				</>
			)}
		</div>
	);
};

export default FlatBorder;
