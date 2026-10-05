import { useBlockProps } from "@wordpress/block-editor";
import { ProBadgeIcon } from "../../ready-patterns/Icons";
import { generatePatternLibraryLink, pricingPageUrl, testimonialBlocksInfo } from "@testimonial/constants";
import { __ } from "@wordpress/i18n";
import proBlocks from "./config";
import "./pro-blocks-style.scss";

// PREVIEW_IMAGES.
const PREVIEW_IMAGES = proBlocks.reduce((map, { name, previewImage }) => {
	map[name] = previewImage;
	return map;
}, {});

// ProBlockEdit.
const ProBlockEdit = ({ name, attributes }) => {
	const { isPreview } = attributes;
	const blockProps = useBlockProps({ className: "sp-real-pro-block-preview" });
	const PreviewImage = PREVIEW_IMAGES[name];

	if (isPreview && PreviewImage) {
		return (
			<div {...blockProps}>
				<PreviewImage />
			</div>
		);
	}

	const selectedBlock = testimonialBlocksInfo[name];
	// The layout block itself is Pro-only, so "View Demo" opens the Ready Patterns
	// library on this block's patterns — previewable here, insertable with Pro.
	const adminUrl = `${sp_real_localize_data?.homeUrl || ""}wp-admin/`;

	return (
		<div {...blockProps}>
			<div className="sp-real-pro-block-notice sp-d-flex sp-align-center sp-justify-between">
				<p className="sp-real-pro-block-label sp-d-i-flex sp-align-center sp-gap-4px">
					Unlock this premium <span className="sp-real-pro-block-title">{selectedBlock?.title}</span> layout
					block with Pro
				</p>
				<div className="sp-real-pro-blocks-buttons sp-d-flex sp-align-center sp-gap-8px">
					<a
						target="_blank"
						rel="noreferrer"
						href={generatePatternLibraryLink(adminUrl, selectedBlock?.patternSlug)}
						className="sp-real-pro-block-view-demo"
					>
						View Demo
					</a>
					<a
						target="_blank"
						rel="noreferrer"
						href={pricingPageUrl}
						className="sp-real-upgrade-to-pro-btn sp-d-flex sp-align-center sp-gap-8px"
					>
						<ProBadgeIcon color="#fff" />
						{__("Upgrade to Pro Now!", "testimonial-free")}
					</a>
				</div>
			</div>
		</div>
	);
};

export default ProBlockEdit;
