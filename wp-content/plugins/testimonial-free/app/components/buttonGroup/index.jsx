import { __ } from "@wordpress/i18n";
import { useDeviceType } from "@testimonial/controls";
import { memo } from "@wordpress/element";
import Responsive from "../responsive";
import Button from "../button";
import "./editor.scss";

const ButtonGroup = ({ attributes, attributesKey, setAttributes, items, label = "", onClick = false }) => {
	// Device type
	const deviceType = useDeviceType();
	const isResponsiveValue = attributes?.device ? true : false;

	// Update button group value
	const setButtonGroup = (newValue) => {
		if (isResponsiveValue) {
			setAttributes({
				[attributesKey]: {
					...attributes,
					device: {
						...attributes.device,
						[deviceType]: newValue,
					},
				},
			});
		} else {
			setAttributes({ [attributesKey]: newValue });
		}
	};

	// Get the active value
	const activeValue = isResponsiveValue ? attributes.device[deviceType] : attributes;

	// Index of the active item — drives the sliding indicator position.
	const activeIndex = items?.findIndex((item) => item.value === activeValue) ?? -1;

	// Handle button click
	const handleClick = (value, isPro) => {
		// Pro-locked items are muted and never change the value.
		if (isPro) {
			return;
		}
		if (onClick) {
			onClick(value);
		} else {
			setButtonGroup(value);
		}
	};

	return (
		<div className="sp-real-button-group sp-real-component-mb">
			{label && (
				<div className="sp-real-component-top sp-real-component-title-mb sp-d-flex sp-align-center">
					<span className="sp-real-component-title">{label}</span>
					{isResponsiveValue && <Responsive />}
				</div>
			)}
			<div
				className="sp-real-button-group-items sp-d-flex sp-align-center"
				style={{ "--total-buttons": items?.length, "--active-index": activeIndex }}
			>
				{activeIndex >= 0 && <span className="sp-real-bg-indicator" aria-hidden="true" />}
				{items?.map((item, i) => (
					<Button
						key={i}
						className={`${activeValue === item.value ? "active" : ""}${item.isPro ? " sp-real-bg-pro" : ""}`}
						value={item.value}
						disabled={item.isPro}
						onClick={() => handleClick(item.value, item.isPro)}
					>
						<span {...(item.tooltip && { title: item.tooltip })} className="sp-real-button-group-content">
							{item?.label}
							{item.isPro && <span className="sp-real-pro-tag">({__("Pro", "testimonial-free")})</span>}
						</span>
					</Button>
				))}
			</div>
		</div>
	);
};

export default memo(ButtonGroup);
