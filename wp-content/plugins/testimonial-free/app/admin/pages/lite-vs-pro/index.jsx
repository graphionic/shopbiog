import { __ } from "@wordpress/i18n";
import { useSelect } from "@wordpress/data";
import { STORE_NAME } from "../../store";
import { ProBadgeIcon } from "../../../ready-patterns/Icons";
import { pricingPageUrl } from "@testimonial/constants";

const ProIconLight = () => (
	<svg width={16} height={16} viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M8 0 1 3v4.5C1 11.6 3.98 15.4 8 16c4.02-.6 7-4.4 7-8.5V3L8 0Z" fill="#02B472" />
		<path d="m6.9 10.7-2-2 1-1 1 1 3.2-3.2 1 1L6.9 10.7Z" fill="#fff" />
	</svg>
);

const WordPressIcon = () => (
	<svg xmlns="http://www.w3.org/2000/svg" width={17} height={16} fill="none">
		<path
			fill="#6E6A69"
			d="M16.5 8c0-4.408-3.592-8-8-8-4.416 0-8 3.592-8 8 0 4.416 3.584 8 8 8 4.408 0 8-3.584 8-8Zm-9.776 4.296-2.728-7.32c.44-.016.936-.064.936-.064.4-.048.352-.904-.048-.888 0 0-1.16.088-1.896.088-.144 0-.296 0-.464-.008A7.097 7.097 0 0 1 8.5.888c1.864 0 3.56.696 4.84 1.872-.544-.088-1.32.312-1.32 1.264 0 .592.36 1.088.72 1.68.28.488.44 1.088.44 1.968 0 1.192-1.12 4-1.12 4L9.636 4.976c.432-.016.656-.136.656-.136.4-.04.352-1-.048-.976 0 0-1.152.096-1.904.096-.696 0-1.864-.096-1.864-.096-.4-.024-.448.96-.048.976l.736.064 1.008 2.728-1.448 4.664ZM14.428 8c.192-.512.592-1.496.344-3.4.56 1.032.84 2.168.84 3.4 0 2.632-1.384 4.992-3.52 6.224.776-2.072 1.552-4.16 2.336-6.224ZM5.38 14.472C2.996 13.32 1.388 10.824 1.388 8c0-1.04.184-1.984.576-2.872C3.1 8.24 4.236 11.36 5.38 14.472Zm3.224-5.304 2.064 5.584a6.734 6.734 0 0 1-2.168.36 6.301 6.301 0 0 1-1.832-.264c.648-1.904 1.296-3.792 1.936-5.68Z"
		/>
	</svg>
);

const StarIcon = ({ color = "#F4674D" }) => (
	<svg width={16} height={15} viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M7.70547 0L9.84795 5.15113L15.409 5.59696L11.1721 9.22637L12.4665 14.653L7.70547 11.745L2.94441 14.653L4.23887 9.22637L0.00191116 5.59696L5.56299 5.15113L7.70547 0Z"
			fill={color}
		/>
	</svg>
);

const HalfStarIcon = () => (
	<svg width={17} height={17} viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="m8.1 0 2.143 5.151 5.56.446-4.236 3.63 1.294 5.426L8.1 11.745l-4.761 2.908 1.294-5.427L.396 5.597l5.562-.446z"
			fill="#e0e0e0"
		/>
		<path d="m8.1 0-2.142 5.151-5.562.446 4.236 3.63-1.294 5.426L8.1 11.745V0z" fill="#f4674d" />
	</svg>
);

const TrustpilotIcon = () => (
	<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="m10.5 15.252 4.563-1.164L16.969 20zM21 7.61h-8.031L10.5 0 8.031 7.61H0l6.5 4.717-2.469 7.61 6.5-4.717 4-2.893z"
			fill="#00b57a"
		/>
	</svg>
);

const features = [
	{
		title: __("All Free Version Features", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __(
			"Amazing Testimonial Layouts (Slider, Carousel, Grid, Masonry, List, Isotope, etc.)",
			"testimonial-free"
		),
		free: 7,
		pro: 16,
		hot: true,
	},
	{
		title: __("Customizable and Professionally Designed Testimonials Themes", "testimonial-free"),
		free: 7,
		pro: 33,
	},
	{
		title: __("Ready-to-use Testimonial Patterns", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Reusable Saved Templates", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Testimonial Submission Form (Inline)", "testimonial-free"),
		free: "yes",
		pro: "yes",
		hot: true,
	},
	{
		title: __("Popup Submission Form", "testimonial-free"),
		free: "no",
		pro: "yes",
		new: true,
	},
	{
		title: __("Google reCAPTCHA Spam Protection", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Auto-Publish Testimonials (By Rating)", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Email Notifications on New Submission", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Custom Redirect After Submission", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Reviewer Social Profiles", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Video Testimonials", "testimonial-free"),
		free: "no",
		pro: "yes",
		hot: true,
	},
	{
		title: __("Reviewer Image Fallbacks (Gravatar, Mystery, Initials)", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Carousel Sliding Effects (Slide, Cube, Flip, Fade, etc.)", "testimonial-free"),
		free: 3,
		pro: 6,
	},
	{
		title: __("Ken Burns & Creative Motion Effects", "testimonial-free"),
		free: "no",
		pro: "yes",
		new: true,
	},
	{
		title: __("Adaptive Height Carousel", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Live Frontend Filter & AJAX Search", "testimonial-free"),
		free: "no",
		pro: "yes",
		hot: true,
	},
	{
		title: __("Filter by Group & Rating", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Marquee / Ticker Layout", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Bento & Polaroid Grid", "testimonial-free"),
		free: "yes",
		pro: "yes",
		new: true,
	},
	{
		title: __("Schema Markup (Google Rich Snippets)", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Page Builder Integrations (Elementor, Divi, WPBakery, Bricks, etc.)", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Global Style Controls", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Entrance & Scroll Animations", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
	{
		title: __("Import / Export Testimonials (CSV)", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Multisite Compatible", "testimonial-free"),
		free: "yes",
		pro: "yes",
	},
	{
		title: __("Priority Top-notch Support", "testimonial-free"),
		free: "no",
		pro: "yes",
	},
];

const testimonials = [
	{
		text: __(
			"This plugin is exactly what I was looking for. It's easy to set up, the layouts look great out of the box, and the support team responded to my question within a couple of hours. Highly recommended!",
			"testimonial-free"
		),
		user: "wordpress",
		name: "Sirpa",
		role: "Website Owner",
		img: "src/Admin/assets/images/sirpa.png",
	},
	{
		text: __(
			"Real Testimonials made it effortless to collect and showcase customer reviews on my site. The carousel and grid layouts are beautiful and the submission form works flawlessly. Worth every penny.",
			"testimonial-free"
		),
		user: "wordpress",
		name: "Ali Senejani",
		role: "Freelance Developer",
		img: "src/Admin/assets/images/ali_senejani.png",
	},
	{
		text: __(
			"Great plugin with a clean interface and helpful documentation. The free version is generous and the Pro upgrade added the video testimonials and filtering we needed. Support has been fantastic.",
			"testimonial-free"
		),
		user: "trustpilot",
		name: "Jordan Miles",
		role: "Marketing Manager",
	},
];

const generateFreeOrProContent = (content) => {
	if (typeof content === "number") {
		return <b>{content}</b>;
	}
	if (content === "yes") {
		return <i className="dashicons dashicons-saved"></i>;
	}
	if (content === "no") {
		return <i className="dashicons dashicons-no-alt"></i>;
	}
	return content;
};

const LiteVsPro = () => {
	const { pluginUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);

	return (
		<section className="sp-real-pro-page" id="lite-pro-tab">
			<div className="sp-real-pro-table">
				<div className="sp-real-pro-header sp-d-flex sp-align-center sp-justify-between">
					<div>
						<h2 className="sp-real-section-title">{__("Lite vs Pro Comparison", "testimonial-free")}</h2>
						<span className="sp-real-pro-subtitle">
							{__(
								"Get Real Testimonials Pro Today and Unlock all the Powerful Features",
								"testimonial-free"
							)}
						</span>
					</div>
					<a target="_blank" rel="noreferrer" href={pricingPageUrl} className="sp-real-upgrade-to-pro-btn">
						<ProBadgeIcon color="#fff" />
						{__("Upgrade to Pro Now!", "testimonial-free")}
					</a>
				</div>
				<div className="sp-real-pro-table-list">
					<ul>
						<li className="sp-real-pro-table-row sp-real-header">
							<span className="sp-real-title">{__("FEATURES", "testimonial-free")}</span>
							<span className="sp-real-free">{__("FREE", "testimonial-free")}</span>
							<span className="sp-real-pro sp-real-pro-icon">
								<ProIconLight />
								{__("PRO", "testimonial-free")}
							</span>
						</li>
						{features.map((item, index) => (
							<li className="sp-real-pro-table-row" key={index}>
								<span className="sp-real-title">
									{item?.title}
									{item?.new && <span className="sp-real-new">{__("new", "testimonial-free")}</span>}
									{item?.hot && <span className="sp-real-hot">{__("hot", "testimonial-free")}</span>}
								</span>
								<span className="sp-real-free">{generateFreeOrProContent(item?.free)}</span>
								<span className="sp-real-pro">{generateFreeOrProContent(item?.pro)}</span>
							</li>
						))}
					</ul>
				</div>
			</div>
			<div className="sp-real-upgrade-to-pro-promotion">
				<h2 className="sp-real-section-title">
					{__("Upgrade To PRO & Enjoy Advanced Features!", "testimonial-free")}
				</h2>
				<span className="sp-real-section-subtitle">
					{__("Already, ", "testimonial-free")}
					<b>{__("40,000+", "testimonial-free")}</b>
					{__(
						" websites are using Real Testimonials to build trust with beautiful reviews, why won't you!",
						"testimonial-free"
					)}
				</span>
				<div className="sp-real-upgrade-to-pro-btn-wrapper sp-d-flex sp-justify-center">
					<a target="_blank" rel="noreferrer" href={pricingPageUrl} className="sp-real-upgrade-to-pro-btn">
						{__("Upgrade to Pro Now!", "testimonial-free")}
					</a>
					<a
						target="_blank"
						rel="noreferrer"
						href="https://realtestimonials.io/"
						className="sp-real-upgrade-to-pro-btn"
					>
						{__("See All Features", "testimonial-free")}
					</a>
				</div>
			</div>

			{/* Testimonial Section */}
			<div className="sp-real-testimonial">
				<div className="sp-real-testimonial-header">
					<div className="sp-real-testimonial-ratings">
						<a
							href="https://wordpress.org/support/plugin/testimonial-free/reviews/"
							target="_blank"
							rel="noreferrer"
							className="sp-real-testimonial-rating-item"
							title="Reviews on WordPress"
						>
							<div className="sp-real-testimonial-rating-wordpress">
								<div className="sp-real-wp-mark">
									<WordPressIcon />
								</div>
								<div className="sp-real-wp-text">
									<StarIcon />
									<StarIcon />
									<StarIcon />
									<StarIcon />
									<HalfStarIcon />
								</div>
							</div>
							<span className="sp-real-testimonial-rating-score">4.7/5</span>
							<span className="sp-real-testimonial-review-count">220+ Reviews</span>
						</a>
						<a
							href="https://www.trustpilot.com/review/shapedplugin.com"
							target="_blank"
							rel="noreferrer"
							className="sp-real-testimonial-rating-item"
							title="Reviews on Trustpilot"
						>
							<div className="sp-real-testimonial-rating-trustpilot">
								<div className="sp-real-trustpilot-mark">
									<TrustpilotIcon />
								</div>
							</div>
							<div className="sp-real-trustpilot-stars">
								<StarIcon color="#fff" />
								<StarIcon color="#fff" />
								<StarIcon color="#fff" />
								<StarIcon color="#fff" />
								<StarIcon color="#fff" />
							</div>
							<span className="sp-real-testimonial-rating-score">4.8/5</span>
							<span className="sp-real-testimonial-review-count">140+ Reviews</span>
						</a>
					</div>
					<h2 className="sp-real-testimonial-title">
						{__("Don't Just Take Our Word for It — See What Users Say!", "testimonial-free")}
					</h2>
				</div>
				<div className="sp-real-testimonial-wrap">
					{testimonials?.map((item, index) => (
						<div
							className={`sp-real-testimonial-card sp-d-flex sp-flex-col sp-align-start sp-gap-20px ${item?.user === "trustpilot" ? "sp-real-testimonial-card-trustpilot" : ""}`}
							key={index}
						>
							<div className="sp-real-testimonial-card-header">
								<div className="sp-real-testimonial-reviewer sp-d-flex sp-align-center sp-gap-12px">
									<div className="sp-real-testimonial-avatar">
										{item?.user === "trustpilot" ? (
											<div className="sp-real-testimonial-avatar-initials sp-d-flex sp-align-center sp-justify-center">
												<span>{item.name.slice(0, 2).toUpperCase()}</span>
											</div>
										) : (
											<img src={`${pluginUrl}${item?.img}`} alt={item?.name} />
										)}
										<div className="sp-real-testimonial-source-badge sp-d-flex sp-align-center sp-justify-center">
											{item?.user === "trustpilot" ? <TrustpilotIcon /> : <WordPressIcon />}
										</div>
									</div>
									<div className="sp-real-testimonial-reviewer-info sp-d-flex sp-flex-col sp-gap-4px">
										<h3>{item.name}</h3>
										<p>{item.role}</p>
									</div>
								</div>
							</div>
							<div className="sp-real-testimonial-rating-stars sp-d-flex sp-align-center sp-justify-start sp-gap-2px">
								{item?.user === "trustpilot" ? (
									<div className="sp-real-testimonial-rating-trustpilot-stars sp-d-flex sp-align-center sp-gap-2px">
										{Array.from({ length: 5 }).map((_, i) => (
											<span className="sp-real-trustpilot-star-item" key={i}>
												<StarIcon color="#fff" />
											</span>
										))}
									</div>
								) : (
									<span>★★★★★</span>
								)}
							</div>
							<div className="sp-real-testimonial-card-content">
								<p>{item?.text}</p>
							</div>
						</div>
					))}
				</div>
			</div>
		</section>
	);
};

export default LiteVsPro;
