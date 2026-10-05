import { Arrow } from "../../Icons";
import { __ } from "@wordpress/i18n";
import { useSelect } from "@wordpress/data";
import { STORE_NAME } from "../../store/constants";
import {
	SmartPostIcon,
	SmartTabsIcon,
	RenoProductGalleryIcon,
	WPCarouselIcon,
	LogoShowcaseIcon,
	EasyAccordionIcon,
	RenoProductSliderIcon,
	RenoProductCategoryIcon,
	SmartSwatch,
	SmartTeam,
	SmartBrand,
	LocationWeatherIcon,
} from "./Icon";

// ArrowRight component for plugin cards
const ArrowRight = () => (
	<svg width={12} height={12} viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="m2 10 8-8m0 6.153V2H3.846"
			stroke="currentColor"
			strokeWidth={1.2}
			strokeLinecap="round"
			strokeLinejoin="round"
		/>
	</svg>
);

// Plugin data for showcase - matching easy accordion design
const morePlugins = [
	{
		name: "WP Carousel",
		description:
			"The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.",
		url: "https://wpcarousel.io/",
		icon: <WPCarouselIcon />,
	},
	{
		name: "Easy Accordion",
		description: "The most powerful Accordion and FAQs builder plugin for WordPress with modern design.",
		url: "https://easyaccordion.io/",
		icon: <EasyAccordionIcon />,
	},
	{
		name: "Smart Tabs",
		description: "Best WooCommerce Custom Product Tabs & WordPress Tabs Builder Plugin to create responsive tabs.",
		url: "https://wptabs.com/",
		icon: <SmartTabsIcon />,
	},
	{
		name: "Smart Post",
		description:
			"Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.",
		url: "https://wpsmartpost.com/",
		icon: <SmartPostIcon />,
	},
	{
		name: "Smart Team",
		description: "Simply collect, manage, and display team members on your website with beautiful layouts.",
		url: "https://getwpteam.com/",
		icon: <SmartTeam />,
	},
	{
		name: "Logo Carousel",
		description:
			"Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.",
		url: "https://logocarousel.com/",
		icon: <LogoShowcaseIcon />,
	},
	{
		name: "Location Weather",
		description:
			"Display beautiful weather update widgets to your WordPress site in a minute without coding skills!",
		url: "https://locationweather.io/",
		icon: <LocationWeatherIcon />,
	},
	{
		name: "Reno Product Gallery",
		description:
			"Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.",
		url: "https://renoproductgallery.com/",
		icon: <RenoProductGalleryIcon />,
	},
	{
		name: "Reno Product Slider",
		description: "Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.",
		url: "https://wooproductslider.io/",
		icon: <RenoProductSliderIcon />,
	},
	{
		name: "Reno Product Category",
		description: "Display by filtering the list of categories aesthetically and boosting sales.",
		url: "https://shapedplugin.com/reno-product-category/",
		icon: <RenoProductCategoryIcon />,
	},
	{
		name: "Smart Swatches",
		description: "Smart Swatches is a Best Product Variation Swatches for WooCommerce to Boost Your Store Sales.",
		url: "https://shapedplugin.com/smart-swatches-for-woocommerce",
		icon: <SmartSwatch />,
	},
	{
		name: "Smart Brands",
		description:
			"Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.",
		url: "https://shapedplugin.com/smart-brands/",
		icon: <SmartBrand />,
	},
];

const AboutUs = () => {
	const { pluginUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);

	return (
		<section id="about-us-tab" className="sp-real-about-page">
			<div className="sp-real-about-box">
				<div className="sp-real-about-info">
					<h3>
						{__("The Most Powerful Testimonials Builder plugin for WordPress from ", "testimonial-free")}
						<span className="sp-real-highlight-text">
							{__("Real Testimonials Team", "testimonial-free")}
						</span>
					</h3>
					<p>
						{__("At", "testimonial-free")} <b>{__("ShapedPlugin LLC,", "testimonial-free")}</b>{" "}
						{__(
							"we have been looking for the best way to collect and display testimonials on WordPress sites. Unfortunately, we couldn't find any suitable plugin that met our needs. Hence, we set a simple goal: to develop a highly customizable and full-featured testimonials builder plugin to help businesses showcase customer reviews effectively.",
							"testimonial-free"
						)}{" "}
						{__(
							"The Real Testimonials plugin provides a convenient way to create visually appealing testimonial sections to boost social proof. Check it out now and experience the difference!",
							"testimonial-free"
						)}
					</p>
					<div className="sp-real-video-section-btn">
						<ul>
							<li>
								<a
									target="_blank"
									rel="noreferrer"
									href="https://realtestimonials.io/"
									className="sp-real-medium-btn"
								>
									{__("Explore Real Testimonials", "testimonial-free")}
								</a>
							</li>
							<li>
								<a
									target="_blank"
									rel="noreferrer"
									href="https://shapedplugin.com/about-us/"
									className="sp-real-medium-btn sp-real-arrow-btn"
								>
									{__("More About Us ", "testimonial-free")} <Arrow />
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div className="sp-real-about-img">
					<img
						src={`${pluginUrl}src/Admin/assets/images/shapedplugin-team.jpg`}
						alt="Team"
						height="402"
						width="610"
					/>
					<span>{__("The Creative Minds Behind the Real Testimonials Plugin", "testimonial-free")}</span>
				</div>
			</div>

			{/* More Plugins Section */}
			<div className="sp-real-more-plugins-section">
				<div className="sp-real-more-plugins-header">
					<h2 className="sp-real-more-plugins-title">
						{__("Your Website Deserves More Than Typical — Go Premium Today!", "testimonial-free")}
					</h2>
					<p className="sp-real-more-plugins-subtitle">
						{__(
							"Unlock powerful plugins built to boost performance, elevate design, and grow your business.",
							"testimonial-free"
						)}
					</p>
				</div>
				<div className="sp-real-more-plugins-grid">
					{morePlugins.map((plugin) => (
						<a
							key={plugin.name}
							href={plugin.url}
							target="_blank"
							rel="noopener noreferrer"
							className="sp-real-plugin-card sp-d-flex sp-flex-col"
						>
							<div className="sp-real-plugin-card-icon">{plugin.icon}</div>
							<div className="sp-real-plugin-card-content">
								<h3 className="sp-real-plugin-card-title">{plugin.name}</h3>
								<p className="sp-real-plugin-card-desc">{plugin.description}</p>
							</div>
							<span className="sp-real-plugin-card-arrow">
								<ArrowRight />
							</span>
						</a>
					))}
				</div>
			</div>
		</section>
	);
};

export default AboutUs;
