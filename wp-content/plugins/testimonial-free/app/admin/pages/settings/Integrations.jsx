import { __ } from "@wordpress/i18n";
import RenderModuleCard from "../modules/render";
import {
	BeaverIntegrationIcon,
	BricksIntegrationIcon,
	DiviIntegrationIcon,
	ElementorIntegrationIcon,
	OxygenIntegrationIcon,
	WPBakeryIntegrationIcon,
} from "../integrations/icons";
import { generateDocLink } from "@testimonial/constants";

const integrationItems = [
	{
		id: 1,
		key: "elementor",
		label: "Elementor",
		Icon: ElementorIntegrationIcon,
		desc: __(
			"This addon lets you use Real Testimonials Gutenberg blocks in Elementor by generating reusable shortcodes from saved templates.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/integrations#elementor"),
	},
	{
		id: 2,
		key: "divi",
		label: "Divi",
		Icon: DiviIntegrationIcon,
		desc: __(
			"This addon lets you use Real Testimonials Gutenberg blocks in Divi by generating reusable shortcodes from saved templates.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/integrations#divi"),
	},
	{
		id: 3,
		key: "wpbakery",
		label: "WPBakery",
		Icon: WPBakeryIntegrationIcon,
		desc: __(
			"This addon lets you use Real Testimonials Gutenberg blocks in WPBakery by generating reusable shortcodes from saved templates.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/integrations#wpbakery"),
	},
	{
		id: 4,
		key: "oxygen",
		label: "Oxygen",
		Icon: OxygenIntegrationIcon,
		desc: __(
			"This addon lets you use Real Testimonials Gutenberg blocks in Oxygen by generating reusable shortcodes from saved templates.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/integrations#oxygen"),
	},
	{
		id: 5,
		key: "beaver",
		label: "Beaver Builder",
		Icon: BeaverIntegrationIcon,
		desc: __(
			"This addon lets you use Real Testimonials Gutenberg blocks in Beaver by generating reusable shortcodes from saved templates.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/integrations#beaver-builder"),
	},
	{
		id: 6,
		key: "bricks",
		label: "Bricks",
		Icon: BricksIntegrationIcon,
		desc: __(
			"This addon lets you use Real Testimonials Gutenberg blocks in Bricks by generating reusable shortcodes from saved templates.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/integrations#bricks"),
	},
];

const Integrations = () => (
	<div className="sp-real-integrations-page">
		<div className="sp-real-dashboard-page-header sp-d-flex sp-flex-col sp-gap-10px">
			<span className="sp-real-dashboard-page-header__label">
				{__("Manage Integrations", "testimonial-free")}
			</span>
			<span className="sp-real-dashboard-page-header__desc">
				{__("Enable only the Integrations you need to keep your site fast and optimized.", "testimonial-free")}
			</span>
		</div>
		<RenderModuleCard items={integrationItems} optionKey="integrations" />
	</div>
);

export default Integrations;
