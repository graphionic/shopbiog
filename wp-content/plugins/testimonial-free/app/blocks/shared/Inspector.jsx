import { __ } from "@wordpress/i18n";
import { TabControls } from "@testimonial/components";
import { inArray } from "@testimonial/controls";
import { PanelBody } from "@wordpress/components";
import {
	LayoutsGeneralTab,
	QueryBuilderGeneralTab,
	CardDesignGeneralTab,
	TestimonialContentGeneralTab,
	StarRatingGeneralTab,
	ReviewerImageGeneralTab,
	ReviewerDetailsGeneralTab,
	MotionEffectsGeneralTab,
	SliderSettingsGeneralTab,
	NavigationArrowGeneralTab,
	PaginationDotsGeneralTab,
} from "./GeneralTab";
import {
	CardDesignStyleTab,
	TestimonialContentStyleTab,
	StarRatingStyleTab,
	ReviewerImageStyleTab,
	ReviewerDetailsStyleTab,
	NavigationArrowStyleTab,
	PaginationDotsStyleTab,
} from "./StyleTab";
import { useTogglePanelBody } from "../../context";
import { AdvancedGeneralTab, AdvancedTab, VisibilityTab } from "./AdvancedTab";
import { ProPanelTitle } from "../../components/proControls";

const SharedInspector = ({ attributes, setAttributes }) => {
	const { blockName, enablePaginationDots, enableNavigationArrow } = attributes;
	const { togglePanelBody, openedPanelBody, activeTab, toggleActiveTab, highlightedPanel } = useTogglePanelBody();
	const checkOpenedPanelBody = (accordionName) => {
		return openedPanelBody === accordionName ? true : false;
	};
	// Flash the panel a card-section click just opened (see editor.scss keyframes).
	const highlightClass = (accordionName) => {
		return highlightedPanel === accordionName ? "active" : undefined;
	};

	return (
		<>
			{/* Layouts Panel */}
			<PanelBody
				title={__("Layouts", "testimonial-free")}
				opened={checkOpenedPanelBody("defaultOpen")}
				onToggle={() => togglePanelBody("defaultOpen")}
				initialOpen={true}
			>
				{inArray(["grid", "polaroid-grid", "bento-grid", "masonry"], blockName) ? (
					<LayoutsGeneralTab attributes={attributes} setAttributes={setAttributes} />
				) : (
					<TabControls
						attributes={attributes}
						setAttributes={setAttributes}
						GeneralTab={LayoutsGeneralTab}
						SliderTab={SliderSettingsGeneralTab}
						tabName={activeTab}
						setTabName={toggleActiveTab}
					/>
				)}
			</PanelBody>

			{/* Query Builder Panel */}
			<PanelBody
				title={__("Query Builder", "testimonial-free")}
				opened={checkOpenedPanelBody("query-builder")}
				onToggle={() => togglePanelBody("query-builder")}
			>
				<QueryBuilderGeneralTab attributes={attributes} setAttributes={setAttributes} />
			</PanelBody>

			{/* Navigation Arrow And Pagination Dots */}
			{inArray(["slider", "carousel"], blockName) && (
				<>
					{enableNavigationArrow && (
						<PanelBody
							title={__("Navigation Arrow", "testimonial-free")}
							opened={checkOpenedPanelBody("navigation-arrow")}
							className={highlightClass("navigation-arrow")}
							onToggle={() => togglePanelBody("navigation-arrow")}
						>
							<TabControls
								attributes={attributes}
								setAttributes={setAttributes}
								GeneralTab={NavigationArrowGeneralTab}
								StyleTab={NavigationArrowStyleTab}
								tabName={activeTab}
								setTabName={toggleActiveTab}
							/>
						</PanelBody>
					)}
					{enablePaginationDots && (
						<PanelBody
							title={__("Pagination Dots", "testimonial-free")}
							opened={checkOpenedPanelBody("pagination-dots")}
							className={highlightClass("pagination-dots")}
							onToggle={() => togglePanelBody("pagination-dots")}
						>
							<TabControls
								attributes={attributes}
								setAttributes={setAttributes}
								GeneralTab={PaginationDotsGeneralTab}
								StyleTab={PaginationDotsStyleTab}
								tabName={activeTab}
								setTabName={toggleActiveTab}
							/>
						</PanelBody>
					)}
				</>
			)}

			{/* Card Content Panel */}
			<PanelBody
				title={__("Card Content", "testimonial-free")}
				opened={checkOpenedPanelBody("card-design")}
				onToggle={() => togglePanelBody("card-design")}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					GeneralTab={CardDesignGeneralTab}
					StyleTab={CardDesignStyleTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>

			{/* Testimonial Content Panel */}
			<PanelBody
				title={__("Testimonial Content", "testimonial-free")}
				opened={checkOpenedPanelBody("testimonial-content")}
				className={highlightClass("testimonial-content")}
				onToggle={() => togglePanelBody("testimonial-content")}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					GeneralTab={TestimonialContentGeneralTab}
					StyleTab={TestimonialContentStyleTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>

			{/* Star Rating Panel */}
			<PanelBody
				title={__("Star Rating", "testimonial-free")}
				opened={checkOpenedPanelBody("star-rating")}
				className={highlightClass("star-rating")}
				onToggle={() => togglePanelBody("star-rating")}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					GeneralTab={StarRatingGeneralTab}
					StyleTab={StarRatingStyleTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>

			{/* Reviewer Image Panel */}
			<PanelBody
				title={__("Reviewer Image", "testimonial-free")}
				opened={checkOpenedPanelBody("reviewer-image")}
				className={highlightClass("reviewer-image")}
				onToggle={() => togglePanelBody("reviewer-image")}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					GeneralTab={ReviewerImageGeneralTab}
					StyleTab={ReviewerImageStyleTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>

			{/* Reviewer Details Panel */}
			<PanelBody
				title={__("Reviewer Details", "testimonial-free")}
				opened={checkOpenedPanelBody("reviewer-details")}
				className={highlightClass("reviewer-details")}
				onToggle={() => togglePanelBody("reviewer-details")}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					GeneralTab={ReviewerDetailsGeneralTab}
					StyleTab={ReviewerDetailsStyleTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>

			{/* Motion Effects Panel — Pro-only; renders the upsell teaser. */}
			<PanelBody
				title={<ProPanelTitle label={__("Motion Effects", "testimonial-free")} />}
				opened={checkOpenedPanelBody("motion-effects")}
				onToggle={() => togglePanelBody("motion-effects")}
			>
				<MotionEffectsGeneralTab attributes={attributes} setAttributes={setAttributes} />
			</PanelBody>

			{/* Advanced Settings Panel */}
			<PanelBody
				title={__("Advanced Settings", "testimonial-free")}
				opened={checkOpenedPanelBody("advanced-settings")}
				onToggle={() => {
					togglePanelBody("advanced-settings");
					toggleActiveTab("advanced-general");
				}}
			>
				<TabControls
					attributes={attributes}
					setAttributes={setAttributes}
					AdvancedGeneralTab={AdvancedGeneralTab}
					VisibilityTab={VisibilityTab}
					AdvancedTab={AdvancedTab}
					tabName={activeTab}
					setTabName={toggleActiveTab}
				/>
			</PanelBody>
		</>
	);
};

export default SharedInspector;
