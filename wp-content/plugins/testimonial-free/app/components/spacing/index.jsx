import { RangeControl } from "@wordpress/components";
import { memo, useEffect, useState, useMemo } from "@wordpress/element";
import { useDeviceType } from "@testimonial/controls";
import {
	BottomSpaceIcon,
	GapHorizontalIcon,
	GapVerticalIcon,
	HorizontalAxisSpaceIcon,
	LeftSpaceIcon,
	LinkedIcon,
	RadiusAllIcon,
	RadiusBottomLeftIcon,
	RadiusBottomRightIcon,
	RadiusTopLeftIcon,
	RadiusTopRightIcon,
	RightSpaceIcon,
	TopSpaceIcon,
	VerticalAxisSpaceIcon,
} from "./Icons";
import { Responsive, ResetButton, Units } from "@testimonial/components";
import { PopOverToggleIcon } from "@testimonial/icons";
import "./editor.scss";

const sideAxisIcons = {
	horizontal: <HorizontalAxisSpaceIcon />,
	vertical: <VerticalAxisSpaceIcon />,
	top: <TopSpaceIcon />,
	right: <RightSpaceIcon />,
	bottom: <BottomSpaceIcon />,
	left: <LeftSpaceIcon />,
	all: <LinkedIcon />,
	"radius-top": <RadiusTopLeftIcon />,
	"radius-right": <RadiusTopRightIcon />,
	"radius-bottom": <RadiusBottomRightIcon />,
	"radius-left": <RadiusBottomLeftIcon />,
	"radius-all": <RadiusAllIcon />,
	"gap-horizontal": <GapHorizontalIcon />,
	"gap-vertical": <GapVerticalIcon />,
};

const SpCustomRanger = ({
	attr,
	sideKey = "all",
	onChangeHandler,
	rangeStep,
	min,
	max,
	step,
	rangeMax,
	indicator = "",
	selectUnit = "",
}) => {
	// `showInput` true → stepped range (rangeStep marks, capped at rangeMax);
	// false → free numeric input (fine `step`, full `max`). Toggled manually.
	const [showInput, setShowInput] = useState(true);
	const rangerStep = showInput ? rangeStep : step;
	const iconType = indicator ? `${indicator}-${sideKey}` : sideKey;

	// Nullish check so a real `0` is kept as a number, not coerced to empty.
	const rangeValue = useMemo(() => {
		const val = sideKey === "all" ? attr?.top : attr?.[sideKey];
		return val !== undefined && val !== null && val !== "" ? Number(val) : 0;
	}, [attr, sideKey]);

	return (
		<div className={`sp-real-range-control-ranger-row${!showInput ? " input-active" : ""}`}>
			<div className="sp-real-range-control sp-side-demo-icon">
				<span className="sp-link-side-icon">{sideAxisIcons[iconType]}</span>
			</div>
			<div className="sp-real-range-control sp-ranger">
				<RangeControl
					value={rangeValue}
					onChange={(val) => onChangeHandler(sideKey, val)}
					step={rangerStep}
					min={min}
					max={showInput && rangeMax ? rangeMax : max}
					withInputField={!showInput}
					marks={showInput}
					__nextHasNoMarginBottom
					__next40pxDefaultSize
					renderTooltipContent={(value) => `${value}${selectUnit || ""}`}
				/>
			</div>
			<div
				className="sp-real-range-control sp-preset-btn sp-cursor-pointer"
				onClick={() => setShowInput(!showInput)}
			>
				<PopOverToggleIcon />
			</div>
		</div>
	);
};

const Spacing = ({
	label,
	attributes,
	attributesKey,
	setAttributes,
	onChange = false,
	units = ["px", "%", "em"],
	step = 1,
	min = 0,
	max = 200,
	rangeStep = 8,
	rangeMax = 48,
	resetIcon = true,
	indicator = "",
	defaultValue = { unit: "px", value: { top: 0, right: 0, bottom: 0, left: 0 } },
	customClass = "",
}) => {
	const deviceType = useDeviceType();

	// Responsive when the attribute carries a per-device map.
	const isResponsive = attributes && typeof attributes?.device === "object";

	const attrValue = () => {
		if (isResponsive && attributes?.device) {
			return attributes?.device?.[deviceType] || {};
		}
		if ("object" === typeof attributes?.value) {
			return attributes?.value;
		}
		return attributes || {};
	};

	// Mirror the active device/attribute value locally; resync on external change.
	const [simpleAttr, setSimpleAttr] = useState(attrValue());

	useEffect(() => {
		setSimpleAttr(attrValue());
	}, [deviceType, attributes]);

	const allChange = attributes?.allChange || false;

	const getUpdatedAttr = (value) => {
		if (isResponsive && attributes?.device) {
			return {
				...attributes,
				device: {
					...attributes.device,
					[deviceType]: value,
				},
			};
		}
		return {
			...attributes,
			value,
		};
	};

	const onChangeHandler = (side, newVal) => {
		let updatedValue = { ...simpleAttr };
		if (allChange || side === "all") {
			updatedValue = { top: newVal, right: newVal, bottom: newVal, left: newVal };
		} else {
			updatedValue[side] = newVal;
			// Side rangers render an empty side as 0; store that 0 so the css box
			// keeps all four sides instead of collapsing the shorthand.
			["top", "right", "bottom", "left"].forEach((boxSide) => {
				if (
					updatedValue[boxSide] === "" ||
					updatedValue[boxSide] === undefined ||
					updatedValue[boxSide] === null
				) {
					updatedValue[boxSide] = 0;
				}
			});
		}

		const updateAttr = getUpdatedAttr(updatedValue);
		if (onChange) {
			onChange(updateAttr);
		} else {
			setAttributes({ [attributesKey]: updateAttr });
		}
		setSimpleAttr(updatedValue);
	};

	const onValueReset = () => {
		const resetVal = defaultValue?.value || defaultValue;
		onChangeHandler("all", resetVal.top || 0);
	};

	// Current unit for the tooltip suffix.
	const getCurrentUnit = () => {
		if (isResponsive && attributes?.unit) {
			return attributes?.unit?.[deviceType] || attributes?.unit;
		}
		return attributes?.unit;
	};

	const IndicatorIcon = indicator ? sideAxisIcons["radius-all"] : sideAxisIcons.all;

	const toggleAllChange = () => {
		setAttributes({ [attributesKey]: { ...attributes, allChange: !allChange } });
	};

	return (
		<div
			className={`sp-real-spacing-range-control sp-real-range-control sp-real-component-mb${
				customClass ? " " + customClass : ""
			}`}
		>
			<div className="sp-real-header-control sp-d-flex sp-align-center sp-justify-between">
				<div className="sp-real-header-control-left sp-d-flex sp-align-center sp-gap-8px">
					<span className="sp-real-component-title">{label}</span>
					{isResponsive && <Responsive />}
				</div>
				<div className="sp-real-header-control-right sp-d-flex sp-align-center sp-gap-8px">
					{resetIcon && <ResetButton onClick={() => onValueReset()} />}
					<div className={`sp-link-btn${allChange ? "" : " active"}`} onClick={toggleAllChange}>
						<span className="sp-link-side-icon">{IndicatorIcon}</span>
					</div>
					{units && (
						<Units
							attributes={attributes}
							setAttributes={setAttributes}
							attributesKey={attributesKey}
							units={units}
						/>
					)}
				</div>
			</div>
			{allChange ? (
				<SpCustomRanger
					attr={simpleAttr}
					selectUnit={getCurrentUnit()}
					onChangeHandler={onChangeHandler}
					sideKey={"all"}
					min={min}
					max={max}
					rangeStep={indicator ? 4 : rangeStep}
					step={step}
					rangeMax={indicator ? 24 : rangeMax}
					indicator={indicator}
				/>
			) : (
				["top", "right", "bottom", "left"].map((side) => (
					<SpCustomRanger
						key={side}
						attr={simpleAttr}
						selectUnit={getCurrentUnit()}
						onChangeHandler={onChangeHandler}
						sideKey={side}
						min={min}
						max={max}
						rangeStep={rangeStep}
						step={step}
						rangeMax={rangeMax}
						indicator={indicator}
					/>
				))
			)}
		</div>
	);
};

export default memo(Spacing);
