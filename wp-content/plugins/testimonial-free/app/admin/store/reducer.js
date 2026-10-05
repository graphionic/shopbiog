const DEFAULT_STATE = {
	apiData: {},
	isLoading: false,
	isSaving: false,
	templateData: {},
	templatesLoading: false,
	templatesError: null,
};

const reducer = (state = DEFAULT_STATE, action) => {
	switch (action.type) {
		case "SET_API_DATA":
			return { ...state, apiData: action.data };
		case "SET_LOADING":
			return { ...state, isLoading: action.isLoading };
		case "SET_SAVING":
			return { ...state, isSaving: action.isSaving };
		case "SET_TEMPLATE_DATA":
			return { ...state, templateData: action.templateData };
		case "SET_TEMPLATES_LOADING":
			return { ...state, templatesLoading: action.isLoading };
		case "SET_TEMPLATES_ERROR":
			return { ...state, templatesError: action.error };
		default:
			return state;
	}
};

export default reducer;
