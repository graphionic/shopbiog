import { __ } from "@wordpress/i18n";
import {
	TestimonialCollectionFormIcon,
	SaveTemplatesIcon,
	ExportImportTestimonialsIcon,
	PatternLibraryIcon,
	SEOSchemaMarkupModuleIcon,
	// Pro
	TestimonialRatingFilterIcon,
	TestimonialCategoryFilterIcon,
	AjaxTestimonialSearchIcon,
	ProBuiltInTemplatesIcon,
	AverageRatingDisplayIcon,
	ReviewerFallbackImageIcon,
	CustomInformationFieldsModuleIcon,
	ReviewerSocialProfilesModuleIcon,
	InfiniteScrollPaginationIcon,
	AssignTestimonialToCategoriesIcon,
	DragDropFieldOrderingIcon,
	CustomIconLibraryIcon,
	ContentDisplayManagementIcon,
	PopupInlineVideoPlayIcon,
	MotionEffectModuleIcon,
	MultipleTestimonialFormsIcon,
	DragDropFormBuilderIcon,
	AjaxFormSubmissionIcon,
	EmailNotificationsApprovalIcon,
	SpamProtectionModuleIcon,
	ReviewerCountrySelectorIcon,
	VideoTestimonialRecorderIcon,
	MailchimpModuleIcon,
	WhiteLabelBrandingModuleIcon,
	RoleManagementModuleIcon,
	SkeletonLoaderModuleIcon,
} from "./Icons";
import { generateDocLink } from "@testimonial/constants";

// Central source of truth for the Modules page.
// `freeModules` render a working toggle (persisted under the `modules` option key).
// `proModules` are upsell cards: they show a PRO badge instead of a toggle and have no `key`.
// Links: pass `videoLink`, `demoLink`, and/or `docLink` to render the matching chip.

export const freeModules = [
	{
		id: 2,
		key: "testimonial_form",
		Icon: TestimonialCollectionFormIcon,
		label: __("Testimonial Collection Form", "testimonial-free"),
		desc: __(
			"Turning this off will hide the Testimonial Forms menu and stop loading related features.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/modules#testimonial-collection-form"),
	},
	{
		id: 3,
		key: "saved_templates",
		Icon: SaveTemplatesIcon,
		label: __("Saved Templates", "testimonial-free"),
		desc: __(
			"This lets you create unlimited templates by converting blocks into shortcodes to reuse anywhere.",
			"testimonial-free"
		),
		videoLink: "#",
		docLink: generateDocLink("dashboard/modules#saved-templates"),
	},
	{
		id: 4,
		key: "import_export",
		Icon: ExportImportTestimonialsIcon,
		label: __("Export/Import Testimonials", "testimonial-free"),
		desc: __(
			"This allows you to export and import testimonials easily for backup, migration, or transferring data between websites.",
			"testimonial-free"
		),
		videoLink: "#",
		docLink: generateDocLink("dashboard/modules#export-import-testimonials"),
	},
	{
		id: 5,
		key: "pattern_library",
		Icon: PatternLibraryIcon,
		label: __("Ready Patterns Library", "testimonial-free"),
		desc: __(
			"Access a large collection of pre-designed patterns to build layouts faster with consistent design quality.",
			"testimonial-free"
		),
		demoLink: "#",
		docLink: generateDocLink("dashboard/modules#ready-patterns-library"),
	},
	{
		id: 6,
		key: "seo_schema_markup",
		Icon: SEOSchemaMarkupModuleIcon,
		label: __("SEO Schema Markup", "testimonial-free"),
		desc: __(
			"It enables structured data for your testimonials to improve search engine visibility and enhance how they appear in search results.",
			"testimonial-free"
		),
		videoLink: "#",
		docLink: generateDocLink("dashboard/modules#seo-schema-markup"),
	},
];

export const proModules = [
	{
		id: 101,
		pro: true,
		Icon: TestimonialRatingFilterIcon,
		label: __("Testimonial Rating Filter", "testimonial-free"),
		desc: __(
			"This enables live frontend filtering so visitors can instantly view testimonials based on star rating.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("blocks/filter-by-rating"),
	},
	{
		id: 102,
		pro: true,
		Icon: TestimonialCategoryFilterIcon,
		label: __("Testimonial Category Filter", "testimonial-free"),
		desc: __(
			"This enables live frontend filtering so visitors can instantly browse and view testimonials by selected categories.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("blocks/filter-by-group"),
	},
	{
		id: 103,
		pro: true,
		Icon: AjaxTestimonialSearchIcon,
		label: __("Ajax Testimonial Search", "testimonial-free"),
		desc: __(
			"This module enables live ajax search functionality to quickly find specific testimonials.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("blocks/ajax-testimonial-search"),
	},
	{
		id: 104,
		pro: true,
		Icon: ProBuiltInTemplatesIcon,
		label: __("20+ Pro Built-In Templates", "testimonial-free"),
		desc: __(
			"Access 20+ built-in premium templates to create quick, beautiful, and professional designs effortlessly.",
			"testimonial-free"
		),
		// demoLink: "#",
	},
	{
		id: 106,
		pro: true,
		Icon: AverageRatingDisplayIcon,
		label: __("Average Rating Display", "testimonial-free"),
		desc: __(
			"This automatically calculates and displays the average rating based on all submitted testimonial ratings.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("blocks/review-summary"),
	},
	{
		id: 107,
		pro: true,
		Icon: ReviewerFallbackImageIcon,
		label: __("Reviewer Fallback Image", "testimonial-free"),
		desc: __(
			"This automatically displays a default image when a reviewer profile image is not available.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("blocks/carousel#reviewer-image"),
	},
	{
		id: 109,
		pro: true,
		Icon: CustomInformationFieldsModuleIcon,
		label: __("Custom Information Fields", "testimonial-free"),
		desc: __(
			"This allows you to add reviewer custom information fields to display additional profile details.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("dashboard/modules#custom-information-fields"),
	},
	{
		id: 110,
		pro: true,
		Icon: ReviewerSocialProfilesModuleIcon,
		label: __("Reviewer Social Profiles", "testimonial-free"),
		desc: __(
			"This lets you display reviewer social media profiles alongside testimonial content.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("dashboard/modules#reviewer-social-profiles"),
	},
	{
		id: 111,
		pro: true,
		Icon: InfiniteScrollPaginationIcon,
		label: __("Infinite Scroll Pagination", "testimonial-free"),
		desc: __(
			"This loads testimonials automatically as users scroll for a seamless browsing experience.",
			"testimonial-free"
		),
		// demoLink: "#",
	},
	{
		id: 112,
		pro: true,
		Icon: AssignTestimonialToCategoriesIcon,
		label: __("Assign Testimonial To Categories", "testimonial-free"),
		desc: __(
			"This lets you assign testimonials to categories for better organization and easier filtering or display across your layouts.",
			"testimonial-free"
		),
		// videoLink: "#",
	},
	{
		id: 113,
		pro: true,
		Icon: DragDropFieldOrderingIcon,
		label: __("Drag & Drop Field Ordering", "testimonial-free"),
		desc: __(
			"This enables rearranging testimonial fields easily using drag-and-drop controls.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("blocks/carousel#card-content"),
	},
	{
		id: 114,
		pro: true,
		Icon: CustomIconLibraryIcon,
		label: __("Custom Icon Library", "testimonial-free"),
		desc: __(
			"This provides access to a customizable icon library for rating, navigation, and visual elements.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("blocks/ajax-testimonial-search#search-button"),
	},
	{
		id: 115,
		pro: true,
		Icon: ContentDisplayManagementIcon,
		label: __("Content Display Management", "testimonial-free"),
		desc: __(
			"This lets you control which testimonial elements appear in the layout, such as reviewer details, ratings, images, and content.",
			"testimonial-free"
		),
		// videoLink: "#",
	},
	{
		id: 116,
		pro: true,
		Icon: PopupInlineVideoPlayIcon,
		label: __("Popup & Inline Video Play", "testimonial-free"),
		desc: __(
			"This allows video testimonials to play in a popup or directly inline within the testimonial layout.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("blocks/carousel#reviewer-image"),
	},
	{
		id: 117,
		pro: true,
		Icon: MotionEffectModuleIcon,
		label: __("Motion Effects", "testimonial-free"),
		desc: __(
			"This enables you to add advanced hover effects, overlays, and animations for more dynamic presentations.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#motion-effects"),
	},
	{
		id: 118,
		pro: true,
		Icon: MultipleTestimonialFormsIcon,
		label: __("Multiple Testimonial Forms", "testimonial-free"),
		desc: __(
			"This allows visitors to submit testimonials directly from the frontend, helping you collect feedback easily.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("classic/testimonial-forms"),
	},
	{
		id: 119,
		pro: true,
		Icon: DragDropFormBuilderIcon,
		label: __("Drag And Drop Form Builder", "testimonial-free"),
		desc: __(
			"Easily build forms by dragging and dropping elements—no coding skills needed for quick setup.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("blocks/submission-form#form-builder"),
	},
	{
		id: 120,
		pro: true,
		Icon: AjaxFormSubmissionIcon,
		label: __("Ajax Form Submission", "testimonial-free"),
		desc: __(
			"This enables testimonial forms to submit instantly using AJAX, allowing users to send their feedback without reloading the page.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#ajax-form-submission"),
	},
	{
		id: 121,
		pro: true,
		Icon: EmailNotificationsApprovalIcon,
		label: __("Email Notifications & Approval", "testimonial-free"),
		desc: __(
			"This sends email alerts when testimonials are submitted and lets administrators review and manage their status.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#email-notifications-approval"),
	},
	{
		id: 122,
		pro: true,
		Icon: SpamProtectionModuleIcon,
		label: __("Spam Protection (ReCAPTCHA)", "testimonial-free"),
		desc: __(
			"This protects testimonial submission forms from spam by adding Google reCAPTCHA verification to validate real users.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("dashboard/modules#spam-protection-recaptcha"),
	},
	{
		id: 123,
		pro: true,
		Icon: ReviewerCountrySelectorIcon,
		label: __("Reviewer Country Selector", "testimonial-free"),
		desc: __(
			"This lets reviewers select their location when submitting testimonials, helping display where the feedback is coming from.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#reviewer-country-selector"),
	},
	{
		id: 124,
		pro: true,
		Icon: VideoTestimonialRecorderIcon,
		label: __("Video Testimonial Recorder", "testimonial-free"),
		desc: __(
			"This provides an interactive recorder that lets users record, preview, and submit video testimonials directly from the browser.",
			"testimonial-free"
		),
		// demoLink: "#",
		// docLink: generateDocLink("dashboard/modules#video-testimonial-recorder"),
	},
	{
		id: 126,
		pro: true,
		Icon: MailchimpModuleIcon,
		label: __("Mailchimp", "testimonial-free"),
		desc: __(
			"Mailchimp is an email marketing platform for campaigns, automation, audience management, analytics, and business growth.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#mailchimp"),
	},
	{
		id: 127,
		pro: true,
		Icon: WhiteLabelBrandingModuleIcon,
		label: __("White Label Branding", "testimonial-free"),
		desc: __(
			"This allows you to replace Real Testimonial branding with your own brand name and logo for a fully customized client experience.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#white-label-branding"),
	},
	{
		id: 128,
		pro: true,
		Icon: RoleManagementModuleIcon,
		label: __("Role Management", "testimonial-free"),
		desc: __(
			"This enables you to control user access by assigning permissions to create, edit, or manage galleries based on WordPress roles.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#role-management"),
	},
	{
		id: 129,
		pro: true,
		Icon: SkeletonLoaderModuleIcon,
		label: __("Skeleton Loader", "testimonial-free"),
		desc: __(
			"Show content placeholders while loading to create a smoother, faster, and more engaging user experience.",
			"testimonial-free"
		),
		// videoLink: "#",
		// docLink: generateDocLink("dashboard/modules#skeleton-loader"),
	},
];
