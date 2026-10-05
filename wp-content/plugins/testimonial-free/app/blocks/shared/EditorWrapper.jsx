import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { InspectorControl } from "@testimonial/components";
import { useUniqueId, useRealChildBlocks, useApiData } from "@testimonial/hooks";
import { fontFamilyToUrlGenerator, getRealBlockProps, inArray } from "@testimonial/controls";
import { CardDesignPicker } from "@testimonial/templates";
import { memo, useEffect, useMemo } from "@wordpress/element";
import { TogglePanelBodyProvider } from "../../context";
import dynamicCss from "./dynamicCss";
import Inspector from "./Inspector";
import CarouselRender from "./CarouselRender";
import GridRender from "./GridRender";
import { CarouselBlockPreviewImage } from "../carousel/icon";
import { SliderBlockPreviewImage } from "../slider/icon";
import { GridBlockPreviewImage } from "../grid/icon";

const EditorContent = ({ clientId, attributes, setAttributes }) => {
	const {
		uniqueId,
		customIdName,
		customClassName,
		cardDesign,
		blockName,
		template,
		titleTypography,
		excerptTypography,
		nameTypography,
		designationTypography,
		fontLists,
	} = attributes;
	const { posts, postsStatus, message, isDemoTestimonials } = useApiData(attributes);

	// Generate unique ID.
	useUniqueId(clientId, uniqueId, setAttributes);

	// Wrap with testimonial-group + manage addon child blocks (skips when no card design).
	useRealChildBlocks(clientId, attributes);

	const blockProps = getRealBlockProps(useBlockProps(), customIdName, customClassName);

	const cssString = useMemo(() => dynamicCss(attributes), [attributes]);

	const layout = blockName;

	// Fonts: rebuild only when a typography attr actually changes.
	const typographyList = useMemo(
		() => [titleTypography, excerptTypography, nameTypography, designationTypography],
		[titleTypography, excerptTypography, nameTypography, designationTypography]
	);

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
			{cardDesign ? (
				<TogglePanelBodyProvider>
					<>
						<InspectorControl attributes={attributes} setAttributes={setAttributes} Inspector={Inspector} />
						{/* Demo testimonials notice - editor only */}
						{isDemoTestimonials && (
							<div className="sp-real-demo-testimonials-notice">
								{__(
									"You're viewing sample testimonials to preview the card design and layout.",
									"testimonial-free"
								)}{" "}
								<a
									href={`${sp_real_localize_data?.homeUrl}wp-admin/post-new.php?post_type=spt_testimonial`}
									target="_blank"
									rel="noopener noreferrer"
								>
									{__("Add Your Testimonials", "testimonial-free")}
								</a>{" "}
								{__("to display real customer feedback and build trust.", "testimonial-free")}
							</div>
						)}
						<div id={uniqueId} className={`sp-real-${blockName}`}>
							{!postsStatus && !message && posts?.length > 0 && (
								<div className={`sp-real-${blockName}-${template} sp-real-template-wrapper`}>
									{/* Carousel Layout */}
									{inArray(["carousel", "slider"], layout) && (
										<CarouselRender attributes={attributes} layout={layout} testimonials={posts} />
									)}
									{/* Grid Layout */}
									{!inArray(["carousel", "slider"], layout) && (
										<GridRender attributes={attributes} layout={layout} testimonials={posts} />
									)}
								</div>
							)}
							{postsStatus ? (
								<div className="sp-real-loading">Loading testimonials...</div>
							) : (
								<>
									{message && <div className="sp-real-error">{message}</div>}
									{posts.length === 0 && (
										<div className="sp-real-no-testimonials">No testimonials found.</div>
									)}
								</>
							)}
						</div>
					</>
				</TogglePanelBodyProvider>
			) : (
				<CardDesignPicker
					clientId={clientId}
					cardDesign={cardDesign}
					blockName={blockName}
					attributes={attributes}
					setAttributes={setAttributes}
				/>
			)}
		</div>
	);
};

// Static inserter-preview image per layout block, keyed by `blockName`. Rendered
// when block.json `example.attributes.isPreview` is set, so the inserter shows an
// image instead of mounting the live editor (which would fire the data hooks).
const PREVIEW_IMAGES = {
	carousel: CarouselBlockPreviewImage,
	slider: SliderBlockPreviewImage,
	grid: GridBlockPreviewImage,
};
// Guard: keep the inserter preview free of hooks. On `isPreview` render the
// mapped static image; otherwise mount the live editor. The hook-bearing body
// lives in EditorContent so its hooks always run unconditionally.
const EditorWrapper = (props) => {
	if (props.attributes?.isPreview) {
		const PreviewImage = PREVIEW_IMAGES[props.attributes.blockName];
		return PreviewImage ? <PreviewImage /> : null;
	}
	return <EditorContent {...props} />;
};

export default memo(EditorWrapper);
