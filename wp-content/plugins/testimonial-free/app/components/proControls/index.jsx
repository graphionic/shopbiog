/* eslint-disable jsx-a11y/anchor-has-content */
import { __ } from "@wordpress/i18n";
import { createInterpolateElement } from "@wordpress/element";
import { ProBadgeIcon } from "../../ready-patterns/Icons";
import { API_ENDPOINTS } from "../../ready-patterns/constants";
import "./editor.scss";

// ProFeaturesBox.
export const ProFeaturesBox = ({
	title = __("Pro Features", "testimonial-free"),
	description = "",
	features = [],
	buttonLabel = __("Upgrade to Pro", "testimonial-free"),
}) => (
	<div className="sp-real-pro-features-box sp-real-component-mb">
		<h4 className="sp-real-pro-features-box__title sp-d-flex sp-align-center sp-gap-4px">
			<ProBadgeIcon />
			{title}
		</h4>
		<p className="sp-real-pro-features-box__desc">{description}</p>
		<ul className="sp-real-pro-features-box__list">
			{features.map((feature, index) => (
				<li key={index}>{feature}</li>
			))}
		</ul>
		<a
			className="sp-real-pro-features-box__btn sp-d-flex sp-align-center sp-justify-center sp-gap-4px"
			href={API_ENDPOINTS.UPGRADE_URL}
			target="_blank"
			rel="noopener noreferrer"
		>
			<ProBadgeIcon color="#fff" />
			{buttonLabel}
		</a>
	</div>
);

// ProNoteBox.
export const ProNoteBox = ({ children }) => (
	<div className="sp-real-pro-note-box sp-real-component-mb">
		<p className="sp-real-pro-note-box__text">{children}</p>
	</div>
);

/**
 * Testimonial-content-tab promo: highlights content-length, quote-symbol and
 * formatting controls with an inline upgrade link.
 */
export const TestimonialContentProNote = () => (
	<ProNoteBox>
		{createInterpolateElement(
			__(
				"Control testimonial <b>content length</b>, add <b>quote symbols</b> and formatting with ease. <a>Upgrade to Pro!</a>",
				"testimonial-free"
			),
			{
				b: <strong />,
				a: (
					<a
						className="sp-real-pro-note-box__link"
						href={API_ENDPOINTS.UPGRADE_URL}
						target="_blank"
						rel="noopener noreferrer"
					/>
				),
			}
		)}
	</ProNoteBox>
);

/**
 * Reviewer-details-tab promo: verified badges and reviewer designation linking
 * with an inline upgrade link.
 */
export const ReviewerDetailsProNote = () => (
	<ProNoteBox>
		{createInterpolateElement(
			__(
				"Add verified badges and link reviewer designation to build trust and credibility. <a>Upgrade to Pro!</a>",
				"testimonial-free"
			),
			{
				a: (
					<a
						className="sp-real-pro-note-box__link"
						href={API_ENDPOINTS.UPGRADE_URL}
						target="_blank"
						rel="noopener noreferrer"
					/>
				),
			}
		)}
	</ProNoteBox>
);

// ProPanelTitle.
// Inspector PanelBody title with the Pro crown badge — for panels that hold
// nothing but an upsell teaser (Email Notification, Motion Effects, ...).
export const ProPanelTitle = ({ label }) => (
	<span className="sp-real-pro-panel-title">
		{label}
		<ProBadgeIcon color="#FF9437" fill="#FF9437" />
	</span>
);
