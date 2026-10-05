import { __ } from "@wordpress/i18n";
import { PanelBody } from "@wordpress/components";
import { TabControls } from "@testimonial/components";
import { useTogglePanelBody } from "../../context";
import {
	EmailNotificationGeneralTab,
	FormBuilderGeneralTab,
	MessageSettingsGeneralTab,
	SubmitButtonGeneralTab,
	MessageSettingsStyleTab,
	SubmitButtonStyleTab,
	FormBuilderStyleTab,
	InputFieldsGeneralTab,
	InputFieldsStyleTab,
} from "./SettingsTabs";
import { AdvancedTab, VisibilityTab } from "../shared/AdvancedTab";
import { inArray } from "@testimonial/controls";
import { MotionEffectsGeneralTab } from "../shared/GeneralTab";
import { ProPanelTitle } from "../../components/proControls";

const Inspector = ({ attributes, setAttributes }) => {
	const { togglePanelBody, openedPanelBody, activeTab, toggleActiveTab } = useTogglePanelBody();

	const checkOpenedPanelBody = (tabName) => {
		return openedPanelBody === tabName ? true : false;
	};

	return (
		<>
			<PanelBody
				title={__("Form Builder", "testimonial-free")}
				opened={checkOpenedPanelBody("defaultOpen")}
				onToggle={() => togglePanelBody("defaultOpen")}
			>
				{checkOpenedPanelBody("defaultOpen") && (
					<TabControls
						attributes={attributes}
						setAttributes={setAttributes}
						GeneralTab={FormBuilderGeneralTab}
						StyleTab={FormBuilderStyleTab}
						tabName={activeTab}
						setTabName={toggleActiveTab}
					/>
				)}
			</PanelBody>
			<PanelBody
				title={__("Input Fields", "testimonial-free")}
				opened={checkOpenedPanelBody("inputFields")}
				onToggle={() => togglePanelBody("inputFields")}
			>
				{checkOpenedPanelBody("inputFields") && (
					<TabControls
						attributes={attributes}
						setAttributes={setAttributes}
						GeneralTab={InputFieldsGeneralTab}
						StyleTab={InputFieldsStyleTab}
						tabName={activeTab}
						setTabName={toggleActiveTab}
					/>
				)}
			</PanelBody>
			<PanelBody
				title={__("Submit Button", "testimonial-free")}
				opened={checkOpenedPanelBody("submitButton")}
				onToggle={() => togglePanelBody("submitButton")}
			>
				{checkOpenedPanelBody("submitButton") && (
					<TabControls
						attributes={attributes}
						setAttributes={setAttributes}
						GeneralTab={SubmitButtonGeneralTab}
						StyleTab={SubmitButtonStyleTab}
						tabName={activeTab}
						setTabName={toggleActiveTab}
					/>
				)}
			</PanelBody>
			<PanelBody
				title={__("Status & Message Settings", "testimonial-free")}
				opened={checkOpenedPanelBody("messageSettings")}
				onToggle={() => togglePanelBody("messageSettings")}
			>
				{checkOpenedPanelBody("messageSettings") && (
					<TabControls
						attributes={attributes}
						setAttributes={setAttributes}
						GeneralTab={MessageSettingsGeneralTab}
						StyleTab={MessageSettingsStyleTab}
						tabName={activeTab}
						setTabName={toggleActiveTab}
					/>
				)}
			</PanelBody>
			<PanelBody
				title={<ProPanelTitle label={__("Email Notification Control", "testimonial-free")} />}
				opened={checkOpenedPanelBody("emailNotification")}
				onToggle={() => togglePanelBody("emailNotification")}
			>
				{checkOpenedPanelBody("emailNotification") && (
					<EmailNotificationGeneralTab attributes={attributes} setAttributes={setAttributes} />
				)}
			</PanelBody>
			{/* Motion Effects Panel — Pro-only; renders the upsell teaser. */}
			<PanelBody
				title={<ProPanelTitle label={__("Motion Effects", "testimonial-free")} />}
				opened={checkOpenedPanelBody("motion-effects")}
				onToggle={() => togglePanelBody("motion-effects")}
			>
				<MotionEffectsGeneralTab attributes={attributes} setAttributes={setAttributes} />
			</PanelBody>
			<PanelBody
				title={__("Advanced", "testimonial-free")}
				opened={checkOpenedPanelBody("advanced")}
				onToggle={() => {
					togglePanelBody("advanced");
					toggleActiveTab("visibility");
				}}
			>
				{checkOpenedPanelBody("advanced") && (
					<TabControls
						attributes={attributes}
						setAttributes={setAttributes}
						displayIcon={false}
						VisibilityTab={VisibilityTab}
						AdvancedTab={AdvancedTab}
						tabName={inArray(["general", "style"], activeTab) ? "visibility" : activeTab}
						setTabName={toggleActiveTab}
					/>
				)}
			</PanelBody>
		</>
	);
};

export default Inspector;
