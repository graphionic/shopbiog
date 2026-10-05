import { __experimentalInputControl as Input } from "@wordpress/components";
import { useDeviceType } from "@testimonial/controls";
import Responsive from "../responsive";
import "./editor.scss";
import { InfoIcon } from "../Icons";
import { useState } from "@wordpress/element";

const InputControl = ({
	attributes,
	attributesKey,
	setAttributes,
	label,
	flex = false,
	inputType = "text",
	placeholder,
	onChange = false,
	help = false,
	min = 1,
	max = null,
	step = 1,
	infoText = "",
}) => {
	// Check device (desktop/tablet/mobile).
	const [isVisible, setIsVisible] = useState(false);
	const deviceType = useDeviceType();

	const value = attributes?.device ? attributes?.device[deviceType] : attributes;

	const setInputValue = (newValue) => {
		if (attributes?.device) {
			setAttributes({
				[attributesKey]: {
					...attributes,
					device: { ...attributes.device, [deviceType]: newValue },
				},
			});
		} else {
			setAttributes({ [attributesKey]: newValue });
		}
	};

	// Set value function.
	const setValue = (newValue) => {
		setInputValue(newValue);
	};

	return (
		<div className="sp-real-input-control sp-real-component-mb">
			<div
				className={`sp-real-input-control-wrapper ${flex ? "sp-flex-input sp-d-flex sp-justify-between" : "sp-block-input"}`}
			>
				{label && (
					<div
						className={`sp-real-input-control-header sp-d-flex sp-align-center sp-gap-4px${flex ? "" : " sp-mb-8px"}`}
					>
						<span className="sp-real-component-title">{label}</span>
						{infoText && (
							<span
								className="sp-real-label-info-text__popup-trigger sp-cursor-pointer"
								onMouseEnter={() => setIsVisible(true)}
								onMouseLeave={() => setIsVisible(false)}
							>
								<InfoIcon />
							</span>
						)}
						{attributes?.device && <Responsive />}
						{isVisible && infoText && (
							<div
								onMouseEnter={() => setIsVisible(true)}
								onMouseLeave={() => setIsVisible(false)}
								className="sp-real-label-info-text__popup"
							>
								<div className="sp-reallabel-info-text__text">{infoText}</div>
							</div>
						)}
					</div>
				)}
				<Input
					type={inputType}
					value={value}
					step={step}
					onChange={(val) => (onChange ? onChange(val) : setValue(val))}
					placeholder={placeholder} // Use placeholder prop here
					help={help}
					min={min}
					{...(max && { max })}
					__next40pxDefaultSize
				/>
			</div>
		</div>
	);
};

export default InputControl;
