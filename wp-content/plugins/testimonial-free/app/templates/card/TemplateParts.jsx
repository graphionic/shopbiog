import { RatingIcon } from "@testimonial/constants";

const MAX_RATING = 5;

// -----------------------------------------------------------------------------
// Shared helpers
// -----------------------------------------------------------------------------
const cx = (...parts) => parts.filter(Boolean).join(" ");

const Img = ({ src, alt = "", width, height, className }) => (
	<img decoding="async" src={src} alt={alt} width={width} height={height} className={className} />
);

// Reviewer-image fallbacks (mystery/smart-avatar/gravatar/custom) are Pro; free
// only supports "No Fallback Image", so no fallback src is ever resolved.
// Mirrors BlocksHelper::reviewer_fallback_src() (PHP).
export const reviewerFallbackSrc = () => "";

// -----------------------------------------------------------------------------
// Components
// -----------------------------------------------------------------------------

export const ClientImage = ({ attributes, data }) => {
	const { reviewerFallbackImages } = attributes;
	const { thumbnail, title, name } = data;

	// No reviewer image → resolve the configured fallback. "none" renders nothing.
	const isFallback = !thumbnail;
	const fallbackMode = reviewerFallbackImages || "mystery_person";
	const imgSrc = isFallback ? reviewerFallbackSrc(fallbackMode, { name }) : thumbnail;

	if (isFallback && !imgSrc) {
		return null;
	}

	const imgEl = (
		<Img
			src={imgSrc}
			alt={title}
			width={120}
			height={120}
			className={cx("sp-real-img-tag", "sp-real-grayscale-none", isFallback && "sp-real-fallback-image")}
		/>
	);

	return (
		<div className={cx("sp-real-client-image", "sp-real-card-client-image")}>
			<div className="sp-real-client-image-wrap">{imgEl}</div>
		</div>
	);
};

export const TestimonialTitle = ({ title = "", attributes = {} }) => {
	const { titleTag: Tag = "h6" } = attributes;

	return (
		<div className="sp-real-testimonial-client-title">
			<Tag className="sp-real-client-title">{title}</Tag>
		</div>
	);
};

export const TestimonialContent = ({ content = "", attributes = {} }) => {
	const { stripAllHTMLTags } = attributes;

	const finalText = stripAllHTMLTags ? content.replace(/<[^>]*>/g, "").trim() : content;

	return (
		<div className="sp-real-testimonial-content">
			<div className="sp-real-testimonial-text">
				<div className="sp-real-excerpt" dangerouslySetInnerHTML={{ __html: finalText }} />
			</div>
		</div>
	);
};

export const ClientRating = ({ rating = 0, ratingIconSet = {} }) => {
	const activeIcon = ratingIconSet?.active || "star-fill";
	const inactiveIcon = ratingIconSet?.inactive || "star-stroke";

	const value = Math.max(0, Math.min(Number(rating) || 0, MAX_RATING));
	const full = Math.floor(value);
	const hasHalf = value - full >= 0.5;
	const empty = MAX_RATING - full - (hasHalf ? 1 : 0);

	return (
		<div className="sp-real-client-rating sp-d-flex sp-align-center">
			{Array.from({ length: full }, (_, i) => (
				<span key={`full-${i}`} className="sp-real-rating-icon sp-real-rating-full">
					<RatingIcon name={activeIcon} />
				</span>
			))}
			{hasHalf && (
				<span key="half" className="sp-real-rating-icon sp-real-rating-half">
					<span className="sp-real-rating-base">
						<RatingIcon name={inactiveIcon} />
					</span>
					<span className="sp-real-rating-fill">
						<RatingIcon name={activeIcon} />
					</span>
				</span>
			)}
			{Array.from({ length: empty }, (_, i) => (
				<span key={`empty-${i}`} className="sp-real-rating-icon sp-real-rating-empty">
					<RatingIcon name={inactiveIcon} />
				</span>
			))}
		</div>
	);
};

export const ClientName = ({ name = "", tag: Tag = "h6" }) => (
	<div className="sp-real-testimonial-client-name sp-d-flex sp-align-center">
		<Tag className="sp-real-client-name">{name}</Tag>
	</div>
);

export const ClientDesignation = ({ position = "", company = "" }) => {
	const label = company ? (position ? `${position} at ${company}` : company) : position;
	if (!label) {
		return null;
	}

	return <div className="sp-real-client-designation">{label}</div>;
};
