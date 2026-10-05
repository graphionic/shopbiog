import apiFetch from "@wordpress/api-fetch";
import { useState, useEffect } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

const useChangelogData = (showSidebar) => {
	const [status, setStatus] = useState("loading");
	const [changelog, setChangelog] = useState("");
	const [errorMessage, setErrorMessage] = useState("");

	useEffect(() => {
		if (!showSidebar) {
			return;
		}
		const fetchApi = async () => {
			setStatus("loading");
			try {
				const response = await apiFetch({ path: "/sp-rtp/v2/changelog" });
				if (response?.changelog) {
					setChangelog(response.changelog);
					setStatus("success");
				} else {
					setStatus("error");
					setErrorMessage(__("No changelog found.", "testimonial-free"));
				}
			} catch (error) {
				setStatus("error");
				setErrorMessage(error?.message || __("Something went wrong.", "testimonial-free"));
			}
		};
		fetchApi();
	}, [showSidebar]);

	return { status, changelog, errorMessage };
};

export default useChangelogData;
