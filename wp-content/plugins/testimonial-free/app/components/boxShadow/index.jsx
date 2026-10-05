import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { Tooltip } from "@wordpress/components";
import { ColorPicker, ToggleControl, Units } from "@testimonial/components";
import { boxShadowValue } from "@testimonial/controls";
import { ResetIcon, BorderIcon } from "@testimonial/icons";
import { BoxSpacing } from "./boxSpacing";
import "./editor.scss";

/**
 * Preset shadows. Each preset is the shared `box_shadow` value shape, so a click writes
 * directly to the attribute and renders through `boxShadowCss` — no CSS-variable mapping.
 * Side order mirrors `Utils::box_shadow`: top → X, right → Y, bottom → Blur, left → Spread.
 */
const SHADOW_PRESETS = [
	{ title: __("Soft", "testimonial-free"), value: { top: 0, right: 1, bottom: 2, left: 0 } },
	{ title: __("Low", "testimonial-free"), value: { top: 0, right: 2, bottom: 4, left: 0 } },
	{ title: __("Medium", "testimonial-free"), value: { top: 0, right: 4, bottom: 6, left: 0 } },
	{ title: __("High", "testimonial-free"), value: { top: 0, right: 8, bottom: 10, left: 0 } },
	{ title: __("Deep", "testimonial-free"), value: { top: 0, right: 12, bottom: 17, left: 0 } },
	{ title: __("Glow", "testimonial-free"), value: { top: 6, right: 6, bottom: 0, left: 0 } },
];

const matchesPreset = (shadow, preset) => {
	const sides = ["top", "right", "bottom", "left"];
	return sides.every((side) => Number(shadow?.value?.[side]) === preset.value[side]);
};

const BoxShadow = ({
	label = __("Box Shadow", "testimonial-free"),
	attributes,
	attributesKey,
	setAttributes,
	onChange = false,
	defaultValue = {
		value: { top: 0, right: 4, bottom: 6, left: 0 },
		unit: "outset",
		color: "#0000001A",
	},
}) => {
	const shadow = attributes || {};
	const isActive = shadow?.isActive;

	// Preset mode by default when current values match a known preset; custom otherwise.
	const [customShadow, setCustomShadow] = useState(
		() => !SHADOW_PRESETS.some((preset) => matchesPreset(shadow, preset))
	);

	const writeShadow = (next) => {
		if (onChange) {
			onChange(attributesKey, next);
		} else {
			setAttributes({ [attributesKey]: next });
		}
	};

	const handleToggle = (value) => {
		writeShadow({ ...shadow, isActive: value });
	};

	const handlePreset = (preset) => {
		writeShadow({
			...shadow,
			value: { ...preset.value },
			unit: "outset",
			color: shadow?.color || defaultValue.color,
			isActive: true,
		});
	};

	const handleColor = (newColor) => {
		writeShadow({ ...shadow, color: newColor });
	};

	const handleReset = () => {
		writeShadow({ ...shadow, ...defaultValue });
	};

	return (
		<div className="sp-real-box-shadow-component">
			<ToggleControl label={label} attributes={isActive} onChange={handleToggle} />
			{isActive && (
				<>
					<div className="sp-real-bs-header sp-d-flex sp-align-center sp-justify-between">
						<span className="sp-real-component-title">{__("Shadow Type", "testimonial-free")}</span>
						<div className="sp-real-bs-actions sp-d-flex sp-align-center sp-gap-8px">
							<Tooltip text={__("Reset to default", "testimonial-free")}>
								<button
									className="sp-real-bs-reset sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer"
									onClick={handleReset}
								>
									<ResetIcon />
								</button>
							</Tooltip>
							{customShadow && (
								<Units
									attributes={attributes}
									setAttributes={setAttributes}
									attributesKey={attributesKey}
									units={["Inset", "Outset"]}
								/>
							)}
							<Tooltip
								text={
									customShadow
										? __("Switch to presets", "testimonial-free")
										: __("Switch to custom", "testimonial-free")
								}
							>
								<button
									className={`sp-real-bs-action sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer${
										customShadow ? " active" : ""
									}`}
									onClick={() => setCustomShadow((prev) => !prev)}
								>
									<BorderIcon color={customShadow ? "#fff" : "#2F2F2F"} />
								</button>
							</Tooltip>
						</div>
					</div>

					{customShadow ? (
						<BoxSpacing
							label={label}
							attributes={shadow}
							attributesKey={attributesKey}
							setAttributes={setAttributes}
							onChange={onChange}
						/>
					) : (
						<div className="sp-real-bs-presets sp-d-grid sp-grid-cols-6 sp-gap-8px">
							{SHADOW_PRESETS.map((preset) => {
								const selected = matchesPreset(shadow, preset);
								return (
									<Tooltip key={preset.title} text={preset.title}>
										<button
											type="button"
											className={`sp-real-bs-swatch sp-w-full sp-cursor-pointer${selected ? " selected" : ""}`}
											style={{
												boxShadow: boxShadowValue({
													...preset,
													unit: "outset",
													color: shadow?.color || defaultValue.color,
													isActive: true,
												}),
											}}
											onClick={() => handlePreset(preset)}
										>
											{selected && (
												<span
													className="sp-real-bs-check sp-d-flex sp-align-center sp-justify-center"
													aria-hidden="true"
												>
													<svg width="10" height="10" viewBox="0 0 12 12" fill="none">
														<path
															d="M10 3 4.5 8.5 2 6"
															stroke="#fff"
															strokeWidth="1.6"
															strokeLinecap="round"
															strokeLinejoin="round"
														/>
													</svg>
												</span>
											)}
										</button>
									</Tooltip>
								);
							})}
						</div>
					)}

					<ColorPicker
						label={__("Shadow Color", "testimonial-free")}
						value={shadow?.color}
						onChange={handleColor}
						defaultColor={defaultValue.color}
					/>
				</>
			)}
		</div>
	);
};

export default BoxShadow;
