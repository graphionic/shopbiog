import Toggle from "react-toggle";
import { memo } from "@wordpress/element";
import { generatePatternLibraryLink, testimonialBlocksInfo } from "@testimonial/constants";
import { Demos, Docs } from "../Icons";

const ToggleCard = ({ attributes, blockShowHideHandler, adminUrl, isPro = false }) => {
	const { show, name } = attributes;
	// return if block info don't exist.
	if (!testimonialBlocksInfo[name]) {
		return;
	}
	const { Icon, patternSlug, docLink, title } = testimonialBlocksInfo[name];
	// Only blocks the pattern catalog actually covers carry a `patternSlug`, so
	// child/shortcode blocks never link to an empty library. Deliberately not
	// gated on the `pattern_library` module — the deep link opens the library
	// either way (see maybeAutoOpen() in ready-patterns/ToolbarButton.jsx).
	const showDemoLink = patternSlug && adminUrl;

	return (
		<div className={`sp-real-visibility-setting-card sp-d-flex sp-justify-between sp-align-center`}>
			{isPro && (
				<div className="sp-real-pro-blocks-badge sp-d-flex sp-align-center sp-gap-2px">
					<svg width={12} height={12} viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="m1.966 7.867-.56-4.449c-.041-.328.265-.553.483-.353l1.744 1.599c.192.176.464.118.597-.125l1.452-2.662c.154-.282.492-.282.645 0L7.78 4.539c.132.243.405.3.597.125l1.744-1.599c.218-.2.524.025.482.353l-.56 4.449zm7.656 2.468H2.387c-.232 0-.42-.23-.42-.515V8.69h8.076v1.13c0 .284-.188.515-.42.515"
							fill="#4ab866"
						/>
					</svg>
					<span>Pro</span>
				</div>
			)}
			<div className="sp-real-visibility-setting-card-info sp-d-flex sp-align-center">
				<div className="sp-real-visibility-setting-card-icon sp-d-flex sp-align-center">
					<Icon />
				</div>
				<div className="sp-real-visibility-setting-card-docs">
					<h4 className="sp-real-visibility-card-label sp-d-flex sp-align-center sp-gap-4px">
						<span className="sp-real-visibility-block-name">{title}</span>
					</h4>
					<ul className="sp-d-flex sp-align-center sp-gap-10px">
						{docLink && (
							<li className="sp-real-doc-link">
								<a
									className="sp-d-flex sp-align-center"
									href={docLink}
									target="_blank"
									rel="noreferrer"
								>
									<Docs /> Docs
								</a>
							</li>
						)}
						{showDemoLink && (
							<li className="sp-real-demo-link">
								<a
									className="sp-d-flex sp-align-center"
									href={generatePatternLibraryLink(adminUrl, patternSlug)}
									target="_blank"
									rel="noreferrer"
								>
									<Demos /> Demo
								</a>
							</li>
						)}
					</ul>
				</div>
			</div>
			<div className={`sp-real-visibility-setting-toggle${isPro ? " sp-real-pro-blocks-toggle" : ""}`}>
				<Toggle
					icons={false}
					defaultChecked={isPro ? false : show}
					onChange={() => {
						if (!isPro) {
							blockShowHideHandler(name);
						}
					}}
				/>
			</div>
		</div>
	);
};

export default memo(ToggleCard);
