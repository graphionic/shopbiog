import {
	filterResponsiveDynamicCss,
	generateTypographyCss,
	generateTypoResponsive,
	generateBorderStyles,
	getVisibilityCss,
	spacingCss,
	responsiveSpacingCss,
	rangeControlCss,
} from "@testimonial/controls";

const responsiveCss = (attributes, deviceType) => {
	const { uniqueId, paginationPadding, paginationMargin } = attributes;
	const uid = uniqueId ? `#${uniqueId}` : "";

	return [
		{
			selector: `${uid} .sp-real-pagination-item`,
			styles: {
				...generateTypoResponsive(attributes, deviceType, "pagination"),
				padding: responsiveSpacingCss(paginationPadding, deviceType),
			},
		},
		{
			selector: `${uid} .sp-real-pagination-wrapper`,
			styles: {
				margin: responsiveSpacingCss(paginationMargin, deviceType),
			},
		},
	];
};

const dynamicCss = (attributes) => {
	const {
		uniqueId,
		paginationNumberGap,
		paginationTypography,
		paginationColor,
		paginationBgColor,
		paginationButtonType,
		paginationBorder,
		paginationBorderWidth,
		paginationBorderRadius,
		hideOnDesktop,
		hideOnTablet,
		hideOnMobile,
	} = attributes;
	const uid = uniqueId ? `#${uniqueId}` : "";

	const visibility = getVisibilityCss({ uniqueId, hideOnDesktop, hideOnTablet, hideOnMobile });

	const desktopCss = [
		...visibility?.Desktop,
		...responsiveCss(attributes, "Desktop"),
		{
			selector: `${uid} .sp-real-pagination-item`,
			styles: {
				color: paginationColor?.normal || "",
				"background-color": paginationBgColor?.normal || "",
				...generateTypographyCss(paginationTypography),
				...generateBorderStyles(paginationBorder, paginationBorderWidth),
				"border-radius": spacingCss(paginationBorderRadius),
			},
		},
		{
			selector: `${uid} .sp-real-pagination-item:where(:hover, .current)`,
			styles: {
				color: paginationColor?.hover || "",
				"background-color": paginationBgColor?.hover || "",
				"border-color": paginationBorder?.hoverColor || "",
			},
		},
		paginationButtonType === "number" && {
			selector: `${uid} .sp-real-pagination-buttons`,
			styles: {
				gap: rangeControlCss(paginationNumberGap),
			},
		},
	];

	const tabletCss = [...visibility?.Tablet, ...responsiveCss(attributes, "Tablet")];
	const mobileCss = [...visibility?.Mobile, ...responsiveCss(attributes, "Mobile")];

	return filterResponsiveDynamicCss({ desktopCss, tabletCss, mobileCss });
};

export default dynamicCss;
