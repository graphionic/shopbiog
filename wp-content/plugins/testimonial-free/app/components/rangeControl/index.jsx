import { RangeControl } from "@wordpress/components";
import { memo, useState } from "@wordpress/element";
import { useDeviceType } from "@testimonial/controls";
import { PopOverToggleIcon } from "@testimonial/icons";
import { ComponentHeader } from "@testimonial/components";
import "./editor.scss";

const SPRangeControl = ({
	attributes,
	attributesKey,
	setAttributes,
	label,
	units = ["px", "%", "em"],
	min = 0,
	max = 200,
	step = 1,
	rangerStep = null,
	defaultValue = { unit: "px", value: 10 },
}) => {
	const deviceType = useDeviceType();
	const [showRangeStep, setShowRangeStep] = useState(rangerStep ? true : false);

	// Check if this is responsive control
	const isResponsive = attributes && typeof attributes?.device === "object";

	let value = 0;
	if (isResponsive && attributes?.device) {
		value = attributes?.device[deviceType];
	} else if (attributes?.value !== undefined) {
		value = attributes?.value;
	} else {
		value = attributes;
	}

	const setValue = (newValue) => {
		let newAttributes;
		if (isResponsive && attributes?.device) {
			newAttributes = {
				...attributes,
				device: { ...attributes.device, [deviceType]: newValue },
			};
		} else if (attributes?.value !== undefined) {
			newAttributes = {
				...attributes,
				value: newValue,
			};
		} else {
			newAttributes = newValue;
		}

		setAttributes({ [attributesKey]: newAttributes });
	};

	const setDefault = () => {
		let newAttributes;

		if (isResponsive && attributes?.device) {
			newAttributes = {
				...attributes,
				device: {
					...attributes.device,
					[deviceType]: defaultValue.value,
				},
			};
		} else if (attributes?.value !== undefined) {
			newAttributes = {
				...attributes,
				value: defaultValue.value,
			};
		} else {
			newAttributes = defaultValue;
		}

		setAttributes({ [attributesKey]: newAttributes });
	};

	const unit =
		isResponsive && attributes?.unit ? attributes?.unit?.[deviceType] || attributes?.unit : attributes?.unit;

	const getMaxValue = () => {
		if (showRangeStep) {
			return 32;
		}
		if (!max && attributes?.unit && unit === "%") {
			return 100;
		}
		return max;
	};

	return (
		<div className="sp-real-range-control sp-real-component-mb">
			<ComponentHeader
				label={label}
				attributes={attributes}
				attributesKey={attributesKey}
				setAttributes={setAttributes}
				units={units}
				onReset={setDefault}
			/>
			<div className="sp-real-range-wrapper sp-d-flex sp-align-center">
				<RangeControl
					value={value}
					onChange={(newValue) => setValue(newValue)}
					min={min}
					max={getMaxValue()}
					withInputField={!showRangeStep}
					marks={rangerStep && showRangeStep}
					step={rangerStep && showRangeStep ? rangerStep : step}
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					renderTooltipContent={(tooltipValue) => `${tooltipValue}${"string" === typeof unit ? unit : ""}`}
				/>
				{rangerStep && (
					<span
						className="sp-real-toggle-predefined-btn sp-cursor-pointer"
						onClick={() => setShowRangeStep((prev) => !prev)}
					>
						<PopOverToggleIcon />
					</span>
				)}
			</div>
		</div>
	);
};

export default memo(SPRangeControl);
