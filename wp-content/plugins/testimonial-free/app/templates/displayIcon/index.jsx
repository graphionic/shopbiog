const DisplayIcon = ({ icon }) => {
	if (!icon || typeof icon !== "object") {
		return null;
	}

	if ("custom" === icon?.source && !icon?.image?.url) {
		return null;
	}

	return (
		<span className="sp-real-library-icon">
			{"custom" === icon?.source && icon?.image?.url && (
				<img src={icon?.image.url} alt={icon?.image.alt || ""} style={{ width: "100%", height: "100%" }} />
			)}
			{"icon" === icon?.source && icon?.icon?.path && (
				<svg xmlns="http://www.w3.org/2000/svg" viewBox={icon?.icon?.viewBox} aria-hidden="true">
					<path d={icon?.icon?.path} />
				</svg>
			)}
		</span>
	);
};

export default DisplayIcon;
