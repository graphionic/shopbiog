import { __ } from "@wordpress/i18n";
import { useMemo } from "@wordpress/element";
import { toast } from "react-hot-toast";
import { useSelect, useDispatch } from "@wordpress/data";
import ToggleCard from "../../templates/ToggleCard";
import { STORE_NAME } from "../../store";

const BLOCK_GROUPS = [
	{ key: "main", label: __("Main Blocks", "testimonial-free") },
	{ key: "child", label: __("Child Blocks", "testimonial-free") },
	{ key: "social", label: __("Social Reviews", "testimonial-free") },
];

const BlockVisibility = ({ showHeading = true }) => {
	const settings = useSelect((select) => select(STORE_NAME).getSettings());
	const { allBlockList, adminUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);
	const blockVisibility = settings?.active_blocks || [];
	const { saveSettings } = useDispatch(STORE_NAME);

	// notification message.
	const show_notification = (block) => {
		const message = block.show
			? __("Block disabled successfully", "testimonial-free")
			: __("Block enabled successfully", "testimonial-free");
		toast.success(message, { style: { marginTop: "20px", fontSize: "14px" } });
	};

	// Handler to toggle block visibility.
	const blockShowHideHandler = (name) => {
		const updatedVisibility = blockVisibility?.map((item) => {
			if (name === item.name) {
				show_notification(item);
				return { ...item, show: !item.show };
			}
			return item;
		});
		saveSettings({ settings: { ...settings, active_blocks: updatedVisibility } });
	};

	// Group active blocks by category derived from dashboardInfo.allBlockList.
	// Anything not listed as `child` or `social` falls into the `main` bucket
	// (covers main, parent and the synthetic shortcode entry).
	const groupedBlocks = useMemo(() => {
		const childSet = new Set(allBlockList?.child || []);
		const socialSet = new Set(allBlockList?.social || []);
		const groups = { main: [], child: [], social: [] };

		blockVisibility.forEach((block) => {
			if (childSet.has(block.name)) {
				groups.child.push(block);
			} else if (socialSet.has(block.name)) {
				groups.social.push(block);
			} else {
				groups.main.push(block);
			}
		});

		return groups;
	}, [blockVisibility, allBlockList]);

	return (
		<div className="sp-real-visibility-page">
			{showHeading && (
				<div className="sp-real-dashboard-page-header sp-d-flex sp-flex-col sp-gap-10px">
					<span className="sp-real-dashboard-page-header__label">
						{__("Control Blocks", "testimonial-free")}
					</span>
					<span className="sp-real-dashboard-page-header__desc">
						{__("Turn blocks on or off as needed to improve performance.", "testimonial-free")}
					</span>
				</div>
			)}
			{BLOCK_GROUPS.map(({ key, label }, groupIndex) => {
				const items = groupedBlocks[key];
				if (!items || items.length === 0) {
					return null;
				}
				return (
					<div key={key} className="sp-real-block-visibility-group">
						{groupIndex > 0 && (
							<div className="sp-real-dashboard-page-header sp-d-flex sp-flex-col sp-gap-10px sp-real-child-blocks-heading">
								<span className="sp-real-dashboard-page-header__label">{label}</span>
							</div>
						)}
						<div className="sp-real-all-block-list sp-d-grid sp-grid-cols-3">
							{items.map((card) => (
								<ToggleCard
									key={card.name}
									attributes={card}
									blockShowHideHandler={blockShowHideHandler}
									adminUrl={adminUrl}
									isPro={allBlockList?.proBlocks.includes(card.name)}
								/>
							))}
						</div>
					</div>
				);
			})}
		</div>
	);
};

export default BlockVisibility;
