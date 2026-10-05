import {
	boxShadowCss,
	unit,
	spRealBgControl,
	generateBorderStyles,
	spRealBackgroundImgCss,
	filterResponsiveDynamicCss,
	generateTypographyCss,
	generateTypoResponsive,
	checkIsActiveCardItem,
	getVisibilityCss,
	inArray,
	singleResponsiveCss,
	spacingCss,
	responsiveSpacingCss,
	rangeControlCss,
} from "@testimonial/controls";

const responsiveCssFn = (attributes, device = "Desktop") => {
	const {
		blockName,
		uniqueId,
		columnGap,
		cardDesign,
		cardPadding,
		imageWidth,
		imageHeight,
		aspectRatio,
		aspectRatioCustom,
		ratingIconSize,
		ratingIconGap,
		titleMargin,
		excerptMargin,
		nameMargin,
		designationMargin,
		imageMargin,
		ratingIconMargin,
		cardContents,
		// Navigation arrows responsive.
		enableNavigationArrow,
		navIconSize,
		navIconGap,
		navIconPadding,
		navIconPosition,
		navOffsetX,
		navOffsetY,
		// Pagination dots responsive.
		enablePaginationDots,
		paginationDotsWidth,
		paginationDotsHeight,
		paginationDotsSpaceBetween,
		paginationDotsMargin,
		realPadding,
		realMargin,
	} = attributes;

	const uniqueIdClass = uniqueId ? `#${uniqueId}` : "";

	let responsiveCss = [
		// main block css.
		{
			selector: `${uniqueIdClass} .sp-real-template-wrapper`,
			styles: {
				padding: responsiveSpacingCss(realPadding, device),
				margin: responsiveSpacingCss(realMargin, device),
			},
		},
		// Gap between items (columns gap). Marquee uses per-item margin (via the
		// so we only emit a flex gap for the swiper case.
		...(blockName === "carousel"
			? [
					{
						selector: `${uniqueIdClass} .sp-swiper-wrapper`,
						styles: {
							gap: singleResponsiveCss(columnGap, device),
						},
					},
					{
						selector: `${uniqueIdClass} .sp-testimonial-swiper:is(.swiper-fade, .swiper-cube, .swiper-flip) .swiper-slide`,
						styles: {
							display: "grid",
							"grid-template-columns": `repeat(${Number(attributes?.columns?.device?.[device]) || 1}, minmax(0, 1fr))`,
							gap: singleResponsiveCss(columnGap, device),
						},
					},
				]
			: []),
		// Grid.
		blockName === "grid" && {
			selector: `${uniqueIdClass} .sp-real-grid-wrapper`,
			styles: {
				display: "grid",
				"grid-template-columns": `repeat(${Number(attributes?.columns?.device?.[device]) || 3}, minmax(0, 1fr))`,
				"column-gap": singleResponsiveCss(columnGap, device),
				"row-gap": singleResponsiveCss(attributes?.rowGap, device),
			},
		},
		// Card padding.
		{
			selector: `${uniqueIdClass} .sp-real-card-inner`,
			styles: {
				padding: responsiveSpacingCss(cardPadding, device),
			},
		},
		// Designation responsive.
		checkIsActiveCardItem(cardContents, "designation") && {
			selector: `${uniqueIdClass} .sp-real-client-designation`,
			styles: {
				...generateTypoResponsive(attributes, device, "designation"),
				margin: responsiveSpacingCss(designationMargin, device),
			},
		},
	];

	// Title Responsive Css.
	if (checkIsActiveCardItem(cardContents, "testimonial_title")) {
		responsiveCss = [
			...responsiveCss,
			{
				selector: `${uniqueIdClass} .sp-real-client-title`,
				styles: generateTypoResponsive(attributes, device, "title"),
			},
			{
				selector: `${uniqueIdClass} .sp-real-testimonial-client-title`,
				styles: {
					margin: responsiveSpacingCss(titleMargin, device),
				},
			},
		];
	}

	// Excerpt Responsive Css.
	if (checkIsActiveCardItem(cardContents, "testimonial_text")) {
		responsiveCss = [
			...responsiveCss,
			{
				selector: `${uniqueIdClass} .sp-real-testimonial-text`,
				styles: generateTypoResponsive(attributes, device, "excerpt"),
			},
			{
				selector: `${uniqueIdClass} .sp-real-testimonial-content`,
				styles: {
					margin: responsiveSpacingCss(excerptMargin, device),
				},
			},
		];
	}

	// Rating Responsive Css.
	if (checkIsActiveCardItem(cardContents, "rating")) {
		responsiveCss = [
			...responsiveCss,
			{
				selector: `${uniqueIdClass} .sp-real-client-rating`,
				styles: {
					"font-size": singleResponsiveCss(ratingIconSize, device),
					gap: singleResponsiveCss(ratingIconGap, device),
					margin: responsiveSpacingCss(ratingIconMargin, device),
				},
			},
		];
	}

	// Resolve aspect ratio: "custom" pulls from the free-text value. Accepts
	// "16:9" or "16/9"; "original" (or empty custom) disables the ratio.
	const rawRatio = aspectRatio === "custom" ? aspectRatioCustom : aspectRatio;
	const hasAspectRatio = rawRatio && rawRatio !== "original";
	const aspectRatioCss = hasAspectRatio ? rawRatio.replace(":", " / ") : "";

	// Image Responsive Css.
	if (checkIsActiveCardItem(cardContents, "reviewer_image")) {
		responsiveCss = [
			...responsiveCss,
			{
				selector: `${uniqueIdClass} .sp-real-img-tag`,
				styles: {
					width: singleResponsiveCss(imageWidth, device),
					height: hasAspectRatio ? "auto" : singleResponsiveCss(imageHeight, device),
					"aspect-ratio": aspectRatioCss,
					"object-fit": hasAspectRatio ? "cover" : "",
				},
			},
			{
				selector: `${uniqueIdClass} .sp-real-client-image`,
				styles: {
					margin: responsiveSpacingCss(imageMargin, device),
				},
			},
		];

		// card two image position adjustment based on image height/width for all responsive views.
		if (cardDesign === "design-two") {
			const imageHalfHeight = parseInt(imageWidth?.device?.[device] || 0) / 2 + unit(imageWidth, device);

			responsiveCss = [
				...responsiveCss,
				{
					selector: `${uniqueIdClass} .sp-real-client-image`,
					styles: {
						top: "-" + imageHalfHeight,
					},
				},
				{
					selector: `${uniqueIdClass} .sp-real-card-design-two`,
					styles: {
						"margin-top": imageHalfHeight,
					},
				},
				{
					selector: `${uniqueIdClass} .sp-real-card-design-two .sp-real-card-inner>div:nth-child(2)`,
					styles: {
						"padding-top": imageHalfHeight,
					},
				},
			];
		}
	}

	// Name Responsive Css.
	if (checkIsActiveCardItem(cardContents, "reviewer_name")) {
		responsiveCss = [
			...responsiveCss,
			{
				selector: `${uniqueIdClass} .sp-real-client-name`,
				styles: generateTypoResponsive(attributes, device, "name"),
			},
			{
				selector: `${uniqueIdClass} .sp-real-testimonial-client-name`,
				styles: {
					margin: responsiveSpacingCss(nameMargin, device),
				},
			},
		];
	}

	// Navigation Arrows Responsive Css.
	if (enableNavigationArrow) {
		responsiveCss = [
			...responsiveCss,
			{
				selector: `${uniqueIdClass} .sp-real-swiper-nav-arrows`,
				styles: {
					gap: singleResponsiveCss(navIconGap, device),
					right: singleResponsiveCss(navOffsetX, device),
					left: singleResponsiveCss(navOffsetX, device),
					[inArray(["bottom_left", "bottom_center", "bottom_right"], navIconPosition) ? "bottom" : "top"]:
						singleResponsiveCss(navOffsetY, device),
				},
			},
			{
				selector: `${uniqueIdClass} .sp-real-swiper-navigation`,
				styles: {
					padding: responsiveSpacingCss(navIconPadding, device),
				},
			},
			{
				selector: `${uniqueIdClass} .sp-real-swiper-navigation svg`,
				styles: {
					width: singleResponsiveCss(navIconSize, device),
					height: singleResponsiveCss(navIconSize, device),
				},
			},
		];
	}

	// Pagination responsive: margin/gap on container; bullet size only for bullet-based modes.
	if (enablePaginationDots) {
		responsiveCss = [
			...responsiveCss,
			// gaps.
			{
				selector: `${uniqueIdClass} .sp-real-swiper-pagination`,
				styles: {
					margin: responsiveSpacingCss(paginationDotsMargin, device),
					gap: singleResponsiveCss(paginationDotsSpaceBetween, device),
				},
			},
			// dot width.
			{
				selector: `${uniqueIdClass} .swiper-pagination-bullet`,
				styles: {
					width: singleResponsiveCss(paginationDotsWidth, device) + " !important",
					height: singleResponsiveCss(paginationDotsHeight, device),
				},
			},
		];
	}

	return responsiveCss;
};

const dynamicCss = (attributes) => {
	const {
		uniqueId,
		cardBackground,
		cardBorder,
		cardBorderWidth,
		cardBorderRadius,
		cardBoxShadow,
		cardBoxShadowHover,
		cardHoverEffect,
		cardHoverTransition,
		titleColors,
		titleTypography,
		excerptColors,
		excerptTypography,
		ratingIconColor,
		ratingIconEmptyColor,
		imageBorder,
		imageBorderRadius,
		imageBorderWidth,
		imageBoxShadow,
		nameColors,
		nameTypography,
		designationColors,
		cardContents,
		hideOnDesktop,
		hideOnTablet,
		hideOnMobile,
		// Navigation arrows.
		enableNavigationArrow,
		navIconVisibleOnHover,
		navIconColors,
		navIconBackground,
		navIconBorder,
		navIconBorderWidth,
		navIconBorderRadius,
		navIconBoxShadow,
		navIconBoxShadowHover,
		// Pagination dots.
		enablePaginationDots,
		paginationDotsColors,
		realBackground,
	} = attributes;

	const uniqueIdClass = uniqueId ? `#${uniqueId}` : "";
	const visibility = getVisibilityCss({ uniqueId, hideOnDesktop, hideOnTablet, hideOnMobile });

	// Desktop css.
	let desktopCss = [
		...visibility?.Desktop,
		...responsiveCssFn(attributes, "Desktop"),
		// main block css.
		{
			selector: `${uniqueIdClass} .sp-real-template-wrapper`,
			styles: {
				background: spRealBgControl(realBackground?.normal),
			},
		},
		// Card background.
		{
			selector: `${uniqueIdClass} .sp-real-card-inner`,
			styles: {
				background: spRealBgControl(cardBackground?.normal),
				...generateBorderStyles(cardBorder, cardBorderWidth),
				"border-radius": spacingCss(cardBorderRadius),
				...boxShadowCss(cardBoxShadow),
				"transition-duration":
					cardHoverEffect && cardHoverEffect !== "none" ? rangeControlCss(cardHoverTransition) : "",
			},
		},
		{
			selector: `${uniqueIdClass} .sp-real-card-inner:hover`,
			styles: {
				"border-color": cardBorder?.hoverColor,
				background: spRealBgControl(cardBackground?.hover),
				...spRealBackgroundImgCss(cardBackground?.hover),
				...boxShadowCss(cardBoxShadowHover),
			},
		},
		// Title.
		checkIsActiveCardItem(cardContents, "testimonial_title") && {
			selector: `${uniqueIdClass} .sp-real-client-title`,
			styles: {
				color: titleColors?.normal || "",
				...generateTypographyCss(titleTypography),
			},
		},
		// Excerpt.
		checkIsActiveCardItem(cardContents, "testimonial_text") && {
			selector: `${uniqueIdClass} .sp-real-testimonial-text`,
			styles: {
				color: excerptColors?.normal || "",
				...generateTypographyCss(excerptTypography),
			},
		},
		// Name.
		checkIsActiveCardItem(cardContents, "reviewer_name") && {
			selector: `${uniqueIdClass} .sp-real-client-name`,
			styles: {
				color: nameColors?.normal || "",
				...generateTypographyCss(nameTypography),
			},
		},
		// Designation color.
		checkIsActiveCardItem(cardContents, "designation") && {
			selector: `${uniqueIdClass} .sp-real-client-designation`,
			styles: {
				color: designationColors?.normal || "",
				...generateTypographyCss(attributes.designationTypography),
			},
		},
	];
	// Rating Css.
	if (checkIsActiveCardItem(cardContents, "rating")) {
		desktopCss = [
			...desktopCss,
			{
				selector: `${uniqueIdClass} .sp-real-rating-full, ${uniqueIdClass} .sp-real-rating-half .sp-real-rating-fill`,
				styles: {
					color: ratingIconColor || "",
				},
			},
			{
				selector: `${uniqueIdClass} .sp-real-rating-empty, ${uniqueIdClass} .sp-real-rating-half .sp-real-rating-base`,
				styles: {
					color: ratingIconEmptyColor || "",
				},
			},
		];
	}
	// Image Css.
	if (checkIsActiveCardItem(cardContents, "reviewer_image")) {
		desktopCss = [
			...desktopCss,
			{
				selector: `${uniqueIdClass} .sp-real-img-tag`,
				styles: {
					...generateBorderStyles(imageBorder, imageBorderWidth),
					"border-radius": spacingCss(imageBorderRadius),
					...boxShadowCss(imageBoxShadow),
				},
			},
			{
				selector: `${uniqueIdClass} .sp-real-client-image:hover .sp-real-img-tag`,
				styles: {
					"border-color": imageBorder?.hoverColor,
				},
			},
		];
	}

	// Navigation Arrows Css.
	if (enableNavigationArrow) {
		desktopCss = [
			...desktopCss,
			// Nav buttons base styles.
			{
				selector: `${uniqueIdClass} .sp-real-swiper-navigation`,
				styles: {
					color: navIconColors?.normal || "",
					background: navIconBackground?.normal || "",
					...generateBorderStyles(navIconBorder, navIconBorderWidth),
					"border-radius": spacingCss(navIconBorderRadius),
					...boxShadowCss(navIconBoxShadow),
					"pointer-events": "auto",
					...(navIconVisibleOnHover && {
						opacity: "0",
						transition: "opacity 0.3s ease",
					}),
				},
			},
			{
				selector: `${uniqueIdClass} .sp-real-swiper-navigation:hover`,
				styles: {
					color: navIconColors?.hover || "",
					background: navIconBackground?.hover || "",
					"border-color": navIconBorder?.hoverColor || "",
					...boxShadowCss(navIconBoxShadowHover),
				},
			},
			navIconVisibleOnHover && {
				selector: `${uniqueIdClass}:hover .sp-real-swiper-navigation`,
				styles: { opacity: "1" },
			},
		];
	}

	// Pagination: colors for dots/stepper on top of swiper.scss base.
	if (enablePaginationDots) {
		desktopCss = [
			...desktopCss,
			{
				selector: `${uniqueIdClass} .swiper-pagination-bullet`,
				styles: {
					background: paginationDotsColors?.normal || "",
				},
			},
			{
				selector: `${uniqueIdClass} :is(.swiper-pagination-bullet:hover, .swiper-pagination-bullet-active)`,
				styles: {
					background: paginationDotsColors?.active || "",
				},
			},
		];
	}

	const tabletCss = [...visibility?.Tablet, ...responsiveCssFn(attributes, "Tablet")];
	const mobileCss = [...visibility?.Mobile, ...responsiveCssFn(attributes, "Mobile")];

	const cssObj = {
		desktopCss,
		tabletCss,
		mobileCss,
	};

	return filterResponsiveDynamicCss(cssObj);
};

export default dynamicCss;
