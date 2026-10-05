import { __ } from "@wordpress/i18n";
import { memo } from "@wordpress/element";
import { decodeEntities } from "@wordpress/html-entities";
import { DownloadIcon, HeartIcon, HeartIconFull, ProBadgeIcon } from "./Icons";
import { API_ENDPOINTS } from "./constants";

/**
 * Single pattern card: a fixed 3:2 thumbnail with a hover overlay Preview
 * button, and a footer row of title + wishlist toggle + import.
 *
 * Patterns flagged `pro` in the library payload build on Pro-only blocks or
 * features, so this plugin shows a Pro tag linking to pricing where it would
 * otherwise offer Insert. Preview stays available either way — it is the upsell.
 *
 * @param {Object}   props
 * @param {Object}   props.pattern       Pattern item ({ ID, name, image, ... }).
 * @param {Function} props.onInsert      Insert handler — receives the pattern.
 * @param {Function} props.onPreview     Preview handler — receives the pattern.
 * @param {*}        props.insertingId   ID of the pattern currently importing.
 * @param {boolean}  props.isFavorite    Whether this pattern is in the wishlist.
 * @param {Function} props.onToggleFavorite Toggle-wishlist handler.
 */
const PatternCard = ({ pattern, onInsert, onPreview, insertingId, isFavorite, onToggleFavorite }) => {
	const isBusy = insertingId === pattern.ID;
	const isPro = !!pattern.pro;

	return (
		<article className="sp-real-ready-patterns-card" role="listitem">
			<div className="sp-real-ready-patterns-card-thumb">
				{pattern.image ? (
					<img src={pattern.image} alt={decodeEntities(pattern.name || "")} loading="lazy" />
				) : (
					<span className="sp-real-ready-patterns-card-thumb-placeholder" />
				)}
				<button
					type="button"
					className="sp-real-ready-patterns-preview-link"
					onClick={() => onPreview(pattern)}
				>
					<span className="dashicons dashicons-visibility" />
					{__("Preview", "testimonial-free")}
				</button>
			</div>
			<div className="sp-real-ready-patterns-card-body">
				<h3 className="sp-real-ready-patterns-card-title">{decodeEntities(pattern.name || "")}</h3>
				<button
					type="button"
					className={`sp-real-ready-patterns-favorite-btn${isFavorite ? " is-favorite" : ""}`}
					aria-label={
						isFavorite
							? __("Remove from wishlist", "testimonial-free")
							: __("Add to wishlist", "testimonial-free")
					}
					aria-pressed={isFavorite}
					onClick={onToggleFavorite}
				>
					{isFavorite ? <HeartIconFull /> : <HeartIcon />}
				</button>
				{isPro ? (
					<a
						className="sp-real-ready-patterns-pro-tag sp-d-flex sp-align-center sp-gap-6px"
						href={API_ENDPOINTS.UPGRADE_URL}
						target="_blank"
						rel="noopener noreferrer"
						aria-label={__("Upgrade to Pro to use this pattern", "testimonial-free")}
					>
						<ProBadgeIcon color="#12a150" />
						{__("Pro", "testimonial-free")}
					</a>
				) : (
					<button
						type="button"
						className={`sp-real-ready-patterns-insert-btn${isBusy ? " is-busy" : ""}`}
						onClick={() => onInsert(pattern)}
						disabled={isBusy}
					>
						{isBusy ? __("Inserting…", "testimonial-free") : __("Insert", "testimonial-free")}
						{isBusy ? (
							<span className="dashicons dashicons-update sp-real-ready-patterns-spin" />
						) : (
							<DownloadIcon />
						)}
					</button>
				)}
			</div>
		</article>
	);
};

export default memo(PatternCard);
