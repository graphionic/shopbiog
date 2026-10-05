export const getSettings = (state) => state.apiData?.settings || {};
export const getDashboardInfo = (state) => state.apiData?.dashboardInfo || {};
export const getSaveTemplates = (state) => state.templateData || {};
export const isLoading = (state) => state.isLoading;
export const isSaving = (state) => state.isSaving;
export const isTemplatesLoading = (state) => state.templatesLoading;
