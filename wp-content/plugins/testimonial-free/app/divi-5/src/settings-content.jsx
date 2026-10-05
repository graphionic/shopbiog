/**
 * Settings Content Component
 *
 * Handles dynamic options for select fields in Divi 5 module settings
 *
 * @package Real_Testimonials_Pro
 */

// Access Divi globals
const { useState, useEffect } = window?.vendor?.wp?.element;
const { set } = window?.lodash;
const loggedFetch = window?.divi?.rest?.loggedFetch;
const ModuleGroups = window?.divi?.module?.ModuleGroups;

/**
 * Settings Content Panel Component
 *
 * @param {Object} props Component props
 * @returns {JSX.Element}
 */
const SettingsContent = ({ groupConfiguration }) => {
	const [templates, setTemplates] = useState({
		0: { label: "- Select Template -" },
	});

	// Fetch templates from REST API
	useEffect(() => {
		const fetchTemplates = async () => {
			try {
				const result = await loggedFetch({
					method: "GET",
					restRoute: "/sp-rtp/divi5/v1/saved-templates",
				});

				// loggedFetch returns the data directly or wrapped in response object
				const data = result?.data || result;

				if (data && typeof data === "object") {
					// Transform to the format expected by Divi select field
					const options = {};
					options["0"] = { label: "- Select Template -" };
					Object.entries(data).forEach(([id, label]) => {
						options[id] = { label: label };
					});

					setTemplates(options);
				}
			} catch (error) {
				console.error("Failed to fetch templates:", error);
			}
		};

		fetchTemplates();
	}, []); // Empty dependency array - fetch only once on mount

	// Update the select field options dynamically
	if (groupConfiguration?.mainContent?.component) {
		set(
			groupConfiguration,
			["mainContent", "component", "props", "fields", "templateIdInnerContent", "component", "props", "options"],
			templates
		);
	}

	return <ModuleGroups groups={groupConfiguration} />;
};

// Export the component
export { SettingsContent };
