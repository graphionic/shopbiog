import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import Toggle from "react-toggle";
import { CodeEditor } from "@testimonial/components";
import { UserDataInfoModal } from "./TemplateParts";
import { jsToPhpBool, phpToJsBool } from "../../functions";
import Integrations from "./Integrations";

const performanceOptions = [
	{
		option_name: "tpro_dequeue_google_fonts",
		label: __("Google Fonts", "testimonial-free"),
		infoText: __("Check this box to load Google Fonts on your site.", "testimonial-free"),
		inputType: "checkbox",
	},
];

const advancedOptions = [
	{
		option_name: "testimonial_data_remove",
		label: __("Clean-up Data on Deletion", "testimonial-free"),
		infoText: __("Remove all plugin data completely when the plugin is uninstalled.", "testimonial-free"),
		inputType: "checkbox",
	},
];

const renderOptionRow = ({ option, pluginSettings, updateSettingsOption, index }) => {
	const { option_name, label, infoText, inputType, options, unit, defaultValue, dependsOn } = option;

	// Honor the dependency rule — hide the row when its dependency is off.
	if (dependsOn && !phpToJsBool(pluginSettings?.[dependsOn])) {
		return null;
	}

	const optionValue = pluginSettings?.[option_name];
	// Fall back to the field default when nothing is stored yet (e.g. fresh install).
	const resolvedValue = optionValue !== undefined ? optionValue : defaultValue;
	const checkboxValue = phpToJsBool(resolvedValue);
	// The classic framework stores numeric options as { all: "24" }; unwrap for display.
	const numberValue = resolvedValue?.all ?? resolvedValue ?? "";

	return (
		<Fragment key={option_name}>
			{index !== 0 && <div className="sp-real-settings-separator"></div>}
			<div className="sp-real-settings-option sp-d-flex sp-align-center sp-justify-between">
				<span className="sp-real-component-title sp-d-flex sp-flex-col sp-gap-4px">
					{label}
					{infoText && <span className="sp-real-component-help">{infoText}</span>}
				</span>
				{inputType === "checkbox" && (
					<input
						type="checkbox"
						className="sp-real-settings-checkbox"
						checked={checkboxValue}
						onChange={() => updateSettingsOption(option_name, jsToPhpBool(!checkboxValue))}
					/>
				)}
				{inputType === "toggle" && (
					<Toggle
						icons={false}
						checked={checkboxValue}
						onChange={() => updateSettingsOption(option_name, jsToPhpBool(!checkboxValue))}
					/>
				)}
				{inputType === "text" && (
					<input
						type="text"
						className="sp-real-settings-text-input"
						value={resolvedValue || ""}
						onChange={(e) => updateSettingsOption(option_name, e.target.value)}
					/>
				)}
				{inputType === "button" && (
					<button
						className="sp-real-flush-cache-btn sp-cursor-pointer"
						onClick={() => {
							updateSettingsOption(option_name, jsToPhpBool(!checkboxValue));
						}}
					>
						{__("Flush Cache", "testimonial-free")}
					</button>
				)}
				{inputType === "select" && (
					<select
						className="sp-real-settings-select"
						value={resolvedValue || options?.[0]?.value}
						onChange={(e) => updateSettingsOption(option_name, e.target.value)}
					>
						{options?.map((opt) => (
							<option key={opt.value} value={opt.value}>
								{opt.label}
							</option>
						))}
					</select>
				)}
				{inputType === "number" && (
					<div className="sp-real-settings-number-input-wrapper sp-d-flex sp-align-center sp-gap-4px">
						<input
							type="number"
							min="0"
							className="sp-real-settings-number-input"
							value={numberValue}
							onChange={(e) => updateSettingsOption(option_name, { all: e.target.value })}
						/>
						{unit && <span className="sp-real-settings-number-unit">{unit}</span>}
					</div>
				)}
				{inputType === "button_set" && (
					<div className="sp-real-settings-button-set sp-d-flex">
						{options?.map((opt) => (
							<button
								key={opt.value}
								type="button"
								className={`sp-real-settings-button-set-option sp-cursor-pointer${
									(resolvedValue || options?.[0]?.value) === opt.value ? " active" : ""
								}`}
								onClick={() => updateSettingsOption(option_name, opt.value)}
							>
								{opt.label}
							</button>
						))}
					</div>
				)}
			</div>
		</Fragment>
	);
};

const customMenuOptions = [
	{
		option_name: "tpro_singular_name",
		label: __("Singular name", "testimonial-free"),
		infoText: __("The singular name for testimonial post type.", "testimonial-free"),
		inputType: "text",
	},
	{
		option_name: "tpro_plural_name",
		label: __("Plural name", "testimonial-free"),
		infoText: __("The plural name for testimonial post type.", "testimonial-free"),
		inputType: "text",
	},
];

const controlAssetsOptions = [
	{
		option_name: "tf_dequeue_slick_js",
		label: __("Swiper JS", "testimonial-free"),
		infoText: __("Load Swiper JavaScript library.", "testimonial-free"),
		inputType: "toggle",
	},
	{
		option_name: "tf_dequeue_slick_css",
		label: __("Swiper CSS", "testimonial-free"),
		infoText: __("Load Swiper CSS stylesheet.", "testimonial-free"),
		inputType: "toggle",
	},
	{
		option_name: "tf_dequeue_fa_css",
		label: __("Font Awesome CSS", "testimonial-free"),
		infoText: __("Load Font Awesome CSS stylesheet.", "testimonial-free"),
		inputType: "toggle",
	},
];

export const CustomMenu = ({ pluginSettings, updateSettingsOption }) => {
	return (
		<div className="sp-real-dashboard-advanced-settings sp-d-flex sp-flex-col sp-gap-24px">
			{customMenuOptions?.map((option, index) =>
				renderOptionRow({ option, pluginSettings, updateSettingsOption, index })
			)}
		</div>
	);
};

export const ControlAssets = ({ pluginSettings, updateSettingsOption }) => {
	return (
		<div className="sp-real-dashboard-advanced-settings sp-d-flex sp-flex-col sp-gap-24px">
			{controlAssetsOptions?.map((option, index) =>
				renderOptionRow({ option, pluginSettings, updateSettingsOption, index })
			)}
		</div>
	);
};

export const Performance = ({ pluginSettings, updateSettingsOption }) => {
	return (
		<div className="sp-real-dashboard-advanced-settings sp-d-flex sp-flex-col sp-gap-24px">
			{performanceOptions?.map((option, index) =>
				renderOptionRow({ option, pluginSettings, updateSettingsOption, index })
			)}
		</div>
	);
};

export const AdvancedControls = ({ pluginSettings, updateSettingsOption }) => {
	// User data info modal.
	const [isOpenModal, setOpenModal] = useState(false);
	const closeModal = () => setOpenModal(false);

	return (
		<div className="sp-real-dashboard-advanced-settings sp-d-flex sp-flex-col sp-gap-24px">
			{advancedOptions?.map((option, index) =>
				renderOptionRow({ option, pluginSettings, updateSettingsOption, index })
			)}
			{isOpenModal && <UserDataInfoModal closeModal={closeModal} />}
		</div>
	);
};

export const CustomCssAndJs = ({ pluginSettings, updateSettingsOption }) => {
	// Settings from wp_enqueue_code_editor(); null when the user has switched
	// syntax highlighting off, in which case CodeEditor renders a plain textarea.
	const codeEditor = window.sp_real_admin_dashboard_localize?.code_editor || {};

	return (
		<div className="sp-real-settings-custom-assets sp-d-flex sp-flex-col sp-gap-24px">
			<CodeEditor
				label={__("Custom CSS", "testimonial-free")}
				attributes={pluginSettings?.custom_css}
				onChange={(value) => updateSettingsOption("custom_css", value)}
				height="147px"
				resizable
				settings={codeEditor.css}
			/>
			<CodeEditor
				label={__("Custom JS", "testimonial-free")}
				attributes={pluginSettings?.custom_js}
				onChange={(value) => updateSettingsOption("custom_js", value)}
				defaultLanguage="javascript"
				height="147px"
				resizable
				settings={codeEditor.js}
			/>
		</div>
	);
};

export { Integrations };
