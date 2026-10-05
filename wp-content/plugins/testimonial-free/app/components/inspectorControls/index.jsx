import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/block-editor";
import { testimonialBlocksInfo } from "@testimonial/constants";
import { onOpenSinglePatternPopup } from "../../ready-patterns/Controls";
import "./editor.scss";

const InspectorControl = ({ attributes, setAttributes, Inspector }) => {
	const { blockName } = attributes;
	const blockInfo = testimonialBlocksInfo[`sp-testimonial-pro/${blockName}`];
	const isShowReadyPatternButton = blockInfo?.patternSlug;

	return (
		<InspectorControls>
			<div className="sp-testimonial-tabs-panel">
				<div className="sp-real-inspector-control-top-section">
					{blockInfo?.docLink && (
						<div className="sp-real-doc-link-button-wrapper">
							<a
								className="sp-real-doc-link-button sp-d-flex sp-align-center sp-gap-2px"
								href={blockInfo?.docLink}
								target="_blank"
								rel="noreferrer"
							>
								Documentation
							</a>
						</div>
					)}
					{isShowReadyPatternButton && (
						<div className="sp-real-inspector-control-button-list sp-d-flex sp-align-center sp-justify-between">
							<button
								data-block={blockName}
								className="sp-real-block-preview-button sp-real-ready-patterns sp-d-flex sp-align-center sp-justify-center sp-w-full"
								onClick={(event) => onOpenSinglePatternPopup(event, blockName)}
							>
								{__("Start with Ready Patterns", "testimonial-free")}
							</button>
						</div>
					)}
				</div>
				<Inspector attributes={attributes} setAttributes={setAttributes} />
			</div>
		</InspectorControls>
	);
};

export default InspectorControl;
