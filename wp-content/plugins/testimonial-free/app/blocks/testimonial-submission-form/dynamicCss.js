import {
	filterResponsiveDynamicCss,
	generateTypographyCss,
	generateTypoResponsive,
	boxShadowCss,
	spRealBgControl,
	generateBorderStyles,
	getVisibilityCss,
	singleResponsiveCss,
	spacingCss,
	responsiveSpacingCss,
} from "@testimonial/controls";

const responsiveCss = (attributes, deviceType = "Desktop") => {
	const {
		uniqueId,
		inputStyle,
		maxWidth,
		fieldsGap,
		fieldPadding,
		submitButtonPadding,
		submitButtonMargin,
		formPadding,
		formMargin,
		labelToFieldGap,
		fieldGapToNoteGap,
	} = attributes;

	const uniqueIdSel = uniqueId ? `#${uniqueId}` : "";

	const css = [
		// Outer wrap max width.
		{
			selector: `${uniqueIdSel}.sp-real-testimonial-submission-form`,
			styles: {
				"max-width": singleResponsiveCss(maxWidth, deviceType),
			},
		},
		// Form padding / margin.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-form`,
			styles: {
				padding: responsiveSpacingCss(formPadding, deviceType),
				margin: responsiveSpacingCss(formMargin, deviceType),
			},
		},
		// Fields container gap.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-fields`,
			styles: {
				gap: singleResponsiveCss(fieldsGap, deviceType),
			},
		},
		// Label typography.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__label`,
			styles: {
				...generateTypoResponsive(attributes, deviceType, "label"),
			},
		},
		// Input filed gap.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field-label-section`,
			styles: {
				[inputStyle === "style-two" ? "margin-right" : "margin-bottom"]: singleResponsiveCss(
					labelToFieldGap,
					deviceType
				),
			},
		},
		// note text.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__note`,
			styles: {
				...generateTypoResponsive(attributes, deviceType, "note"),
				"margin-top": singleResponsiveCss(fieldGapToNoteGap, deviceType),
			},
		},
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__note.sp-tsf-rating-note`,
			styles: {
				"margin-top": 0,
				"margin-bottom": singleResponsiveCss(fieldGapToNoteGap, deviceType),
			},
		},
		// Input typography + padding.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__input`,
			styles: {
				...generateTypoResponsive(attributes, deviceType, "placeholder"),
				padding: responsiveSpacingCss(fieldPadding, deviceType),
			},
		},
		// Submit button typography + padding + margin.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-submit`,
			styles: {
				...generateTypoResponsive(attributes, deviceType, "submitButton"),
				padding: responsiveSpacingCss(submitButtonPadding, deviceType),
				margin: responsiveSpacingCss(submitButtonMargin, deviceType),
			},
		},
	];

	return css;
};

const dynamicCss = (attributes) => {
	const {
		uniqueId,
		labelTypography,
		labelColors,
		noteTypography,
		noteColors,
		requiredColor,
		placeholderColors,
		placeholderTypography,
		fieldBackgroundColors,
		fieldBorder,
		fieldBorderWidth,
		fieldBorderRadius,
		submitButtonTypography,
		submitButtonColors,
		submitButtonBackground,
		submitButtonBorder,
		submitButtonBorderWidth,
		submitButtonBorderRadius,
		successMessageColor,
		errorMessageColor,
		formBackground,
		formBorder,
		formBorderWidth,
		formBorderRadius,
		formBoxShadow,
	} = attributes;

	const uniqueIdSel = uniqueId ? `#${uniqueId}` : "";
	const visibility = getVisibilityCss(attributes);

	const desktopCss = [
		...visibility?.Desktop,
		...responsiveCss(attributes, "Desktop"),
		// Form container.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-form`,
			styles: {
				background: spRealBgControl(formBackground),
				...generateBorderStyles(formBorder, formBorderWidth),
				"border-radius": spacingCss(formBorderRadius),
				...boxShadowCss(formBoxShadow),
			},
		},
		// Label.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__label`,
			styles: {
				color: labelColors?.normal,
				...generateTypographyCss(labelTypography),
			},
		},
		// note.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__note`,
			styles: {
				color: noteColors?.normal,
				...generateTypographyCss(noteTypography),
			},
		},
		// Input.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__input`,
			styles: {
				color: placeholderColors?.normal,
				"background-color": fieldBackgroundColors?.normal,
				...generateTypographyCss(placeholderTypography),
				...generateBorderStyles(fieldBorder, fieldBorderWidth),
				"border-radius": spacingCss(fieldBorderRadius),
			},
		},
		// Placeholder color.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__input::placeholder`,
			styles: {
				color: placeholderColors?.normal,
			},
		},
		// Focus state.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-field__input:focus`,
			styles: {
				color: placeholderColors?.hover,
				"background-color": fieldBackgroundColors?.hover,
				"border-color": fieldBorder?.hoverColor,
			},
		},
		// Required asterisk.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-required`,
			styles: {
				color: requiredColor,
			},
		},
		// Submit button base.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-submit`,
			styles: {
				color: submitButtonColors?.normal,
				background: spRealBgControl(submitButtonBackground?.normal),
				...generateTypographyCss(submitButtonTypography),
				...generateBorderStyles(submitButtonBorder, submitButtonBorderWidth),
				"border-radius": spacingCss(submitButtonBorderRadius),
			},
		},
		// Submit button hover.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-submit:hover`,
			styles: {
				color: submitButtonColors?.hover,
				"border-color": submitButtonBorder?.hoverColor,
				background: spRealBgControl(submitButtonBackground?.hover),
			},
		},
		// Message colors.
		{
			selector: `${uniqueIdSel} .sp-real-tsf-message__success`,
			styles: { color: successMessageColor },
		},
		{
			selector: `${uniqueIdSel} .sp-real-tsf-message__error`,
			styles: { color: errorMessageColor },
		},
	];

	const tabletCss = [...visibility?.Tablet, ...responsiveCss(attributes, "Tablet")];
	const mobileCss = [...visibility?.Mobile, ...responsiveCss(attributes, "Mobile")];

	const cssObj = {
		desktopCss,
		tabletCss,
		mobileCss,
	};

	return filterResponsiveDynamicCss(cssObj);
};

export default dynamicCss;
