import { useEffect, useMemo } from "@wordpress/element";
import { useBlockProps } from "@wordpress/block-editor";
import { useUniqueId } from "@testimonial/hooks";
import { InspectorControl } from "@testimonial/components";
import { TogglePanelBodyProvider } from "@testimonial/context";
import { fontFamilyToUrlGenerator, getRealBlockProps } from "@testimonial/controls";
import Inspector from "./Inspector";
import Render from "./Render";
import dynamicCss from "./dynamicCss";
import { TestimonialSubmissionFormPreviewImage } from "./icon";

const Edit = ({ clientId, attributes, setAttributes }) => {
	const {
		uniqueId,
		customClassName,
		customIdName,
		labelTypography,
		noteTypography,
		placeholderTypography,
		submitButtonTypography,
		fontLists,
	} = attributes;

	useUniqueId(clientId, uniqueId, setAttributes);

	const blockProps = getRealBlockProps(
		useBlockProps(),
		customIdName,
		`sp-real-tsf-editor${customClassName ? ` ${customClassName}` : ""}`
	);

	const cssString = useMemo(() => dynamicCss(attributes), [attributes]);

	// Fonts: rebuild only when a typography attr actually changes.
	const typographyList = useMemo(
		() => [labelTypography, noteTypography, placeholderTypography, submitButtonTypography],
		[labelTypography, noteTypography, placeholderTypography, submitButtonTypography]
	);
	// { editor: @import CSS, frontend: JSON font list } — built in one pass.
	const googleFonts = useMemo(() => fontFamilyToUrlGenerator(typographyList), [typographyList]);

	// Write only when changed — avoids marking the post dirty on mount.
	useEffect(() => {
		if (googleFonts.frontend !== fontLists) {
			setAttributes({ fontLists: googleFonts.frontend });
		}
	}, [googleFonts, fontLists, setAttributes]);

	if (attributes.isPreview) {
		return <TestimonialSubmissionFormPreviewImage />;
	}

	return (
		<div {...blockProps}>
			<style>{cssString}</style>
			<style>{googleFonts.editor}</style>
			<TogglePanelBodyProvider>
				<InspectorControl attributes={attributes} setAttributes={setAttributes} Inspector={Inspector} />
			</TogglePanelBodyProvider>
			<Render attributes={attributes} setAttributes={setAttributes} />
		</div>
	);
};

export default Edit;
