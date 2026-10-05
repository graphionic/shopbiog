const BgButtons = ({ attributes, attributesKey, setAttributes, label, items, onClick = false }) => {
	const setBgType = (newValue) => {
		if (onClick) {
			onClick(newValue);
		} else {
			setAttributes({ [attributesKey]: newValue });
		}
	};
	return (
		<div className="sp-real-background sp-real-component-mb">
			<div className="sp-real-background-control sp-d-flex sp-justify-between sp-align-center">
				<span className="sp-real-component-title">{label}</span>
				<div className="sp-real-background-left sp-d-flex sp-align-center sp-gap-4px">
					{items?.map((option) => (
						<button
							key={option.value}
							type="button"
							className={`sp-bg-indicator ${attributes === option.value ? "active" : ""}`}
							onClick={() => setBgType(option.value)}
						>
							{option.label}
						</button>
					))}
				</div>
			</div>
		</div>
	);
};

export default BgButtons;
