import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";

const VIDEO_ID = "H1WfLICiETw";

export const SavedTemplatesPromo = ({ homeUrl }) => {
	const [isPlaying, setIsPlaying] = useState(false);

	const addNewUrl = `${homeUrl}wp-admin/post-new.php?post_type=sp_real_template&rtpblock_inserter=true`;

	return (
		<div className="sp-real-saved-template-promo">
			<div className="sp-real-saved-template-promo__text">
				<div className="sp-real-saved-template-promo__title-wrap">
					<h2 className="sp-real-saved-template-promo__title">
						{__("Design visually.", "testimonial-free")} <span>{__("Place it ", "testimonial-free")}</span>
						<span className="sp-real-saved-template-promo__title-accent">
							{__("anywhere.", "testimonial-free")}
						</span>
					</h2>
					<p className="sp-real-saved-template-promo__desc">
						Saved Templates let you build testimonial layouts with Gutenberg blocks and use them as{" "}
						<b>shortcodes</b> — perfect for page builders like <b>Elementor, Divi, WPBakery</b>, and more.
					</p>
					<p className="sp-real-saved-template-promo__desc">
						Real Testimonials blocks work directly on any page or post in Gutenberg.
					</p>
				</div>
				<a href={addNewUrl} rel="noreferrer" className="sp-real-saved-template-promo__cta">
					<i className="dashicons dashicons-plus-alt2"></i>
					{__("Add New Template", "testimonial-free")}
				</a>
			</div>
			<div className="sp-real-saved-template-promo__video">
				{isPlaying ? (
					<iframe
						className="sp-real-saved-template-promo__video-frame"
						src={`https://www.youtube.com/embed/${VIDEO_ID}?autoplay=1`}
						title={__("Real Testimonials overview video", "testimonial-free")}
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
						allowFullScreen
					></iframe>
				) : (
					<button
						type="button"
						className="sp-real-saved-template-promo__video-thumb"
						style={{
							backgroundImage: `url(https://img.youtube.com/vi/${VIDEO_ID}/maxresdefault.jpg)`,
						}}
						onClick={() => setIsPlaying(true)}
						aria-label={__("Play overview video", "testimonial-free")}
					>
						<span className="sp-real-saved-template-promo__video-overlay" />
						<span className="sp-real-saved-template-promo__video-play">
							<svg
								width="22"
								height="22"
								viewBox="0 0 22 22"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
								aria-hidden="true"
							>
								<path d="M19 11L4 19.6603L4 2.33975L19 11Z" fill="var(--sp-real-primary-color)" />
							</svg>
						</span>
					</button>
				)}
			</div>
		</div>
	);
};
