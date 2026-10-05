import Select from "react-select";
import "./editor.scss";

const MultipleSelect = ({
	attributes,
	setAttributes,
	attributesKey,
	label,
	items,
	onChange = false,
	flex = false,
	reset = false,
	onInputChange = false,
}) => {
	const updateValue = (data) => {
		setAttributes({ [attributesKey]: data });
	};

	return (
		<div
			className={`sp-real-multi-select${flex ? " sp-d-flex sp-align-center sp-justify-between" : ""} sp-real-component-mb`}
		>
			<span className="sp-real-component-title sp-mb-8px">{label}</span>
			<Select
				defaultValue={attributes}
				isMulti
				options={items}
				isClearable={reset}
				onChange={(data) => (onChange ? onChange(data) : updateValue(data))}
				onInputChange={(e) => (onInputChange ? onInputChange(e) : "")}
				className="sp-real-basic-multi-select"
			/>
		</div>
	);
};

export default MultipleSelect;
