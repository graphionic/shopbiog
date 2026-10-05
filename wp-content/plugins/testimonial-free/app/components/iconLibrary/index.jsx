import { Modal, Button } from "@wordpress/components";
import { useState, useEffect, useMemo } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { Divider, ButtonGroup, MediaPicker } from "@testimonial/components";
import useIconList from "./useIconList";
import "./editor.scss";

const DEFAULT_ICON = { iconName: "", viewBox: "", path: "", label: "" };
const DEFAULT_VALUE = { source: "icon", icon: { ...DEFAULT_ICON }, image: {} };

const STYLE_FILTERS = ["Solid", "Regular", "Light"];
const CATEGORIES = ["All Icons", "Accessibility", "Alert", "Animals", "Arrows", "Media"];

const toSlug = (text) => text.replaceAll(" ", "-").toLowerCase();

const shortenLabel = (text) => {
	if (text.includes("-")) {
		return text.split("-")[0];
	}
	const parts = text.trim().split(/\s+/);
	return parts.length > 1 ? `${parts[0]} …` : parts[0];
};

const normalize = (attributes) => {
	if (attributes && typeof attributes === "object") {
		return {
			source: attributes.source || "icon",
			icon: { ...DEFAULT_ICON, ...(attributes.icon || {}) },
			image: attributes.image || {},
		};
	}
	// Legacy: attributes was a plain icon-name string.
	return {
		...DEFAULT_VALUE,
		icon: { ...DEFAULT_ICON, iconName: attributes || "" },
	};
};

const filterIcons = (icons, { styles, category, search }) =>
	Object.fromEntries(
		Object.entries(icons).filter(([, icon]) => {
			if (styles.length > 0 && !icon.style.some((s) => styles.includes(s))) {
				return false;
			}
			if ("all-icons" !== category && !icon.category.includes(category)) {
				return false;
			}
			if (search && !icon.label.toLowerCase().includes(search.toLowerCase())) {
				return false;
			}
			return true;
		})
	);

const IconSvg = ({ icon, className }) => (
	<svg width={icon?.width} height={icon?.height} viewBox={icon?.viewBox} className={className}>
		<path d={icon?.path} />
	</svg>
);

const PlusGlyph = () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 512 512"
		fill="none"
		width="28"
		height="28"
		className="sp-icon-picker-default-icon"
	>
		<path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z" />
	</svg>
);

const CloseGlyph = () => (
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" className="sp-icon-picker-remove-icon-svg">
		<path d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z" />
	</svg>
);

const SearchGlyph = () => (
	<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
		<path
			fillRule="evenodd"
			clipRule="evenodd"
			d="M10.53 4.34813C10.53 6.74953 8.58323 8.69626 6.18183 8.69626C5.17704 8.69626 4.25184 8.35544 3.51556 7.78309L0.768648 10.53L0 9.76135L2.7469 7.01445C2.17454 6.27815 1.8337 5.35294 1.8337 4.34813C1.8337 1.94672 3.78042 0 6.18183 0C8.58323 0 10.53 1.94672 10.53 4.34813ZM9.44293 4.34813C9.44293 6.14918 7.98288 7.60923 6.18183 7.60923C4.38078 7.60923 2.92073 6.14918 2.92073 4.34813C2.92073 2.54708 4.38078 1.08703 6.18183 1.08703C7.98288 1.08703 9.44293 2.54708 9.44293 4.34813Z"
			fill="#757575"
		/>
	</svg>
);

const IconsLibrary = ({ attributes, attributesKey, setAttributes, onChange = false }) => {
	const allIcons = useIconList();
	const current = normalize(attributes);
	const { source, icon: savedIcon, image: savedImage } = current;

	const [styles, setStyles] = useState([]);
	const [isOpen, setOpen] = useState(false);
	const [iconName, setIconName] = useState(savedIcon.iconName);
	const [searchValue, setSearchValue] = useState("");
	const [iconCat, setIconCat] = useState("all-icons");

	useEffect(() => {
		if (isOpen) {
			setIconName(savedIcon.iconName);
		}
	}, [isOpen, savedIcon.iconName]);

	const filteredIcons = useMemo(
		() => filterIcons(allIcons, { styles, category: iconCat, search: searchValue }),
		[allIcons, styles, iconCat, searchValue]
	);

	const activeIcon = savedIcon.path
		? {
				width: 28,
				height: 28,
				viewBox: savedIcon.viewBox,
				path: savedIcon.path,
				label: savedIcon.label || savedIcon.iconName,
			}
		: allIcons[savedIcon.iconName];

	const commit = (next) => {
		const merged = { ...current, ...next };
		if (onChange) {
			onChange(merged);
		} else if (attributesKey && setAttributes) {
			setAttributes({ [attributesKey]: merged });
		}
	};

	const handleStyleChange = (e) => {
		const { value, checked } = e.target;
		setStyles((prev) => (checked ? [...prev, value] : prev.filter((s) => s !== value)));
	};

	const handleInsert = () => {
		const selected = allIcons[iconName] || {};
		commit({
			icon: {
				iconName,
				viewBox: selected.viewBox || "",
				path: selected.path || "",
				label: selected.label || "",
			},
		});
		setOpen(false);
	};

	const handleRemove = () => {
		setIconName("");
		commit({ icon: { ...DEFAULT_ICON } });
		setOpen(true);
	};

	const sourceItems = [
		{ label: __("Library", "testimonial-free"), value: "icon" },
		{ label: __("Custom", "testimonial-free"), value: "custom" },
	];

	return (
		<>
			<ButtonGroup
				label={__("Icon Source", "testimonial-free")}
				items={sourceItems}
				attributes={source}
				onClick={(value) => commit({ source: value })}
			/>
			{"icon" === source && (
				<>
					<div className="sp-icon-picker-selector-container sp-d-flex sp-flex-col sp-align-center sp-justify-center">
						<Button variant="secondary" onClick={() => setOpen(true)}>
							{activeIcon ? (
								<div className="sp-icon-picker-selected-icon-container sp-d-flex sp-flex-col sp-align-center sp-justify-center sp-gap-8px">
									<IconSvg icon={activeIcon} className="sp-icon-picker-active-icon" />
									<span className="sp-icon-picker-active-icon-label">{activeIcon.label}</span>
								</div>
							) : (
								<div className="sp-icon-picker-default-icon-container">
									<PlusGlyph />
								</div>
							)}
						</Button>
						<button
							onClick={handleRemove}
							className="sp-icon-picker-remove-icon sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer"
						>
							<CloseGlyph />
						</button>
					</div>
					{isOpen && (
						<Modal className="sp-icon-picker" onRequestClose={() => setOpen(false)}>
							<Button className="sp-icon-picker-close-btn" onClick={() => setOpen(false)}>
								X
							</Button>
							<div className="sp-icon-picker-body sp-d-flex">
								<div className="sp-icon-picker-sidebar">
									<h2 className="sp-icon-picker-title">Icon Library</h2>
									<Divider />
									<h4 className="sp-category-indicator-label">Style</h4>
									<ul className="sp-icon-picker-cat-list sp-icon-picker-style">
										{STYLE_FILTERS.map((type) => {
											const id = type.toLowerCase();
											return (
												<li
													key={id}
													className="sp-icon-picker-style-item sp-d-flex sp-align-center"
												>
													<input
														onClick={handleStyleChange}
														type="checkbox"
														value={id}
														id={id}
													/>
													<label htmlFor={id}>{type}</label>
												</li>
											);
										})}
									</ul>
									<Divider />
									<h4 className="sp-category-indicator-label">Categories</h4>
									<ul className="sp-icon-picker-cat-list">
										{CATEGORIES.map((cat) => {
											const slug = toSlug(cat);
											return (
												<li
													className={`sp-icon-picker-cat-item${iconCat === slug ? " active" : ""}`}
													key={slug}
												>
													<button
														onClick={(e) => setIconCat(e.target.value)}
														value={slug}
														type="button"
													>
														{cat}
													</button>
												</li>
											);
										})}
									</ul>
									<Divider />
								</div>
								<div className="sp-icon-picker-content">
									<div className="sp-icon-picker-search-bar">
										<SearchGlyph />
										<input
											type="text"
											value={searchValue}
											onChange={(e) => setSearchValue(e.target.value)}
											className="sp-icon-picker-search-input"
											placeholder="Search icons..."
										/>
									</div>
									<div className="sp-icon-picker-icons sp-d-flex">
										<ul className="sp-d-grid">
											{Object.entries(filteredIcons).map(([key, icon]) => (
												<li key={key} className="sp-icon-picker-single-icon">
													<button
														className={`sp-d-flex sp-flex-col sp-align-center sp-justify-center sp-cursor-pointer${key === iconName ? " active" : ""}`}
														onClick={() => setIconName(key)}
														title={icon.label}
														value={key}
													>
														<IconSvg icon={icon} />
														<span>{shortenLabel(icon.label)}</span>
													</button>
												</li>
											))}
										</ul>
									</div>
									<Button className="sp-icon-picker-insert-btn sp-d-block" onClick={handleInsert}>
										Insert Icon
									</Button>
								</div>
							</div>
						</Modal>
					)}
				</>
			)}
			{"custom" === source && (
				<MediaPicker
					customClass="sp-eab-image-picker"
					enableImageSize={false}
					backgroundImage={savedImage}
					onSelect={(media) => commit({ image: { url: media?.url, alt: media?.alt } || {} })}
				/>
			)}
		</>
	);
};

export default IconsLibrary;
