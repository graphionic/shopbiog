import { __ } from "@wordpress/i18n";
import {
	InputControl,
	SPRangeControl,
	ButtonGroup,
	ToggleControl,
	SelectControl,
	IconsLibrary,
	TextareaControl,
	SortableItem,
	ColorPicker,
	TypographyNew,
	Spacing,
	BackgroundControl,
	Divider,
	BoxShadow,
	PresetPicker,
	FlatBorder,
} from "@testimonial/components";
import { ALL_FORM_FIELDS, DEFAULT_FORM_FIELDS, PRO_FORM_FIELDS, textAlignmentOptions } from "@testimonial/constants";
import { AlignCenter, AlignLeft, AlignRight } from "../../icons";
import { DndIndicatorIcon } from "../../components/Icons";
import { memo, useEffect, useRef, useState } from "@wordpress/element";
import { restrictToVerticalAxis } from "@dnd-kit/modifiers";
import { DndContext, PointerSensor, useSensor, useSensors } from "@dnd-kit/core";
import { arrayMove, SortableContext, verticalListSortingStrategy } from "@dnd-kit/sortable";
import { componentSectionHeader, inArray } from "@testimonial/controls";
import { INPUT_STYLE_ITEMS } from "./icon";
import { ProFeaturesBox } from "../../components/proControls";

const FormFieldBuilder = memo(({ attributes, setAttributes }) => {
	const [openItem, setOpenItem] = useState(null);
	const [togglePopup, setTogglePopup] = useState(false);
	const popupRef = useRef(null);
	const addButtonRef = useRef(null);
	const { formFields } = attributes;

	// Dismiss the "Add New Field" popup on an outside click or Escape. The
	// listener runs on the popup's own document so it also fires inside the
	// iframed (WP 6.3+) editor canvas.
	useEffect(() => {
		if (!togglePopup) {
			return;
		}
		const doc = popupRef.current?.ownerDocument || document;
		const onPointerDown = (event) => {
			if (popupRef.current?.contains(event.target) || addButtonRef.current?.contains(event.target)) {
				return;
			}
			setTogglePopup(false);
		};
		const onKeyDown = (event) => {
			if (event.key === "Escape") {
				setTogglePopup(false);
			}
		};
		doc.addEventListener("mousedown", onPointerDown);
		doc.addEventListener("keydown", onKeyDown);
		return () => {
			doc.removeEventListener("mousedown", onPointerDown);
			doc.removeEventListener("keydown", onKeyDown);
		};
	}, [togglePopup]);

	const onChangeValue = (id, key, value) => {
		const updatedFormFields = formFields?.map((field) => {
			if (field.id === id) {
				return { ...field, [key]: value };
			}
			return field;
		});
		setAttributes({ formFields: updatedFormFields });
	};

	// restore the default field set.
	const onResetFields = () => {
		if (
			// eslint-disable-next-line no-alert
			window.confirm(
				__(
					"Are you sure you want to reset the form fields? This will remove all customizations.",
					"testimonial-free"
				)
			)
		) {
			setOpenItem(null);
			setAttributes({ formFields: DEFAULT_FORM_FIELDS });
		}
	};

	// remove a single field.
	const onDeleteField = (id) => {
		setOpenItem((prev) => (prev === id ? null : prev));
		setAttributes({ formFields: formFields.filter((field) => field.id !== id) });
	};

	// drag and drop functions.
	const sensors = useSensors(
		useSensor(PointerSensor, {
			activationConstraint: { distance: 5 },
		})
	);

	const handleDragEnd = (event) => {
		const { active, over } = event;
		if (active && over && active.id !== over.id) {
			const oldIndex = formFields.findIndex((i) => active.id === i.id);
			const newIndex = formFields.findIndex((i) => over.id === i.id);

			setAttributes({
				formFields: arrayMove(formFields, oldIndex, newIndex),
			});
		}
	};

	const addNewExistingFiled = (newField) => {
		// Catalog templates for the non-default fields carry no `id`; assign one so
		// the React key and per-form-scoped field id stay unique.
		const withId = { ...newField, id: newField.id ?? Date.now() };
		setAttributes({ formFields: [...formFields, withId] });
		setTogglePopup(false);
	};

	const visibleFields = formFields;

	// Free addable fields not yet on the form (reviewer photo only); the popup
	// also lists the Pro-only fields as locked upsell teasers.
	const [newItems, setNewItems] = useState([]);
	const onAddField = () => {
		if (togglePopup) {
			setTogglePopup(false);
			return;
		}
		const removedItems = ALL_FORM_FIELDS?.filter((item) => !formFields.find((i) => i.fieldName === item.fieldName));
		setNewItems(removedItems || []);
		setTogglePopup(true);
	};

	return (
		<div className="sp-real-tsf-field-builder sp-d-flex sp-flex-col sp-gap-8px">
			<div className="sp-real-tsf-field-builder-head sp-d-flex sp-justify-between sp-align-center">
				<span className="sp-real-tsf-field-builder-title">{__("Input Fields", "testimonial-free")}</span>
				<button type="button" className="sp-real-tsf-field-builder-reset" onClick={onResetFields}>
					{__("RESET", "testimonial-free")}
				</button>
			</div>
			<DndContext sensors={sensors} onDragEnd={handleDragEnd} modifiers={[restrictToVerticalAxis]}>
				<SortableContext items={visibleFields} strategy={verticalListSortingStrategy}>
					{visibleFields?.map(
						({ id, label, placeholder, helpText, required, fieldWidth, length, limit, fieldName }) => (
							<SortableItem key={id} id={id}>
								<div className="sp-real-form-field-item">
									<div
										onClick={() => setOpenItem((prev) => (prev === id ? null : id))}
										className="sp-real-form-field-label sp-d-flex sp-justify-between"
									>
										<span className="sp-real-form-field-label-left sp-d-flex sp-gap-4px">
											<DndIndicatorIcon />
											{label}
										</span>
										<span className="sp-real-form-field-label-right sp-d-flex sp-align-center sp-gap-8px">
											<i
												className={`sp-real-icon-angle-${openItem === id ? "up" : "down"}-solid`}
											></i>
											<button
												type="button"
												className="sp-real-form-field-delete"
												aria-label={__("Delete field", "testimonial-free")}
												onClick={(e) => {
													e.stopPropagation();
													onDeleteField(id);
												}}
											>
												&times;
											</button>
										</span>
									</div>
									{openItem === id && (
										<div className="sp-real-form-field-content">
											<InputControl
												label={__("Label", "testimonial-free")}
												attributes={label}
												onChange={(value) => onChangeValue(id, "label", value)}
											/>
											<InputControl
												label={__("Placeholder", "testimonial-free")}
												attributes={placeholder}
												onChange={(value) => onChangeValue(id, "placeholder", value)}
											/>
											<InputControl
												label={__("Note", "testimonial-free")}
												attributes={helpText}
												onChange={(value) => onChangeValue(id, "helpText", value)}
											/>
											{length && (
												<>
													<ButtonGroup
														label={
															fieldName === "tpro_testimonial_title"
																? __("Title Length", "testimonial-free")
																: __("Text Length", "testimonial-free")
														}
														attributes={length}
														items={[
															{ label: "Unlimited", value: "unlimited" },
															{ label: "Limited", value: "limited", isPro: true },
														]}
														onClick={(value) => onChangeValue(id, "length", value)}
													/>
													{length === "limited" && (
														<SPRangeControl
															label={
																fieldName === "tpro_testimonial_title"
																	? __("Title Limit", "testimonial-free")
																	: __("Text Limit", "testimonial-free")
															}
															max={200}
															units={["words", "characters"]}
															attributes={limit}
															attributesKey={"limit"}
															setAttributes={(value) =>
																onChangeValue(id, "limit", value.limit)
															}
															defaultValue={{
																unit: "words",
																value: fieldName === "tpro_testimonial_title" ? 10 : 60,
															}}
														/>
													)}
												</>
											)}
											<ToggleControl
												label={__("Required", "testimonial-free")}
												attributes={required}
												onChange={() => onChangeValue(id, "required", !required)}
											/>
											<SPRangeControl
												label={__("Field Width", "testimonial-free")}
												max={100}
												units={["%"]}
												attributes={fieldWidth}
												attributesKey={"fieldWidth"}
												setAttributes={(value) =>
													onChangeValue(id, "fieldWidth", value.fieldWidth)
												}
												defaultValue={{ unit: "%", value: 100 }}
											/>
										</div>
									)}
								</div>
							</SortableItem>
						)
					)}
				</SortableContext>
			</DndContext>
			<button type="button" className="sp-real-tsf-field-builder-add" ref={addButtonRef} onClick={onAddField}>
				{__("Add New Field", "testimonial-free")}
			</button>
			{togglePopup && (
				<div className="sp-real-tsf-filed-insert-popup sp-d-flex sp-flex-col sp-gap-8px" ref={popupRef}>
					{newItems?.map((item) => (
						<span
							className="sp-cursor-pointer"
							key={item?.fieldName}
							onClick={() => addNewExistingFiled(item)}
						>
							{item?.label}
						</span>
					))}
					{PRO_FORM_FIELDS.map((item) => (
						<span className="sp-real-tsf-pro-field" key={item.key}>
							{item.label} <span className="sp-real-tsf-pro-tag">{__("(Pro)", "testimonial-free")}</span>
						</span>
					))}
				</div>
			)}
		</div>
	);
});

export const FormBuilderGeneralTab = ({ attributes, setAttributes }) => {
	const {
		showTermsAndCondition,
		termsAndConditionLabel,
		termsAndConditionAnchorLabel,
		termsAndConditionLink,
		textAlignment,
		fieldsGap,
		maxWidth,
	} = attributes;

	return (
		<>
			<ButtonGroup
				label={__("Form Display Type", "testimonial-free")}
				items={[
					{ label: "Inline", value: "inline" },
					{ label: "Popup", value: "popup", isPro: true },
				]}
				attributes={"inline"}
			/>
			<FormFieldBuilder attributes={attributes} setAttributes={setAttributes} />
			<ToggleControl label={__("Google reCAPTCHA", "testimonial-free")} isPro={true} />
			<ToggleControl label={__("Accessibility", "testimonial-free")} isPro={true} />
			<ToggleControl
				label={__("Terms and Conditions", "testimonial-free")}
				attributes={showTermsAndCondition}
				attributesKey={"showTermsAndCondition"}
				setAttributes={setAttributes}
			/>
			{showTermsAndCondition && (
				<>
					<InputControl
						label={__("Terms Text", "testimonial-free")}
						attributes={termsAndConditionLabel}
						attributesKey={"termsAndConditionLabel"}
						setAttributes={setAttributes}
					/>
					<InputControl
						label={__("Terms Link Text", "testimonial-free")}
						attributes={termsAndConditionAnchorLabel}
						attributesKey={"termsAndConditionAnchorLabel"}
						setAttributes={setAttributes}
					/>
					<InputControl
						label={__("Terms And Conditions URL", "testimonial-free")}
						attributes={termsAndConditionLink}
						attributesKey={"termsAndConditionLink"}
						setAttributes={setAttributes}
					/>
				</>
			)}
			<ButtonGroup
				label={__("Alignment", "testimonial-free")}
				attributes={textAlignment}
				attributesKey={"textAlignment"}
				setAttributes={setAttributes}
				items={textAlignmentOptions}
			/>
			<SPRangeControl
				label={__("Fields Gap", "testimonial-free")}
				attributes={fieldsGap}
				attributesKey={"fieldsGap"}
				setAttributes={setAttributes}
				max={200}
				units={["px", "em", "%"]}
				defaultValue={{ unit: "px", value: 24 }}
			/>
			<SPRangeControl
				label={__("Max Width", "testimonial-free")}
				attributes={maxWidth}
				attributesKey={"maxWidth"}
				setAttributes={setAttributes}
				max={1200}
				units={["px", "em", "%"]}
				defaultValue={{ unit: "px", value: 520 }}
			/>
			<ProFeaturesBox
				title={__("Pro Features", "testimonial-free")}
				description={__("Collect and manage testimonials from your customers effortlessly", "testimonial-free")}
				features={[
					__("Show form in popup", "testimonial-free"),
					__("Spam protection with reCAPTCHA", "testimonial-free"),
					__("Live frontend testimonial filtering", "testimonial-free"),
					__("Accessibility for better usability", "testimonial-free"),
				]}
			/>
		</>
	);
};

export const FormBuilderStyleTab = ({ attributes, setAttributes }) => {
	const { formBackground, formBorder, formBorderWidth, formBorderRadius, formBoxShadow, formPadding, formMargin } =
		attributes;
	return (
		<>
			<BackgroundControl
				label={__("Background Type", "testimonial-free")}
				attributes={formBackground}
				attributesKey={"formBackground"}
				setAttributes={setAttributes}
			/>
			<FlatBorder
				attributes={{
					border: formBorder,
					borderWidth: formBorderWidth,
				}}
				attributesKey={{
					border: "formBorder",
					borderWidth: "formBorderWidth",
				}}
				setAttributes={setAttributes}
				activeState={"normal"}
			/>
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={formBorderRadius}
				attributesKey={"formBorderRadius"}
				setAttributes={setAttributes}
				units={["Px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: {
						top: "12",
						right: "12",
						bottom: "12",
						left: "12",
					},
				}}
				indicator="radius"
			/>
			<BoxShadow
				shadowColorBtn={false}
				attributes={formBoxShadow}
				attributesKey={"formBoxShadow"}
				setAttributes={setAttributes}
				defaultValue={{
					color: "#0000001A",
					value: {
						top: "0",
						right: "12",
						bottom: "24",
						left: "0",
					},
					unit: "Outset",
				}}
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={formPadding}
				attributesKey={"formPadding"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: {
						top: "32",
						right: "32",
						bottom: "32",
						left: "32",
					},
				}}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={formMargin}
				attributesKey={"formMargin"}
				setAttributes={setAttributes}
				units={["Px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: {
						top: "0",
						right: "0",
						bottom: "0",
						left: "0",
					},
				}}
			/>
		</>
	);
};

export const InputFieldsGeneralTab = ({ attributes, setAttributes }) => {
	const { formFields, inputStyle, labelToFieldGap, fieldGapToNoteGap } = attributes;
	const halfWidth = ["tpro_client_name", "tpro_client_email", "tpro_client_designation"];

	const onChangeInputStyle = (newStyle) => {
		const updatedFormFields = formFields?.map((field) => {
			let width = 100;
			if (newStyle === "style-one" && inArray(halfWidth, field?.fieldName)) {
				width = 50;
			}
			return { ...field, fieldWidth: { value: width, unit: "%" } };
		});
		setAttributes({ inputStyle: newStyle, formFields: updatedFormFields });
	};

	return (
		<>
			<PresetPicker
				label={__("Input Style", "testimonial-free")}
				cols={2}
				items={INPUT_STYLE_ITEMS}
				attributes={inputStyle}
				onClick={onChangeInputStyle}
			/>
			<SPRangeControl
				label={__("Label to Input Gap", "testimonial-free")}
				attributes={labelToFieldGap}
				attributesKey={"labelToFieldGap"}
				setAttributes={setAttributes}
				max={200}
				units={["px", "em", "%"]}
				defaultValue={{ unit: "px", value: 12 }}
			/>
			<SPRangeControl
				label={__("Input to Note Gap", "testimonial-free")}
				attributes={fieldGapToNoteGap}
				attributesKey={"fieldGapToNoteGap"}
				setAttributes={setAttributes}
				max={200}
				units={["px", "em", "%"]}
				defaultValue={{ unit: "px", value: 12 }}
			/>
		</>
	);
};

export const InputFieldsStyleTab = ({ attributes, setAttributes }) => {
	const [colorState, setColorState] = useState("normal");
	const {
		labelTypography,
		labelFontSize,
		labelLineHeight,
		labelLetterSpacing,
		labelColors,
		noteTypography,
		noteFontSize,
		noteLineHeight,
		noteLetterSpacing,
		noteColors,
		placeholderTypography,
		placeholderFontSize,
		placeholderLineHeight,
		placeholderLetterSpacing,
		requiredColor,
		fieldBackgroundColors,
		placeholderColors,
		fieldBorder,
		fieldBorderWidth,
		fieldBorderRadius,
		fieldPadding,
	} = attributes;

	return (
		<>
			{componentSectionHeader(__("Label", "testimonial-free"))}
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: labelTypography,
					typographyKey: "labelTypography",
					fontSize: labelFontSize,
					fontSizeKey: "labelFontSize",
					letterSpacing: labelLetterSpacing,
					letterSpacingKey: "labelLetterSpacing",
					lineHeight: labelLineHeight,
					lineHeightKey: "labelLineHeight",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{
					unit: "px",
					value: 16,
				}}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={labelColors}
				attributesKey={"labelColors"}
				setAttributes={setAttributes}
				activeState={"normal"}
			/>
			<ColorPicker
				label={__("Required Color", "testimonial-free")}
				value={requiredColor}
				attributesKey={"requiredColor"}
				setAttributes={setAttributes}
			/>
			<Divider />
			{componentSectionHeader(__("Note", "testimonial-free"))}
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: noteTypography,
					typographyKey: "noteTypography",
					fontSize: noteFontSize,
					fontSizeKey: "noteFontSize",
					letterSpacing: noteLetterSpacing,
					letterSpacingKey: "noteLetterSpacing",
					lineHeight: noteLineHeight,
					lineHeightKey: "noteLineHeight",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{
					unit: "px",
					value: 15,
				}}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={noteColors}
				attributesKey={"noteColors"}
				setAttributes={setAttributes}
				activeState={"normal"}
			/>
			<Divider />
			{componentSectionHeader(__("Placeholder", "testimonial-free"))}
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: placeholderTypography,
					typographyKey: "placeholderTypography",
					fontSize: placeholderFontSize,
					fontSizeKey: "placeholderFontSize",
					letterSpacing: placeholderLetterSpacing,
					letterSpacingKey: "placeholderLetterSpacing",
					lineHeight: placeholderLineHeight,
					lineHeightKey: "placeholderLineHeight",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{
					unit: "px",
					value: 14,
				}}
			/>
			<ButtonGroup
				attributes={colorState}
				items={[
					{ label: "Normal", value: "normal" },
					{ label: "Focus", value: "hover" },
				]}
				onClick={(e) => setColorState(e)}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={placeholderColors}
				attributesKey={"placeholderColors"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<ColorPicker
				label={__("Background Color", "testimonial-free")}
				value={fieldBackgroundColors}
				attributesKey={"fieldBackgroundColors"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<FlatBorder
				label={__("Border", "testimonial-free")}
				attributes={{
					border: fieldBorder,
					borderWidth: fieldBorderWidth,
				}}
				attributesKey={{
					border: "fieldBorder",
					borderWidth: "fieldBorderWidth",
				}}
				defaultValue={{
					unit: "px",
					value: {
						top: "1",
						right: "1",
						bottom: "1",
						left: "1",
					},
				}}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<Divider />
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={fieldBorderRadius}
				attributesKey={"fieldBorderRadius"}
				setAttributes={setAttributes}
				defaultValue={{
					unit: "px",
					value: {
						top: "4",
						right: "4",
						bottom: "4",
						left: "4",
					},
				}}
				indicator="radius"
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={fieldPadding}
				attributesKey={"fieldPadding"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: {
						top: "15",
						right: "18",
						bottom: "15",
						left: "18",
					},
				}}
			/>
		</>
	);
};

export const SubmitButtonGeneralTab = ({ attributes, setAttributes }) => {
	const {
		submitButtonLabel,
		submitButtonWidth,
		submitButtonAlignment,
		showSubmitButtonIcon,
		submitButtonIcon,
		submitButtonIconPosition,
		submitButtonIconSize,
	} = attributes;
	return (
		<>
			<ToggleControl
				label={__("Show Icon", "testimonial-free")}
				attributes={showSubmitButtonIcon}
				attributesKey={"showSubmitButtonIcon"}
				setAttributes={setAttributes}
			/>
			{showSubmitButtonIcon && (
				<>
					<IconsLibrary
						attributes={submitButtonIcon}
						attributesKey="submitButtonIcon"
						setAttributes={setAttributes}
					/>
					<SelectControl
						label={__("Icon Position", "testimonial-free")}
						items={[
							{ label: "Left", value: "left" },
							{ label: "Right", value: "right" },
						]}
						attributes={submitButtonIconPosition}
						attributesKey={"submitButtonIconPosition"}
						setAttributes={setAttributes}
						flexStyle={true}
					/>
					<SPRangeControl
						label={__("Icon Size", "testimonial-free")}
						attributes={submitButtonIconSize}
						attributesKey={"submitButtonIconSize"}
						setAttributes={setAttributes}
						min={0}
						max={200}
						defaultValue={{ unit: "px", value: 16 }}
					/>
				</>
			)}
			<InputControl
				label={__("Button Label", "testimonial-free")}
				attributes={submitButtonLabel}
				attributesKey={"submitButtonLabel"}
				setAttributes={setAttributes}
			/>
			<ButtonGroup
				label={__("Button Width", "testimonial-free")}
				items={[
					{ label: "Full Width", value: "fullWidth" },
					{ label: "Auto", value: "auto" },
				]}
				attributes={submitButtonWidth}
				attributesKey={"submitButtonWidth"}
				setAttributes={setAttributes}
			/>
			{submitButtonWidth === "auto" && (
				<ButtonGroup
					label={__("Button Alignment", "testimonial-free")}
					attributes={submitButtonAlignment}
					attributesKey={"submitButtonAlignment"}
					setAttributes={setAttributes}
					items={[
						{ label: <AlignLeft />, value: "left" },
						{ label: <AlignCenter />, value: "center" },
						{ label: <AlignRight />, value: "right" },
					]}
				/>
			)}
		</>
	);
};

export const MessageSettingsGeneralTab = ({ attributes, setAttributes }) => {
	const {
		testimonialStatus,
		showRequiredNotice,
		requiredNoticeLabel,
		ajaxFormSubmission,
		successMessage,
		errorMessage,
		submissionMessagePosition,
	} = attributes;

	return (
		<>
			<div className="sp-real-tsf-status-wrap">
				<SelectControl
					label={__("Testimonial Status", "testimonial-free")}
					items={[
						{ label: "Pending", value: "pending" },
						{ label: "Private", value: "private" },
						{ label: "Draft", value: "draft" },
						{ label: "Auto Publish (Pro)", value: "auto_publish", disabled: true },
						{
							label: "Auto Publish Based on Star Rating (Pro)",
							value: "auto_publish_rating",
							disabled: true,
						},
					]}
					attributes={testimonialStatus}
					attributesKey={"testimonialStatus"}
					setAttributes={setAttributes}
				/>
				<p className="sp-real-tsf-field-note">
					{__("Choose how submitted testimonials are handled.", "testimonial-free")}
				</p>
			</div>
			<Divider />
			<ToggleControl
				label={__("Required Notice", "testimonial-free")}
				attributes={showRequiredNotice}
				attributesKey={"showRequiredNotice"}
				setAttributes={setAttributes}
			/>
			{showRequiredNotice && (
				<InputControl
					label={__("Notice Label", "testimonial-free")}
					attributes={requiredNoticeLabel}
					attributesKey={"requiredNoticeLabel"}
					setAttributes={setAttributes}
				/>
			)}
			<Divider />
			<ToggleControl
				label={__("Ajax Form Submission", "testimonial-free")}
				attributes={ajaxFormSubmission}
				attributesKey={"ajaxFormSubmission"}
				setAttributes={setAttributes}
			/>
			<SelectControl
				label={__("Redirect After Submit", "testimonial-free")}
				items={[
					{ label: "Same Page", value: "same-page" },
					{ label: "To a Page (Pro)", value: "to-selected-page", disabled: true },
					{ label: "To a Custom URL (Pro)", value: "to-custom-url", disabled: true },
				]}
				attributes={"same-page"}
			/>
			<TextareaControl
				label={__("Successful Message", "testimonial-free")}
				attributes={successMessage}
				attributesKey={"successMessage"}
				setAttributes={setAttributes}
			/>
			<TextareaControl
				label={__("Error Message", "testimonial-free")}
				attributes={errorMessage}
				attributesKey={"errorMessage"}
				setAttributes={setAttributes}
			/>
			<ButtonGroup
				label={__("Message Position", "testimonial-free")}
				items={[
					{ label: "Top", value: "top" },
					{ label: "Bottom", value: "bottom" },
				]}
				attributes={submissionMessagePosition}
				attributesKey={"submissionMessagePosition"}
				setAttributes={setAttributes}
			/>
			<ToggleControl label={__("Hide Form After Submit", "testimonial-free")} isPro={true} />
			<ProFeaturesBox
				title={__("Pro Features", "testimonial-free")}
				description={__("Automate submissions and control post submission actions", "testimonial-free")}
				features={[
					__("Auto publish testimonials instantly", "testimonial-free"),
					__("Auto publish based on star rating", "testimonial-free"),
					__("Custom redirect control", "testimonial-free"),
					__("Hide form after successful submission", "testimonial-free"),
				]}
			/>
		</>
	);
};

export const EmailNotificationGeneralTab = () => (
	<ProFeaturesBox
		title={__("Pro Features", "testimonial-free")}
		description={__("Automate email notifications to stay updated and respond faster", "testimonial-free")}
		features={[
			__("Notify admins on new submissions", "testimonial-free"),
			__("Send alerts for pending and approval", "testimonial-free"),
			__("Customize email with dynamic tags", "testimonial-free"),
			__("Streamlined communication workflow", "testimonial-free"),
		]}
	/>
);

export const SubmitButtonStyleTab = ({ attributes, setAttributes }) => {
	const [colorState, setColorState] = useState("normal");
	const {
		submitButtonTypography,
		submitButtonFontSize,
		submitButtonLineHeight,
		submitButtonLetterSpacing,
		submitButtonColors,
		submitButtonBackground,
		submitButtonBorder,
		submitButtonBorderWidth,
		submitButtonBorderRadius,
		submitButtonPadding,
		submitButtonMargin,
	} = attributes;
	return (
		<>
			<TypographyNew
				label={__("Typography", "testimonial-free")}
				attributes={{
					typography: submitButtonTypography,
					typographyKey: "submitButtonTypography",
					fontSize: submitButtonFontSize,
					fontSizeKey: "submitButtonFontSize",
					letterSpacing: submitButtonLetterSpacing,
					letterSpacingKey: "submitButtonLetterSpacing",
					lineHeight: submitButtonLineHeight,
					lineHeightKey: "submitButtonLineHeight",
				}}
				setAttributes={setAttributes}
				fontSizeDefault={{
					unit: "px",
					value: 16,
				}}
			/>
			<ButtonGroup
				attributes={colorState}
				items={[
					{ label: "Normal", value: "normal" },
					{ label: "Hover", value: "hover" },
				]}
				onClick={(e) => setColorState(e)}
			/>
			<ColorPicker
				label={__("Color", "testimonial-free")}
				value={submitButtonColors}
				attributesKey={"submitButtonColors"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<BackgroundControl
				label={__("Background Type", "testimonial-free")}
				attributes={submitButtonBackground}
				attributesKey={"submitButtonBackground"}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<FlatBorder
				attributes={{
					border: submitButtonBorder,
					borderWidth: submitButtonBorderWidth,
				}}
				attributesKey={{
					border: "submitButtonBorder",
					borderWidth: "submitButtonBorderWidth",
				}}
				defaultValue={{
					unit: "px",
					value: {
						top: "0",
						right: "0",
						bottom: "0",
						left: "0",
					},
				}}
				setAttributes={setAttributes}
				activeState={colorState}
			/>
			<Divider />
			<Spacing
				label={__("Border Radius", "testimonial-free")}
				attributes={submitButtonBorderRadius}
				attributesKey={"submitButtonBorderRadius"}
				setAttributes={setAttributes}
				defaultValue={{
					unit: "px",
					value: {
						top: "4",
						right: "4",
						bottom: "4",
						left: "4",
					},
				}}
				indicator="radius"
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={submitButtonPadding}
				attributesKey={"submitButtonPadding"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: {
						top: "15",
						right: "18",
						bottom: "15",
						left: "18",
					},
				}}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={submitButtonMargin}
				attributesKey={"submitButtonMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: {
						top: "32",
						right: "0",
						bottom: "0",
						left: "0",
					},
				}}
			/>
		</>
	);
};

export const MessageSettingsStyleTab = ({ attributes, setAttributes }) => {
	const { successMessageColor, errorMessageColor } = attributes;
	return (
		<>
			<ColorPicker
				label={__("Success Message Color", "testimonial-free")}
				value={successMessageColor}
				attributesKey={"successMessageColor"}
				setAttributes={setAttributes}
			/>
			<ColorPicker
				label={__("Error Message Color", "testimonial-free")}
				value={errorMessageColor}
				attributesKey={"errorMessageColor"}
				setAttributes={setAttributes}
			/>
		</>
	);
};
