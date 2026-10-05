import { __ } from "@wordpress/i18n";
import { useSelect } from "@wordpress/data";
import { memo, useState } from "@wordpress/element";
import { VideoPlayIcon } from "../../Icons";
import { STORE_NAME } from "../../store";
import { generateDocLink } from "@testimonial/constants";
import { DocumentationCardIcon, PatternInsertedButtonIcon, ShowYourLoveIcon, SupportCardIcon } from "./icons";

const WELCOME_VIDEO_ID = "-eSIbpv8Hik";

// Sidebar help cards, in design order.
const SIDEBAR_CARDS = [
	{
		key: "documentation",
		Icon: DocumentationCardIcon,
		title: __("Documentation", "testimonial-free"),
		desc: __(
			"Explore clear, well-organized documentation to understand features, settings, and get the most out of the plugin.",
			"testimonial-free"
		),
		label: __("Browse Now", "testimonial-free"),
		href: generateDocLink("introduction"),
	},
	{
		key: "support",
		Icon: SupportCardIcon,
		title: __("Technical Support", "testimonial-free"),
		desc: __(
			"Need assistance? Reach out to our expert support team for fast, reliable help with any issues or questions.",
			"testimonial-free"
		),
		label: __("Ask Now", "testimonial-free"),
		href: "https://shapedplugin.com/support/",
	},
	{
		key: "love",
		Icon: ShowYourLoveIcon,
		title: __("Show Your Love", "testimonial-free"),
		desc: __(
			"Leave us a quick review—your feedback helps us improve and serve you better every day with confidence together.",
			"testimonial-free"
		),
		label: __("Rate Us", "testimonial-free"),
		href: "https://wordpress.org/support/plugin/testimonial-free/reviews/?filter=5#new-post",
	},
];

const QuickStart = () => {
	const { userName, homeUrl, pluginUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);
	// Click-to-play facade: the embed is mounted in place of the poster on click,
	// so YouTube is not contacted until the visitor asks for the video.
	const [isPlaying, setIsPlaying] = useState(false);

	return (
		<div className="sp-real-qs-page">
			<div className="sp-real-qs-page-content sp-d-flex sp-align-start">
				{/* Welcome */}
				<div className="sp-real-qs-welcome-card">
					<h2 className="sp-real-qs-welcome-title">
						{__("Welcome, ", "testimonial-free")}
						{userName} 👋
					</h2>
					<p className="sp-real-qs-welcome-desc">
						{__(
							"Turn happy customers into your best sales asset. Pick a ready pattern or create from scratch.",
							"testimonial-free"
						)}
					</p>
					<div className="sp-real-qs-welcome-actions sp-d-flex sp-align-center">
						{/* Opens a fresh page in the block editor with the Ready Patterns library auto-opened. */}
						<a
							className="sp-real-qs-btn sp-real-qs-btn-primary sp-d-flex sp-align-center sp-gap-8px"
							href={`${homeUrl}wp-admin/post-new.php?post_type=page&realpatterns=true`}
							rel="noreferrer"
						>
							<PatternInsertedButtonIcon />
							{__("Start with Ready Patterns", "testimonial-free")}
						</a>
						<a
							className="sp-real-qs-btn sp-real-qs-btn-secondary sp-d-flex sp-align-center sp-gap-8px"
							href={`${homeUrl}wp-admin/post-new.php?post_type=page&rtpblock_inserter=true`}
							rel="noreferrer"
						>
							<i className="dashicons dashicons-plus-alt2"></i>
							{__("Create from Scratch", "testimonial-free")}
						</a>
					</div>
					<div className="sp-real-qs-video-wrapper">
						{isPlaying ? (
							<iframe
								className="sp-real-qs-video-frame"
								src={`https://www.youtube.com/embed/${WELCOME_VIDEO_ID}?autoplay=1&rel=0`}
								title={__("Welcome video", "testimonial-free")}
								allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
								allowFullScreen
							></iframe>
						) : (
							<>
								<img
									className="sp-real-qs-video-placeholder"
									src={`${pluginUrl}src/Admin/assets/images/video-overlay.jpg`}
									alt={__("Video Tutorial", "testimonial-free")}
								/>
								<button
									type="button"
									className="sp-real-qs-play-btn sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer"
									onClick={() => setIsPlaying(true)}
									aria-label={__("Play welcome video", "testimonial-free")}
								>
									<VideoPlayIcon />
								</button>
							</>
						)}
					</div>
				</div>

				{/* Right Side - Sidebar */}
				<div className="sp-real-qs-sidebar sp-d-flex sp-flex-col">
					{SIDEBAR_CARDS.map(({ key, Icon, title, desc, label, href }) => (
						<div key={key} className="sp-real-qs-info-card">
							<div className="sp-real-qs-info-header sp-d-flex sp-align-center sp-gap-10px">
								<div className="sp-real-qs-info-icon sp-d-flex sp-align-center sp-justify-center">
									<Icon />
								</div>
								<h4 className="sp-real-qs-info-title">{title}</h4>
							</div>
							<div className="sp-real-qs-info-content-wrapper sp-d-flex sp-flex-col sp-gap-24px">
								<p className="sp-real-qs-info-desc">{desc}</p>
								<div className="sp-real-qs-info-link-wrapper sp-d-flex sp-align-center">
									<a className="sp-real-qs-info-link" href={href} target="_blank" rel="noreferrer">
										{label}
									</a>
								</div>
							</div>
						</div>
					))}
				</div>
			</div>
		</div>
	);
};

export default memo(QuickStart);
