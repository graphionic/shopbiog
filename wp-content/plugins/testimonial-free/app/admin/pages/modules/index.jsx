import { __ } from "@wordpress/i18n";
import RenderModuleCard from "./render";
import { freeModules, proModules } from "./config";
import { ProIconFly } from "./Icons";
import { pricingPageUrl } from "@testimonial/constants";

const Modules = () => {
	return (
		<div className="sp-real-modules-page">
			<div className="sp-real-dashboard-page-header sp-d-flex sp-flex-col sp-gap-10px">
				<span className="sp-real-dashboard-page-header__label">{__("Manage Modules", "testimonial-free")}</span>
				<span className="sp-real-dashboard-page-header__desc">
					{__("Enable only the modules you need to keep your site fast and optimized.", "testimonial-free")}
				</span>
			</div>
			<RenderModuleCard items={freeModules} optionKey="modules" />
			<div className="sp-real-dashboard-page-header sp-d-flex sp-align-center sp-justify-between sp-real-pro-modules">
				<div className="sp-d-flex sp-flex-col sp-gap-10px">
					<span className="sp-real-dashboard-page-header__label">
						{__("Unlock Powerful Features with Pro!", "testimonial-free")}
					</span>
					<span className="sp-real-dashboard-page-header__desc">
						{__(
							"Upgrade now to access advanced features that build trust and turn visitors into customers.",
							"testimonial-free"
						)}
					</span>
				</div>
				<div className="sp-real-modules-pro-btn">
					<a href={pricingPageUrl} target="_blank" rel="noreferrer" className="sp-real-upgrade-to-pro-btn">
						<ProIconFly />
						Upgrade to Pro
					</a>
				</div>
			</div>
			<RenderModuleCard items={proModules} optionKey="modules" />
		</div>
	);
};

export default Modules;
