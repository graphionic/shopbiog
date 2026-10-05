import { __ } from "@wordpress/i18n";
import { useCallback, useMemo, memo, useState } from "@wordpress/element";
import Sidebar from "./Sidebar";
import Toolbar from "./Toolbar";
import PatternCard from "./PatternCard";

// Loading skeleton grid (mirrors the card shape).
const LoadingSkeleton = memo(() => (
	<div className="sp-real-ready-patterns-grid sp-real-ready-patterns-cols-3">
		{Array.from({ length: 9 }).map((_, i) => (
			<div className="sp-real-ready-patterns-card is-skeleton" key={i}>
				<div className="sp-real-ready-patterns-card-thumb" />
				<div className="sp-real-ready-patterns-card-body">
					<span className="sp-real-ready-patterns-skeleton-line" />
					<span className="sp-real-ready-patterns-skeleton-btn" />
				</div>
			</div>
		))}
	</div>
));

const EmptyState = memo(() => (
	<div className="sp-real-ready-patterns-state">{__("No patterns found.", "testimonial-free")}</div>
));

/**
 * Grid: applies the in-memory filters (search / tier / category-type / wishlist)
 * to the sorted list and renders PatternCards.
 */
const PatternGrid = memo(
	({
		items,
		columns,
		searchQuery,
		freePro,
		categoryType,
		showWishList,
		wishListArr,
		setWListAction,
		reloadId,
		onImport,
		onPreview,
	}) => {
		const filtered = useMemo(() => {
			return items.filter((data) => {
				const searchMatch = !searchQuery || data.name?.toLowerCase().includes(searchQuery.toLowerCase());
				const freeProMatch =
					freePro === "all" || (freePro === "pro" && data.pro) || (freePro === "free" && !data.pro);
				const categoryTypeMatch =
					!categoryType ||
					categoryType === "all" ||
					(Array.isArray(data.category) && data.category.map(String).includes(String(categoryType)));
				const wishListMatch = !showWishList || wishListArr?.includes(String(data.ID));
				return searchMatch && freeProMatch && categoryTypeMatch && wishListMatch;
			});
		}, [items, searchQuery, freePro, categoryType, showWishList, wishListArr]);

		if (filtered.length === 0) {
			return <EmptyState />;
		}

		return (
			<div className={`sp-real-ready-patterns-grid sp-real-ready-patterns-cols-${columns}`}>
				{filtered.map((data) => (
					<PatternCard
						key={data.ID}
						pattern={data}
						onInsert={onImport}
						onPreview={onPreview}
						insertingId={reloadId}
						isFavorite={wishListArr?.includes(String(data.ID))}
						onToggleFavorite={() =>
							setWListAction(data.ID, wishListArr?.includes(String(data.ID)) ? "remove" : "")
						}
					/>
				))}
			</div>
		);
	}
);

/**
 * Main body: Sidebar (full mode) + main area (Toolbar + Grid). Owns the
 * in-modal UI state (columns, search, sort, tier, category-type, wishlist) and
 * the block-type filter; keeps the existing data plumbing from Library intact.
 */
const StarterSites = (props) => {
	const {
		filterValue,
		state,
		setState,
		_changeVal,
		filterByCategoryKey,
		setWListAction,
		wishListArr,
		_fetchFile,
		isSingleBlock,
		onPreview,
	} = props;

	const {
		current,
		designs,
		reloadId,
		blocks = [],
		categoryTypes = [],
		freeCount,
		proCount,
		fetching,
		loading,
	} = state;

	// Local UI state.
	const [column, setColumn] = useState("3");
	const [searchQuery, setSearchQuery] = useState("");
	const [trend, setTrend] = useState("default");
	const [freePro, setFreePro] = useState("all");
	const [categoryType, setCategoryType] = useState("all");
	const [showWishList, setShowWishList] = useState(false);

	const totalCount = (freeCount || 0) + (proCount || 0);

	// Sorted list — newest first for "latest", by popularity for "popular".
	const premadeLists = useMemo(() => {
		if (!current.length) {
			return [];
		}
		const lists = [...current];
		if (trend === "latest" || trend === "all") {
			return lists.sort((a, b) => b.ID - a.ID);
		}
		if (trend === "popular" && lists[0]?.hit !== undefined) {
			return lists.sort((a, b) => b.hit - a.hit);
		}
		return lists;
	}, [current, trend]);

	// Plain option arrays (label + value + count) for the Sidebar facets.
	const blockOptions = useMemo(() => {
		const opts = [{ label: __("All Patterns", "testimonial-free"), value: "all", count: totalCount }];
		(Array.isArray(blocks) ? blocks : []).forEach((b) => {
			opts.push({ label: b.label || b.value, value: b.value, count: b.count ?? 0 });
		});
		return opts;
	}, [blocks, totalCount]);

	const categoryOptions = useMemo(() => {
		const opts = [{ label: __("All Categories", "testimonial-free"), value: "all", count: totalCount }];
		(Array.isArray(categoryTypes) ? categoryTypes : []).forEach((c) => {
			opts.push({ label: c.label || String(c.value), value: String(c.value), count: c.count ?? 0 });
		});
		return opts;
	}, [categoryTypes, totalCount]);

	// Block-type filter narrows `designs` (the full flattened catalog) into `current`.
	const handleFilterChange = useCallback(
		(type) => {
			const filteredData = type === "all" ? designs : filterByCategoryKey(designs, type);
			setState((prev) => ({ ...prev, designFilter: type, current: filteredData }));
		},
		[designs, filterByCategoryKey, setState]
	);

	const handleImport = useCallback(
		(pattern) => {
			_changeVal(pattern.ID, pattern.pro);
		},
		[_changeVal]
	);

	const hasData =
		(Array.isArray(blocks) && blocks.length > 0) || (Array.isArray(categoryTypes) && categoryTypes.length > 0);

	return (
		<div className="sp-real-ready-patterns-body">
			{!isSingleBlock ? (
				<Sidebar
					tier={freePro}
					onTierChange={setFreePro}
					tierCounts={{ all: totalCount, free: freeCount || 0, pro: proCount || 0 }}
					search={searchQuery}
					onSearchChange={setSearchQuery}
					blockType={filterValue}
					onBlockTypeChange={handleFilterChange}
					blockOptions={blockOptions}
					categoryType={categoryType}
					onCategoryTypeChange={setCategoryType}
					categoryOptions={categoryOptions}
				/>
			) : null}

			<div className="sp-real-ready-patterns-main">
				<Toolbar
					sort={trend}
					onSortChange={setTrend}
					columns={column}
					onColumnsChange={setColumn}
					favoritesCount={wishListArr?.length || 0}
					showFavoritesOnly={showWishList}
					onToggleFavorites={() => setShowWishList((v) => !v)}
					onRefresh={_fetchFile}
					refreshing={fetching}
				/>

				{loading ? (
					<LoadingSkeleton />
				) : !hasData ? (
					<div className="sp-real-ready-patterns-state">
						{__("No patterns available right now. Please check back later.", "testimonial-free")}
					</div>
				) : (
					<PatternGrid
						items={premadeLists}
						columns={column}
						searchQuery={searchQuery}
						freePro={freePro}
						categoryType={categoryType}
						showWishList={showWishList}
						wishListArr={wishListArr}
						setWListAction={setWListAction}
						reloadId={reloadId}
						onImport={handleImport}
						onPreview={onPreview}
					/>
				)}
			</div>
		</div>
	);
};

export default StarterSites;
