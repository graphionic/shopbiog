import { memo } from "@wordpress/element";
import { Button } from "@wordpress/components";
import { useDeviceType } from "@testimonial/controls";
import "./editor.scss";

const Units = ({ attributes, setAttributes, attributesKey, units }) => {
	const deviceType = useDeviceType();
	// Set unit function.
	const setUnit = (newValue) => {
		if (attributes.unit[deviceType]) {
			setAttributes({
				[attributesKey]: {
					...attributes,
					unit: {
						...attributes.unit,
						[deviceType]: newValue.toLowerCase(),
					},
				},
			});
		} else {
			setAttributes({
				[attributesKey]: {
					...attributes,
					unit: newValue.toLowerCase(),
				},
			});
		}
	};

	const unit = "object" === typeof attributes?.unit ? attributes.unit[deviceType] : attributes?.unit;

	return (
		<div className="sp-real-units-indicator">
			<span className="sp-real-units-label sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer">
				{unit}
			</span>
			<div className="sp-real-units-btn">
				{units?.map((item, i) => (
					<Button
						key={i}
						className={unit === item.toLowerCase() ? "active" : ""}
						value={item}
						onClick={(e) => setUnit(e.target.value)}
					>
						{" "}
						{item}{" "}
					</Button>
				))}
			</div>
		</div>
	);
};

export default memo(Units);
