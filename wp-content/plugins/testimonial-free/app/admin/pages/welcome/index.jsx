import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import { STORE_NAME } from "../../store";

const FeatureCheckIcon = () => (
	<svg width={14} height={14} viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M11.376 6.139a.62.62 0 0 0-.622.622v4.5a.474.474 0 0 1-.474.473H2.156a.474.474 0 0 1-.474-.473V3.136c0-.261.213-.473.474-.473H8.82a.622.622 0 0 0 0-1.245H2.156A1.72 1.72 0 0 0 .437 3.136v8.125a1.72 1.72 0 0 0 1.719 1.718h8.124A1.72 1.72 0 0 0 12 11.26v-4.5a.62.62 0 0 0-.623-.622"
			fill="var(--sp-real-primary-color)"
		/>
		<path
			d="M12.5 1.204 5.576 8.153l-1.78-1.786a.622.622 0 0 0-.881.88L4.893 9.23a.96.96 0 0 0 .684.285.96.96 0 0 0 .684-.285l7.12-7.147a.622.622 0 1 0-.882-.879"
			fill="var(--sp-real-primary-color)"
		/>
	</svg>
);

const WelcomePage = () => {
	const [showVideo, setShowVideo] = useState(false);
	const featureLists = [
		{
			title: __("Add Testimonials in Seconds", "testimonial-free"),
		},
		{
			title: __("AI-Generated Review Summary ", "testimonial-free"),
		},
		{
			title: __("15+ Modern Gutenberg Blocks", "testimonial-free"),
		},
		{
			title: __("Video Testimonial Recorder", "testimonial-free"),
		},
		{
			title: __("Manage Video Testimonials", "testimonial-free"),
		},
		{
			title: __("Automate Collecting Testimonials", "testimonial-free"),
		},
		{
			title: __("Elementor, Divi, Beaver & More.", "testimonial-free"),
		},
	];

	const hour = new Date().getHours();
	let greeting = "Good Night";

	if (hour >= 5 && hour < 12) {
		greeting = "Good Morning";
	} else if (hour >= 12 && hour < 17) {
		greeting = "Good Afternoon";
	} else if (hour >= 17 && hour < 21) {
		greeting = "Good Evening";
	}
	const { userName, pluginUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);

	return (
		<div className="sp-real-setup-welcome-page sp-d-flex sp-align-center">
			<div className="sp-real-setup-welcome-page-left sp-d-flex sp-flex-col">
				<div className="sp-real-setup-welcome-page-greeting">
					{greeting}, {userName}
				</div>
				<h3 className="sp-real-setup-page-title sp-welcome-title">
					Welcome to <span className="sp-real-plugin-name">Real Testimonials!</span>
				</h3>
				<p className="sp-real-setup-page-desc">
					Thanks for installing Real Testimonials — This plugin gives you everything you need to create clean
					and interactive testimonial sections, without <br /> touching a line of code.
				</p>
				<p className="sp-real-setup-page-desc">
					Get set up in minutes and start showcasing testimonials using
					<b> 15+ flexible blocks and 225+ ready-made patterns.</b> Packed with features, including:
				</p>
				<div className="sp-real-setup-feature-lists sp-d-grid sp-grid-cols-2 sp-gap-10px">
					{featureLists?.map((item, index) => (
						<div key={index} className="sp-real-setup-feature-list sp-d-flex sp-align-center sp-gap-8px">
							<span className="sp-real-setup-feature-check-icon">
								<FeatureCheckIcon />
							</span>
							<span className="sp-real-feature-list-title">
								{item.title}
								{item?.hot && <span className="sp-real-feature-list-badge hot">HOT</span>}
								{item?.upcoming && (
									<span className="sp-real-feature-list-badge upcoming">Upcoming</span>
								)}
							</span>
						</div>
					))}
				</div>
			</div>
			<div
				className="sp-real-setup-welcome-page-right sp-d-flex"
				style={{
					backgroundImage: `url(${pluginUrl}src/Admin/assets/images/setup-wizard/video-thumbnail.png)`,
				}}
			>
				{showVideo ? (
					<iframe
						width="510"
						height="410"
						src="https://www.youtube.com/embed/OA7LgaZHwIY?list=PLoUb-7uG-5jM2sjscSqBVj07VXOqt0qHZ&autoplay=1"
						title="YouTube video player"
						allow="autoplay; encrypted-media"
					></iframe>
				) : (
					<div className="sp-real-setup-video-overlay sp-d-flex sp-align-center sp-justify-center">
						<button
							id="sp-real-play-btn"
							className="sp-real-play-btn-sonar sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer"
							onClick={() => setShowVideo(true)}
						>
							<img
								src={`${pluginUrl}src/Admin/assets/images/setup-wizard/video-play.png`}
								alt="video-play"
							/>
						</button>
					</div>
				)}
			</div>
		</div>
	);
};

export default WelcomePage;
