import { useSelect } from "@wordpress/data";
import { defaultDashboardSettings } from "@testimonial/constants";

// Device type fn.
export const useDeviceType = () => {
	const { deviceType } = useSelect((select) => {
		return {
			deviceType: select("core/editor")?.getDeviceType() || "Desktop",
		};
	}, []);

	return deviceType || "";
};

export const getRandomId = (prefix = "") => {
	const randomNumber = Math.floor(10000000 + Math.random() * 90000000);
	const randomId = `${prefix}${randomNumber}`;
	return randomId;
};

export const inArray = (array, value) => {
	return array.includes(value);
};

export const jsonStringify = (data) => {
	return JSON.stringify(data);
};

export const jsonParse = (data) => {
	return JSON.parse(data);
};

// Raw box-shadow value string (e.g. editor preset preview swatches). Empty when disabled.
export const boxShadowValue = (shadow = {}) => {
	if (!shadow?.isActive) {
		return "";
	}
	const { value, unit, color } = shadow;
	return `${unit === "inset" ? "inset " : ""}${value.top}px ${value.right}px ${value.bottom}px ${value.left}px ${color}`.trim();
};

// Box-shadow declaration map (mirrors PHP box_shadow_css / get_border_styles): spread into a
// styles object via `...boxShadowCss(x)`. Disabled → {} so no `box-shadow` is emitted at all.
export const boxShadowCss = (shadow = {}) => {
	const value = boxShadowValue(shadow);
	return value ? { "box-shadow": value } : {};
};

// Background controls fn.
export const spRealBgControl = (backgroundAttr) => {
	const { style, solid, gradient, image } = backgroundAttr;
	const bgOptions = {
		transparent: "transparent",
		solid,
		gradient,
		image: image?.url ? `url(${image?.url})` : "",
	};
	return bgOptions[style];
};

export const spRealBackgroundImgCss = (attr) => {
	const style = attr?.style || "";
	const bgImg = attr?.imageSettings || {};

	if (style === "image") {
		return {
			"background-position": bgImg.bgImagePosition || "",
			"background-attachment": bgImg.bgImageAttachment || "",
			"background-repeat": bgImg.bgImageRepeat || "",
			"background-size": bgImg.bgImageSize || "",
		};
	}

	return {};
};

/**
 * Truthy-check a single css value: rejects null/undefined/empty-string but keeps `0`.
 *
 * @param {*} value - Raw side / scalar value coming from a block attribute.
 * @return {boolean} True when the value should be emitted to css.
 */
const isFilledValue = (value) => value !== null && value !== undefined && value.toString().trim().length > 0;

/**
 * Format a `range_control` attribute (non-responsive scalar + unit) into a css value.
 *
 * Attribute shape (from `Utils::range_control`):
 *   { value: number|string, unit: string }
 *
 * Use case: single numeric controls such as `cardHoverTransition` (600 + "ms")
 * or `paginationNumberGap` — e.g. `transition-duration: rangeControlCss(cardHoverTransition)`.
 *
 * @param {Object} attribute - The range_control attribute object.
 * @return {string} e.g. "600ms", or "" when value is empty.
 */
export const rangeControlCss = (attribute) => {
	if (!attribute || typeof attribute !== "object") {
		return "";
	}

	return isFilledValue(attribute.value) ? `${attribute.value}${attribute.unit ?? ""}` : "";
};

//formatBoxValue.
const formatBoxValue = (sides, sideUnit = "") => {
	if (!sides || typeof sides !== "object") {
		return "";
	}

	return ["top", "right", "bottom", "left"]
		.filter((side) => isFilledValue(sides[side]))
		.map((side) => `${sides[side]}${sideUnit}`)
		.join(" ");
};

/**
 * Format a `single_responsive` attribute (one value per device) into a css value.
 *
 * Attribute shape (from `Utils::single_responsive`):
 *   { device: { Desktop, Tablet, Mobile }, unit: { Desktop, Tablet, Mobile } }
 *
 * Use case: per-device single values such as `columnGap`, `imageWidth`, `ratingIconSize`
 * — e.g. `width: singleResponsiveCss(imageWidth, deviceType)`.
 *
 * @param {Object} attribute  - The single_responsive attribute object.
 * @param {string} deviceType - "Desktop" | "Tablet" | "Mobile" (default "Desktop").
 * @return {string} e.g. "70px", or "" when empty.
 */
export const singleResponsiveCss = (attribute, deviceType = "Desktop") => {
	if (!attribute || typeof attribute !== "object") {
		return "";
	}

	const value = attribute?.device?.[deviceType];
	return isFilledValue(value) ? `${value}${unit(attribute, deviceType)}` : "";
};

/**
 * Format a non-responsive `spacing` attribute (4-side box, no device) into a css value.
 *
 * Attribute shape (from `Utils::spacing`):
 *
 * Use case: non-responsive boxes such as `cardBorderRadius`, `imageBorderWidth`
 * — e.g. `border-radius: spacingCss(cardBorderRadius)`.
 *
 * @param {Object} attribute - The spacing attribute object.
 * @return {string} "16px" (linked) or "16px 16px 16px 16px" (unlinked), or "".
 */
export const spacingCss = (attribute) => {
	if (!attribute || typeof attribute !== "object") {
		return "";
	}

	return formatBoxValue(attribute.value, attribute.unit ?? "");
};

/**
 * Format a `responsive_spacing` attribute (4-side box per device) into a css value.
 *
 * Attribute shape (from `Utils::responsive_spacing`):
 *   { device: { Desktop: { top, right, bottom, left }, Tablet: {...}, Mobile: {...} },
 *
 * Use case: per-device boxes such as `cardPadding`, `titleMargin`, `formPadding`
 * — e.g. `padding: responsiveSpacingCss(cardPadding, deviceType)`.
 *
 * @param {Object} attribute  - The responsive_spacing attribute object.
 * @param {string} deviceType - "Desktop" | "Tablet" | "Mobile" (default "Desktop").
 * @return {string} "32px" (linked) or "12px 0px 12px 0px" (unlinked), or "".
 */
export const responsiveSpacingCss = (attribute, deviceType = "Desktop") => {
	if (!attribute || typeof attribute !== "object") {
		return "";
	}

	return formatBoxValue(attribute?.device?.[deviceType], unit(attribute, deviceType));
};

// Check unit single or object.
export const unit = (attributes, deviceType) => {
	if (!attributes || typeof attributes !== "object" || attributes.unit === undefined || attributes.unit === null) {
		return "";
	}
	if ("object" !== typeof attributes.unit) {
		return attributes.unit;
	}
	return attributes.unit[deviceType] || "";
};

// Object to css convert fn.
export const cssString = (css) => {
	let result = "";
	for (const selector in css) {
		let cssProps = "";
		for (const property in css[selector]) {
			if (css[selector][property] && css[selector][property].length > 0) {
				cssProps += property + ":" + css[selector][property] + ";";
			}
		}
		result += "" !== cssProps ? selector + "{" + cssProps + "}" : "";
	}
	return result;
};

// dynamic css utils.
export const objectToCssString = (dynamicCss) => {
	let css = "";
	dynamicCss?.forEach((item) => {
		if (item.styles) {
			let styles = "";
			Object.entries(item.styles).forEach(([property, value]) => {
				if (value !== null && value !== undefined && value !== "") {
					styles += `${property}: ${value};`;
				}
			});
			if (styles) {
				css += `${item.selector} {${styles}}`;
			}
		}
	});
	return css;
};

export const generateTypographyCss = (typography) => {
	const { family, fontWeight, style, transform, decoration } = typography;
	const styles = {
		...(family && { "font-family": family }),
		...(fontWeight && { "font-weight": fontWeight }),
		...(style && style !== "normal" && { "font-style": style }),
		...(transform !== "none" && { "text-transform": transform }),
		...(decoration !== "none" && { "text-decoration": decoration }),
	};
	return styles;
};

export const generateTypoResponsive = (attributes, device, key) => {
	const fontSize = attributes[`${key}FontSize`].device[device];
	const lineHeight = attributes[`${key}LineHeight`].device[device];
	let letterSpacing = attributes[`${key}LetterSpacing`]?.device?.[device];

	if (letterSpacing === null || letterSpacing === undefined || letterSpacing === "") {
		letterSpacing = 0;
	}

	return {
		...(fontSize && {
			"font-size": fontSize + attributes[`${key}FontSize`].unit[device],
		}),
		...(lineHeight && {
			"line-height": lineHeight,
		}),
		...(letterSpacing !== undefined && {
			"letter-spacing": letterSpacing + (attributes[`${key}LetterSpacing`]?.unit?.[device] || "px"),
		}),
	};
};

export const generateBorderStyles = (border, borderWidth) => {
	const { style, color } = border;
	if (style === "none") {
		return { border: "none" };
	}
	const borderStyle = {
		"border-style": style,
		"border-color": color,
		"border-width": spacingCss(borderWidth),
	};
	return borderStyle;
};

export const filterDuplicateSelector = (cssArray) => {
	const selectorMap = new Map();
	cssArray.forEach((css) => {
		if (css) {
			const { selector, styles } = css;
			if (Object.keys(styles).length > 0) {
				const existing = selectorMap.get(selector);
				selectorMap.set(selector, {
					selector,
					styles: existing ? { ...existing.styles, ...styles } : styles,
				});
			}
		}
	});
	return Array.from(selectorMap.values());
};

export const filterResponsiveDynamicCss = (cssObj) => {
	const { desktopCss, tabletCss, mobileCss } = cssObj;
	const filteredDesktopCss = filterDuplicateSelector(desktopCss);
	const filteredTabletCss = filterDuplicateSelector(tabletCss);
	const filteredMobileCss = filterDuplicateSelector(mobileCss);
	// css string for editor.
	const updatedCssString = `${objectToCssString(
		filteredDesktopCss
	)} @media only screen and (min-width: 600px) and (max-width: 1023px) { ${objectToCssString(
		filteredTabletCss
	)} } @media only screen and (max-width: 599px) {${objectToCssString(filteredMobileCss)}}`;
	return updatedCssString;
};

export const getRealBlockProps = (blockProps, customIdName = "", customClassName = "") => {
	let props = {
		...blockProps,
		className: `sp-real-testimonial-block ${blockProps?.className}`,
	};
	if ("" !== customIdName) {
		props = { ...props, id: `${customIdName}` };
	}
	if ("" !== customIdName) {
		props = { ...props, className: `${props?.className} ${customClassName}` };
	}
	return props;
};

// Google fonts list controls fn.
// Build both Google-fonts payloads in one pass: `editor` = the @import CSS for
// the editor <style>; `frontend` = the unique font list (JSON string) persisted
// to the `fontLists` attribute and consumed by the frontend/PHP.
export const fontFamilyToUrlGenerator = (typographiesArray) => {
	// Normalize non-array / nullish input to [] so falsy args return empty, never throw.
	const familyArray = (Array.isArray(typographiesArray) ? typographiesArray : [])
		.filter(Boolean)
		.filter(({ family }) => family?.length > 0);
	if (familyArray.length === 0) {
		return { editor: "", frontend: "" };
	}
	// Editor @import.
	const familyString = familyArray
		.map(({ family, fontWeight }) => `family=${family.replaceAll(" ", "+")}:wght@${fontWeight}&`)
		.join("&");
	const editor = `@import url('https://fonts.googleapis.com/css2?${familyString}display=swap');`;
	// Frontend fonts array (unique + cleaned).
	const fontList = familyArray.map(({ family, fontWeight }) => `${family}:${fontWeight}`).filter(Boolean);
	const frontend = jsonStringify([...new Set(fontList)]);
	return { editor, frontend };
};

export const getVisibilityCss = ({ uniqueId, hideOnDesktop, hideOnTablet, hideOnMobile }) => {
	// visibility show hide css.
	const toggle = (hidden) => [
		{
			selector: `#${uniqueId}`,
			styles: {
				opacity: hidden ? 0.4 : 1,
				background: hidden
					? `url("${sp_real_localize_data?.pluginUrl}src/Blocks/assets/images/disable-bg-image.svg")`
					: "transparent",
				padding: hidden ? "10px" : "",
			},
		},
	];

	const visibility = {
		Desktop: toggle(hideOnDesktop),
		Tablet: toggle(hideOnTablet),
		Mobile: toggle(hideOnMobile),
	};
	return visibility;
};

export const getModulesSettings = (module_name) => {
	const moduleData = sp_real_localize_data?.dashboardSettings?.modules?.[module_name];
	// Fall back to the shipped defaults, and to false for a module free does not
	// define at all (e.g. Pro-only ones) — an unknown key must not throw.
	return moduleData ? moduleData?.is_active : (defaultDashboardSettings?.modules?.[module_name]?.is_active ?? false);
};

export const checkIsEqual = (updated, old) => {
	// Get sorted keys to ensure consistent comparison.
	const updatedKeys = Object.keys(updated);
	// Compare each value
	return updatedKeys.every((key) => {
		const val1 = updated[key];
		const val2 = old[key];
		return val1 === val2;
	});
};

export const componentSectionHeader = (label) => (
	<span className="sp-real-component-title sp-real-component-mb bold">{label}</span>
);

export const checkIsActiveCardItem = (items, itemName) => {
	const item = items?.find((i) => i.name === itemName);
	return item?.is_active;
};

export const isActiveBlock = (blockName) => {
	return sp_real_localize_data?.activeBlockList?.includes(blockName);
};
