import { __ } from "@wordpress/i18n";
import { Button, ColorPicker, ColorIndicator, Dropdown } from "@wordpress/components";
import { memo, useState, useEffect } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { ResetButton } from "../index";
import { NewColorAddIcon } from "./Icons";
import "./editor.scss";

const ColorPalateDisplay = ({
	label,
	colors = [],
	onChangePanelColor,
	showNewColorAdd = false,
	handleCustomColor = false,
	palateName = "custom",
}) => {
	const handleColorChange = (newColor) => {
		const isVariableColor = newColor?.startsWith("var(--");
		if (isVariableColor) {
			const variableNameMatch = newColor.match(/--[\w-]+/);
			if (variableNameMatch) {
				const rootStyles = getComputedStyle(document.documentElement);
				const astColor = rootStyles.getPropertyValue(variableNameMatch[0]).trim();
				onChangePanelColor(astColor);
			}
			return;
		}
		onChangePanelColor(newColor);
	};

	return (
		<div className="sp-real-color-picker-palette-display-container">
			<div className="sp-real-palette-title-wrapper sp-d-flex sp-justify-between">
				<span className="sp-real-color-picker-palette-title">{label}</span>
			</div>
			<ul className="sp-real-color-picker-palette sp-d-grid">
				{colors?.map((item, i) => (
					<li
						key={i}
						style={{
							backgroundColor: item?.color,
						}}
						title={item?.name}
					>
						<Button onClick={() => handleColorChange(item?.color)} value={item.color} />
						{palateName === "custom" && i > 3 && (
							<span
								onClick={(e) => {
									e.stopPropagation();
									handleCustomColor("remove", item?.name);
								}}
								className="sp-real-color-remove-button"
							>
								<svg
									xmlns="http://www.w3.org/2000/svg"
									fill="none"
									viewBox="0 0 24 24"
									height={24}
									width={24}
								>
									<path
										fill="#1E1E1E"
										d="m12 13.06 3.712 3.713 1.06-1.061-3.712-3.713 3.713-3.712-1.061-1.06-3.713 3.712-3.712-3.712-1.06 1.06L10.939 12l-3.712 3.713 1.06 1.06L12 13.06Z"
									/>
								</svg>
							</span>
						)}
					</li>
				))}
				{showNewColorAdd && (
					<li
						title={__("Add this color to custom colors", "testimonial-free")}
						className="sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer"
					>
						<span
							className="sp-d-flex sp-align-center sp-justify-center"
							onClick={(e) => {
								e.stopPropagation();
								handleCustomColor("add", `color-${colors?.length + 1}`);
							}}
						>
							<NewColorAddIcon />
						</span>
					</li>
				)}
			</ul>
		</div>
	);
};

const SpColorPicker = ({
	setAttributes,
	value,
	attributesKey,
	label,
	onChange,
	defaultColor = "",
	resetButton = true,
	showThemeColors = true,
	activeState = false,
}) => {
	const activeValue = activeState ? value[activeState] : value;
	const [activeColor, setActiveColor] = useState(activeValue);
	const [themeColors, setThemeColors] = useState([]);
	const [savedCustomColors, setSavedCustomColors] = useState([]);
	const [showNewColorAdd, setShowNewColorAdd] = useState(false);

	const customColors = [
		{ name: "color-0", color: "var(--sp-real-primary-color)" },
		{ name: "color-2", color: "var(--sp-real-primary-text-color)" },
		{ name: "color-3", color: "var(--sp-real-border-color)" },
		{ name: "color-4", color: "var(--sp-real-link-color)" },
		...savedCustomColors,
	];

	// Fetch theme colors on mount.
	useEffect(() => {
		const fetchThemeColors = async () => {
			try {
				const response = await apiFetch({ path: "/sp-rtp/v2/theme-colors" });
				if (response?.success) {
					setThemeColors(response.theme_colors || []);
					setSavedCustomColors(response.custom_colors || []);
				}
			} catch (error) {
				// eslint-disable-next-line no-console
				console.error("Failed to fetch theme colors:", error);
			}
		};
		fetchThemeColors();
	}, []);

	// Save custom colors via REST API.
	const saveCustomColors = async (newColors) => {
		try {
			await apiFetch({
				path: "/sp-rtp/v2/theme-colors",
				method: "POST",
				data: { colorSettingsData: JSON.stringify(newColors) },
			});
		} catch (error) {
			// eslint-disable-next-line no-console
			console.error("Failed to save custom colors:", error);
		}
	};

	const handleCustomColor = (event, name) => {
		let updatedColors = [];
		if (event === "add") {
			updatedColors = [...savedCustomColors, { name, color: activeColor }];
		} else {
			updatedColors = savedCustomColors.filter((item) => item.name !== name);
		}
		setSavedCustomColors(updatedColors);
		saveCustomColors(updatedColors);
		setShowNewColorAdd(false);
	};

	// color picker panel color change function.
	const onChangePanelColor = (newColor) => {
		const allColors = [...customColors, ...themeColors];
		const findResult = allColors?.find((item) => item.color === newColor);
		const isExist = findResult ? true : false;
		setShowNewColorAdd(!isExist);
		// set new color.
		setActiveColor(newColor);
		if (onChange) {
			onChange(newColor);
			return;
		}
		if (activeState) {
			setAttributes({ [attributesKey]: { ...value, [activeState]: newColor } });
		} else {
			setAttributes({ [attributesKey]: newColor });
		}
	};

	// color reset function.
	const setDefault = () => {
		onChangePanelColor(defaultColor);
	};

	return (
		<div className="sp-real-color-picker sp-real-component-mb sp-d-flex sp-justify-between sp-align-center">
			<span className="sp-real-component-title">{label}</span>
			<div className="sp-real-color-picker-right-area sp-d-flex sp-align-center">
				{resetButton && <ResetButton onClick={() => setDefault()} />}
				<Dropdown
					popoverProps={{ placement: "bottom-start" }}
					renderToggle={({ isOpen, onToggle }) => (
						<Button onClick={onToggle} aria-expanded={isOpen}>
							<ColorIndicator colorValue={activeValue} />
						</Button>
					)}
					onClose={(event, isInside) => {
						if (isInside) {
							event.stopPropagation();
						}
					}}
					renderContent={() => (
						<div
							onMouseDown={(event) => {
								event.stopPropagation();
							}}
							className="sp-real-color-picker-renderer"
						>
							<ColorPicker
								className="sp-real-color-picker"
								color={activeColor}
								onChange={onChangePanelColor}
								colors={customColors}
								enableAlpha
							/>
							{/* theme colors  */}
							{showThemeColors && themeColors?.length > 0 && (
								<ColorPalateDisplay
									label={__("Theme colors", "testimonial-free")}
									colors={themeColors}
									onChangePanelColor={onChangePanelColor}
									palateName="theme"
								/>
							)}
							{/* custom colors  */}
							{customColors?.length > 0 && (
								<ColorPalateDisplay
									label={__("Custom colors", "testimonial-free")}
									colors={customColors}
									onChangePanelColor={onChangePanelColor}
									showNewColorAdd={showNewColorAdd}
									handleCustomColor={handleCustomColor}
								/>
							)}
						</div>
					)}
				/>
			</div>
		</div>
	);
};

export default memo(SpColorPicker);
