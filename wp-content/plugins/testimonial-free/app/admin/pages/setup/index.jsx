import { __ } from "@wordpress/i18n";
import BlockVisibility from "../blockVisibility";

const BlocksSetup = () => (
	<div className="sp-real-setup-blocks-page">
		<div className="sp-real-setup-blocks-page-header sp-d-flex sp-align-center sp-justify-center">
			<div className="header-left sp-text-center">
				<h3 className="sp-real-setup-page-title">{__("Enable the Blocks You Need", "testimonial-free")}</h3>
				<p className="sp-real-setup-page-desc">
					{__(
						"Turn on the blocks that match your workflow. You can update your selection anytime.",
						"testimonial-free"
					)}
				</p>
			</div>
		</div>
		<BlockVisibility showHeading={false} />
	</div>
);

export default BlocksSetup;
