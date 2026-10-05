import apiFetch from "@wordpress/api-fetch";

export const setApiData = (data) => ({
	type: "SET_API_DATA",
	data,
});

export const setLoading = (isLoading) => ({
	type: "SET_LOADING",
	isLoading,
});

export const setSaving = (isSaving) => ({
	type: "SET_SAVING",
	isSaving,
});

export const setSaveTemplates = (templateData) => ({
	type: "SET_TEMPLATE_DATA",
	templateData,
});

/**
 * Fetch initial API data from REST API.
 */
export const fetchApiData =
	() =>
	async ({ dispatch }) => {
		dispatch(setLoading(true));
		try {
			const data = await apiFetch({
				path: "/sp-rtp/v2/settings",
			});
			dispatch(setApiData(data));
		} catch (error) {
			// eslint-disable-next-line no-console
			console.error("Error fetching data:", error.message);
		}
		dispatch(setLoading(false));
	};

/**
 * Save modified settings to the REST API.
 * The API returns the full updated state, which replaces the store data.
 *
 * @param {Object} modifiedData - The data to save (e.g. { settings: {...} } ).
 */
export const saveSettings =
	(modifiedData) =>
	async ({ dispatch }) => {
		dispatch(setSaving(true));
		try {
			const data = await apiFetch({
				path: "/sp-rtp/v2/settings",
				method: "POST",
				data: modifiedData,
			});
			dispatch(setApiData(data));
		} catch (error) {
			// eslint-disable-next-line no-console
			console.error("Error saving data:", error.message);
		}
		dispatch(setSaving(false));
	};

export const setTemplatesLoading = (isLoading) => ({
	type: "SET_TEMPLATES_LOADING",
	isLoading,
});

export const setTemplatesError = (error) => ({
	type: "SET_TEMPLATES_ERROR",
	error,
});

/**
 * Fetch saved templates from REST API with pagination and search.
 *
 * @param {string} search - Search term for filtering templates.
 * @param {number} page - Current page number.
 * @param {number} perPage - Items per page.
 */
export const fetchSavedTemplates =
	(search = "", page = 1, perPage = 10) =>
	async ({ dispatch }) => {
		dispatch(setTemplatesLoading(true));
		dispatch(setTemplatesError(null));

		const queryParams = new URLSearchParams({
			...(search && { search }),
			page: page.toString(),
			per_page: perPage.toString(),
		});

		try {
			const response = await apiFetch({
				path: `/sp-rtp/v2/saved-templates?${queryParams.toString()}`,
				method: "GET",
			});
			dispatch(setSaveTemplates(response));
		} catch (error) {
			dispatch(setTemplatesError(error.message));
			// eslint-disable-next-line no-console
			console.error("Error fetching templates:", error.message);
		}
		dispatch(setTemplatesLoading(false));
	};
