import { TextareaControl } from "@wordpress/components";
import "./editor.scss";

const SPTextareaControl = ({
	label = "",
	attributes,
	attributesKey = "",
	setAttributes,
	onChange = false,
	rows = 4,
}) => {
	const onChangeValue = (value) => {
		if (onChange) {
			onChange(value);
			return;
		}
		setAttributes({ [attributesKey]: value });
	};

	return (
		<div className="sp-real-text-area-control sp-real-component-mb">
			<span className="sp-real-component-title sp-mb-8px">{label}</span>
			<TextareaControl
				label={false}
				value={attributes}
				rows={rows}
				onChange={(value) => onChangeValue(value)}
				__nextHasNoMarginBottom
			/>
		</div>
	);
};

export default SPTextareaControl;
