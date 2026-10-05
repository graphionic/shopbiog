import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { ControlAssets, CustomMenu } from "./SettingsTabContent";

/**
 * Horizontal sub-tab definitions for the Classic Settings section.
 * These are the legacy framework settings surfaced in the React dashboard.
 */
const classicSubTabs = [
	{
		label: __("Custom Menu", "testimonial-free"),
		value: "custom-menu",
	},
	{
		label: __("Control Assets", "testimonial-free"),
		value: "control-assets",
	},
];

/**
 * ClassicSettings — groups the classic (legacy framework) settings behind a
 * horizontal sub-tab bar. The rows themselves are rendered by the existing
 * CustomMenu / ControlAssets components from SettingsTabContent.
 *
 * @param {Object}   props                      Component props.
 * @param {Object}   props.pluginSettings       Current tab settings slice.
 * @param {Function} props.updateSettingsOption Option updater callback.
 * @return {JSX.Element} Rendered component.
 */
const ClassicSettings = ({ pluginSettings, updateSettingsOption }) => {
	const [activeSubTab, setActiveSubTab] = useState("custom-menu");

	return (
		<div className="sp-real-classic-settings">
			{/* Horizontal sub-tab bar. */}
			<ul className="sp-real-classic-sub-tabs sp-d-flex">
				{classicSubTabs?.map(({ label, value }) => (
					// eslint-disable-next-line jsx-a11y/no-noninteractive-element-interactions
					<li
						key={value}
						className={`sp-real-classic-sub-tab sp-cursor-pointer${activeSubTab === value ? " active" : ""}`}
						onClick={() => setActiveSubTab(value)}
					>
						{label}
					</li>
				))}
			</ul>
			{/* Active sub-tab content. */}
			<div className="sp-real-classic-sub-tab-content">
				{activeSubTab === "custom-menu" && (
					<CustomMenu pluginSettings={pluginSettings} updateSettingsOption={updateSettingsOption} />
				)}
				{activeSubTab === "control-assets" && (
					<ControlAssets pluginSettings={pluginSettings} updateSettingsOption={updateSettingsOption} />
				)}
			</div>
		</div>
	);
};

export default ClassicSettings;
