import { __ } from "@wordpress/i18n";
import { memo } from "@wordpress/element";

/**
 * One facet row: label + count badge + active state. Mirrors the
 * wp-carousel-pro `FacetRow` (no icon column — testimonial block types ship no
 * per-type icons in the manifest).
 *
 * @param {Object}   props
 * @param {string}   props.label    Row label.
 * @param {number}   props.count    Item count badge.
 * @param {boolean}  props.isActive Whether this row is selected.
 * @param {Function} props.onSelect Click handler.
 */
const FacetRow = ({ label, count, isActive, onSelect }) => (
	<button
		type="button"
		className={`sp-real-ready-patterns-category${isActive ? " is-active" : ""}`}
		aria-pressed={isActive}
		onClick={onSelect}
	>
		<span className="sp-real-ready-patterns-category-label">{label}</span>
		<span className="sp-real-ready-patterns-badge">{count ?? 0}</span>
	</button>
);

/**
 * Left sidebar: tier (All/Free/Pro), search, and the Block Type + Category Type
 * browse axes. Mirrors wp-carousel-pro's Sidebar, adapted to testimonial data.
 *
 * @param {Object}   props
 * @param {string}   props.tier               Active tier ("all"|"free"|"pro").
 * @param {Function} props.onTierChange       Tier change handler.
 * @param {Object}   props.tierCounts         { all, free, pro } counts.
 * @param {string}   props.search             Search query.
 * @param {Function} props.onSearchChange     Search change handler.
 * @param {string}   props.blockType          Active Block Type filter.
 * @param {Function} props.onBlockTypeChange  Block Type change handler.
 * @param {Array}    props.blockOptions       [{ label, value, count }] (incl. "all").
 * @param {string}   props.categoryType       Active Category Type filter.
 * @param {Function} props.onCategoryTypeChange Category Type change handler.
 * @param {Array}    props.categoryOptions    [{ label, value, count }] (incl. "all").
 */
const Sidebar = ({
	tier,
	onTierChange,
	tierCounts,
	search,
	onSearchChange,
	blockType,
	onBlockTypeChange,
	blockOptions,
	categoryType,
	onCategoryTypeChange,
	categoryOptions,
}) => {
	const tierOptions = [
		{ value: "all", label: __("All", "testimonial-free") },
		{ value: "free", label: __("Free", "testimonial-free") },
		{ value: "pro", label: __("Pro", "testimonial-free") },
	];

	return (
		<aside className="sp-real-ready-patterns-sidebar">
			<div className="sp-real-ready-patterns-tier">
				{tierOptions.map((option) => (
					<button
						key={option.value}
						type="button"
						className={`sp-real-ready-patterns-tier-btn${option.value === tier ? " is-active" : ""}`}
						aria-pressed={option.value === tier}
						onClick={() => onTierChange(option.value)}
					>
						{option.label}
						<span className="sp-real-ready-patterns-tier-count">{tierCounts?.[option.value] ?? 0}</span>
					</button>
				))}
			</div>

			<div className="sp-real-ready-patterns-search">
				<label className="screen-reader-text" htmlFor="sp-real-ready-patterns-search-input">
					{__("Search patterns", "testimonial-free")}
				</label>
				<span className="dashicons dashicons-search" />
				<input
					type="search"
					id="sp-real-ready-patterns-search-input"
					className="sp-real-ready-patterns-search-input"
					placeholder={__("Search patterns", "testimonial-free")}
					value={search}
					onChange={(event) => onSearchChange(event.target.value)}
				/>
			</div>

			{blockOptions && blockOptions.length > 1 ? (
				<>
					<h3 className="sp-real-ready-patterns-sidebar-heading">{__("Block Type", "testimonial-free")}</h3>
					<nav
						className="sp-real-ready-patterns-categories"
						aria-label={__("Pattern block types", "testimonial-free")}
					>
						{blockOptions.map((option) => (
							<FacetRow
								key={option.value}
								label={option.label}
								count={option.count}
								isActive={String(blockType) === String(option.value)}
								onSelect={() => onBlockTypeChange(option.value)}
							/>
						))}
					</nav>
				</>
			) : null}

			{categoryOptions && categoryOptions.length > 1 ? (
				<>
					<h3 className="sp-real-ready-patterns-sidebar-heading">
						{__("Category Type", "testimonial-free")}
					</h3>
					<nav
						className="sp-real-ready-patterns-categories"
						aria-label={__("Pattern categories", "testimonial-free")}
					>
						{categoryOptions.map((option) => (
							<FacetRow
								key={option.value}
								label={option.label}
								count={option.count}
								isActive={String(categoryType) === String(option.value)}
								onSelect={() => onCategoryTypeChange(option.value)}
							/>
						))}
					</nav>
				</>
			) : null}
		</aside>
	);
};

export default memo(Sidebar);
