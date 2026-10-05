import { memo } from "@wordpress/element";
import { SelectControl } from "@wordpress/components";
import { useDeviceType } from "@testimonial/controls";
import ComponentHeader from "../componentTopSection";

const SelectField = ({
	attributes,
	attributesKey,
	setAttributes,
	items,
	label = "",
	flexStyle = false,
	units = false,
	onChange = false,
}) => {
	// Device check fn
	const deviceType = useDeviceType();
	// Set Button value
	const setNewValue = (newValue) => {
		if (attributes?.device) {
			setAttributes({
				[attributesKey]: {
					device: { ...attributes?.device, [deviceType]: newValue },
				},
			});
		} else {
			setAttributes({ [attributesKey]: newValue });
		}
	};

	// Get active button value
	const activeValue = attributes?.device ? attributes?.device[deviceType] : attributes;

	return (
		<div
			className={`sp-real-select-field sp-real-component-mb ${flexStyle ? "sp-d-flex sp-justify-between sp-align-center" : "sp-d-block"}`}
		>
			<ComponentHeader
				label={label}
				attributes={attributes}
				attributesKey={attributesKey}
				setAttributes={setAttributes}
				units={units}
			/>
			<SelectControl
				className="custom-select-control"
				value={activeValue}
				options={items}
				onChange={(newField) => (onChange ? onChange(newField) : setNewValue(newField))}
				__nextHasNoMarginBottom
				__next40pxDefaultSize
			/>
		</div>
	);
};

export default memo(SelectField);
