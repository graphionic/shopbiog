import { __ } from "@wordpress/i18n";
import { memo, useState } from "@wordpress/element";
import { useSelect, useDispatch } from "@wordpress/data";
import { AdvancedControls, CustomCssAndJs, Integrations, Performance } from "./SettingsTabContent";
import Tools from "./Tools";
import ClassicSettings from "./ClassicSettings";
import { checkIsEqual } from "@testimonial/controls";
import { SaveAndReset } from "./TemplateParts";
import { STORE_NAME } from "../../store";
import {
	AdditionalCodesIcon,
	AdvancedIcon,
	ChevronRightIcon,
	ClassicSettingsTabIcon,
	IntegrationsTabIcon,
	PerformanceIcon,
	ToolsIcon,
} from "./Icons";

const settingsTabNavigation = [
	{
		label: __("Integrations", "testimonial-free"),
		Icon: IntegrationsTabIcon,
		value: "integrations",
	},
	{
		label: __("Performance", "testimonial-free"),
		Icon: PerformanceIcon,
		value: "performance",
	},
	{
		label: __("Advanced Controls", "testimonial-free"),
		Icon: AdvancedIcon,
		value: "advanced",
	},
	{
		label: __("Tools", "testimonial-free"),
		Icon: ToolsIcon,
		value: "tools",
	},
	{
		label: __("Additional CSS & JS", "testimonial-free"),
		Icon: AdditionalCodesIcon,
		value: "additional",
	},
	{
		label: __("Classic Settings", "testimonial-free"),
		Icon: ClassicSettingsTabIcon,
		value: "classic-settings",
	},
];

const pluginSettingDefaultValues = {
	performance: {
		tpro_dequeue_google_fonts: false,
	},
	advanced: {
		testimonial_data_remove: false,
	},
	tools: {},
	integrations: {},
	additional: {
		custom_css: "",
		custom_js: "",
	},
	"classic-settings": {
		tpro_singular_name: "Testimonial",
		tpro_plural_name: "Testimonials",
		tf_dequeue_slick_js: true,
		tf_dequeue_slick_css: true,
		tf_dequeue_fa_css: true,
	},
};

const tabsWithoutSave = ["tools", "integrations"];

const getTabSettings = (settings, tab) => {
	const keys = Object.keys(pluginSettingDefaultValues[tab] || {});
	return keys.reduce((acc, key) => {
		acc[key] = settings[key];
		return acc;
	}, {});
};

// Resolve the tab from the URL hash, falling back to the first tab when the hash is stale/unknown.
const hashTab = window.location?.hash?.replace("#", "")?.split("=")[1];
const defaultActiveTab = settingsTabNavigation.some((tab) => tab.value === hashTab) ? hashTab : "integrations";

const SettingsPage = () => {
	const pluginSettings = useSelect((select) => select(STORE_NAME).getSettings());
	const { saveSettings } = useDispatch(STORE_NAME);

	// Tools tab (import/export) is gated by the Export/Import Testimonials module.
	const isImportExportActive = pluginSettings?.modules?.import_export?.is_active !== false;
	const visibleTabs = isImportExportActive
		? settingsTabNavigation
		: settingsTabNavigation.filter((tab) => tab.value !== "tools");

	const [activeTab, setActiveTab] = useState(defaultActiveTab);
	const [allSettingsData, setAllSettingsData] = useState(pluginSettings);
	const [tabSettingsData, setTabSettingsData] = useState(getTabSettings(pluginSettings, activeTab));
	const [isChangeAnything, setIsChangeAnything] = useState(false);

	// handle option update.
	const updateSettingsOption = (optionName, value) => {
		const updatedTabSettings = { ...tabSettingsData, [optionName]: value };
		setIsChangeAnything(!checkIsEqual(updatedTabSettings, pluginSettings));
		setTabSettingsData(updatedTabSettings);
		setAllSettingsData((prev) => ({ ...prev, ...updatedTabSettings }));
	};

	// handle tab active.
	const handleActiveTab = (newTab) => {
		setActiveTab(newTab);
		window.location.hash = `#settings=${newTab}`;
		if (tabsWithoutSave.includes(newTab)) {
			return;
		}
		const tabSettings = getTabSettings(allSettingsData, newTab);
		setIsChangeAnything(!checkIsEqual(tabSettings, pluginSettings));
		setTabSettingsData(tabSettings);
	};

	// handle setting save.
	const handleSettingsSave = () => {
		const modifiedData = { ...pluginSettings, ...tabSettingsData };
		saveSettings({ settings: modifiedData });
		setIsChangeAnything(false);
	};

	// handle setting reset.
	const handleSettingsReset = () => {
		const resetItems = pluginSettingDefaultValues[activeTab] || {};
		const updatedSettings = { ...pluginSettings, ...resetItems };
		saveSettings({ settings: updatedSettings });
	};

	return (
		<>
			<div className="sp-real-settings-page-container sp-d-flex sp-align-start">
				<div className="sp-real-setting-tabs-wrapper">
					<ul className="sp-real-setting-tabs sp-d-flex sp-flex-col sp-gap-8px">
						{visibleTabs?.map(({ label, value, Icon }) => (
							// eslint-disable-next-line jsx-a11y/no-noninteractive-element-interactions
							<li
								key={value}
								className={`sp-real-setting-tab sp-d-flex sp-cursor-pointer sp-align-center sp-justify-between${activeTab === value ? " active" : ""}`}
								onClick={() => handleActiveTab(value)}
							>
								<span className="sp-real-setting-tab-label sp-d-flex sp-align-center sp-gap-8px">
									<span className="sp-real-setting-icon">
										<Icon />
									</span>
									<span>{label}</span>
								</span>
								{activeTab === value && (
									<span className="sp-real-setting-tab-arrow">
										<ChevronRightIcon />
									</span>
								)}
							</li>
						))}
					</ul>
				</div>
				<div className="sp-real-setting-tab-content-wrapper">
					<div className="sp-real-setting-tab-content">
						{activeTab === "integrations" && <Integrations pluginSettings={allSettingsData} />}
						{activeTab === "performance" && (
							<Performance pluginSettings={tabSettingsData} updateSettingsOption={updateSettingsOption} />
						)}
						{activeTab === "advanced" && (
							<AdvancedControls
								pluginSettings={tabSettingsData}
								updateSettingsOption={updateSettingsOption}
							/>
						)}
						{activeTab === "tools" && isImportExportActive && <Tools />}
						{activeTab === "additional" && (
							<CustomCssAndJs
								pluginSettings={tabSettingsData}
								updateSettingsOption={updateSettingsOption}
							/>
						)}
						{activeTab === "classic-settings" && (
							<ClassicSettings
								pluginSettings={tabSettingsData}
								updateSettingsOption={updateSettingsOption}
							/>
						)}
						{!tabsWithoutSave.includes(activeTab) && (
							<SaveAndReset
								onSave={handleSettingsSave}
								onReset={handleSettingsReset}
								isChanged={isChangeAnything}
							/>
						)}
					</div>
				</div>
			</div>
		</>
	);
};

export default memo(SettingsPage);
