import { TabPanel } from "@wordpress/components";
import { FilterTabIcon, GeneralIcon, ReviewsTabIcon, SliderStyleIcon, StyleIcon } from "./Icons";
import "./editor.scss";

const TabControls = ({
	attributes,
	setAttributes,
	tabName = "general",
	setTabName = () => {},
	displayIcon = true,
	GeneralTab = "",
	StyleTab = "",
	AdvancedTab = "",
	VisibilityTab = "",
	SliderTab = "",
	ReviewsTab = "",
	FilterTab = "",
	AdvancedGeneralTab = "",
}) => {
	const Tabs = [];

	if (GeneralTab) {
		Tabs.push({
			name: "general",
			title: <span className="sp-real-tab-panel-title">{displayIcon && GeneralIcon()} Settings</span>,
			className: "sp-real-general-tab",
		});
	}

	if (AdvancedGeneralTab) {
		Tabs.push({
			name: "advanced-general",
			title: <span className="sp-real-tab-panel-title">General</span>,
			className: "sp-real-advanced-general-tab",
		});
	}

	if (StyleTab) {
		Tabs.push({
			name: "style",
			title: <span className="sp-real-tab-panel-title">{displayIcon && StyleIcon()} Style</span>,
			className: "sp-real-style-tab",
		});
	}

	if (SliderTab) {
		Tabs.push({
			name: "slider",
			title: (
				<span className="sp-real-tab-panel-title">
					{displayIcon && SliderStyleIcon()} {attributes?.blockName || "Slider"}
				</span>
			),
			className: "sp-real-style-tab",
		});
	}

	if (VisibilityTab) {
		Tabs.push({
			name: "visibility",
			title: <span className="sp-real-tab-panel-title">Visibility</span>,
			className: "sp-real-visibility-tab",
		});
	}

	if (AdvancedTab) {
		Tabs.push({
			name: "advanced",
			title: <span className="sp-real-tab-panel-title">Advanced</span>,
			className: "sp-real-advanced-tab",
		});
	}

	if (ReviewsTab) {
		Tabs.push({
			name: "reviews",
			title: <span className="sp-real-tab-panel-title">{displayIcon && ReviewsTabIcon()} Reviews</span>,
			className: "sp-real-reviews-tab",
		});
	}

	if (FilterTab) {
		Tabs.push({
			name: "filter",
			title: <span className="sp-real-tab-panel-title">{displayIcon && FilterTabIcon()} Filter</span>,
			className: "sp-real-filter-tab",
		});
	}

	return (
		<TabPanel
			className="sp-testimonial-tab-panel"
			activeClass="active-tab"
			initialTabName={tabName}
			onSelect={(newVal) => setTabName(newVal)}
			tabs={Tabs}
		>
			{(tab) => {
				return (
					<>
						{tab.name === "general" && GeneralTab && (
							<GeneralTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "advanced-general" && AdvancedGeneralTab && (
							<AdvancedGeneralTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "style" && StyleTab && (
							<StyleTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "slider" && SliderTab && (
							<SliderTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "visibility" && VisibilityTab && (
							<VisibilityTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "advanced" && AdvancedTab && (
							<AdvancedTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "reviews" && ReviewsTab && (
							<ReviewsTab attributes={attributes} setAttributes={setAttributes} />
						)}
						{tab.name === "filter" && FilterTab && (
							<FilterTab attributes={attributes} setAttributes={setAttributes} />
						)}
					</>
				);
			}}
		</TabPanel>
	);
};

export default TabControls;
