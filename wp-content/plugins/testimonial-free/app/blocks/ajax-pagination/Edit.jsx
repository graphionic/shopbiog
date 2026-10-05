import { useBlockProps } from "@wordpress/block-editor";
import { useEffect, useMemo } from "@wordpress/element";
import { InspectorControl } from "@testimonial/components";
import { TogglePanelBodyProvider } from "@testimonial/context";
import { useUniqueId } from "@testimonial/hooks";
import { fontFamilyToUrlGenerator, getRealBlockProps } from "@testimonial/controls";
import Pagination from "./Pagination";
import dynamicCss from "./dynamicCss";
import Inspector from "./Inspector";

const AjaxPaginationEdit = ({ clientId, attributes, setAttributes }) => {
	const { uniqueId, customClassName, paginationTypography, fontLists } = attributes;

	useUniqueId(clientId, uniqueId, setAttributes);

	const cssString = useMemo(() => dynamicCss(attributes), [attributes]);

	const blockProps = getRealBlockProps(
		useBlockProps(),
		uniqueId,
		`sp-real-ajax-pagination ${customClassName ? ` ${customClassName}` : ""}`
	);

	// Fonts: rebuild only when a typography attr actually changes.
	const typographyList = useMemo(() => [paginationTypography], [paginationTypography]);
	// { editor: @import CSS, frontend: JSON font list } — built in one pass.
	const googleFonts = useMemo(() => fontFamilyToUrlGenerator(typographyList), [typographyList]);

	// Write only when changed — avoids marking the post dirty on mount.
	useEffect(() => {
		if (googleFonts.frontend !== fontLists) {
			setAttributes({ fontLists: googleFonts.frontend });
		}
	}, [googleFonts, fontLists, setAttributes]);

	return (
		<div {...blockProps}>
			<style>{cssString}</style>
			<style>{googleFonts.editor}</style>
			<TogglePanelBodyProvider>
				<InspectorControl attributes={attributes} setAttributes={setAttributes} Inspector={Inspector} />
				<Pagination attributes={attributes} />
			</TogglePanelBodyProvider>
		</div>
	);
};

export default AjaxPaginationEdit;
