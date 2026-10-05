import { Popover } from "@testimonial/components";
import "./editor.scss";

const SelectDropdown = ({ label, options, attributes, setAttributes, attributesKey, onClick = false }) => {
	return (
		<Popover label={label}>
			<ul className="sp-real-select-dropdown">
				{options?.map(({ value, label, icon, isPro }, index) => (
					// eslint-disable-next-line jsx-a11y/no-noninteractive-element-interactions
					<li
						key={index}
						className={`sp-real-select-dropdown-option${attributes === value ? " active" : ""}`}
						onClick={() => {
							if (isPro) {
								return;
							}
							if (onClick) {
								onClick(value);
								return;
							}
							setAttributes({
								[attributesKey]: value,
							});
						}}
					>
						{label && (
							<span>
								{label}
								{isPro && <span className="sp-real-pro-tag">{"(Pro)"}</span>}
							</span>
						)}
						{icon && <span>{icon}</span>}
					</li>
				))}
			</ul>
		</Popover>
	);
};

export default SelectDropdown;
