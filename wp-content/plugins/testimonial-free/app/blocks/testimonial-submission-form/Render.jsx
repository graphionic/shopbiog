import { memo } from "@wordpress/element";
import { DisplayIcon } from "@testimonial/templates";
import { useDeviceType } from "@testimonial/controls";

const FIELD_TYPE_MAP = {
	tpro_client_name: "text",
	tpro_client_email: "email",
	tpro_client_designation: "text",
	tpro_client_rating: "rating",
	tpro_testimonial_title: "text",
	tpro_client_testimonial: "textarea",
	tpro_client_image: "file",
};

const getFieldType = (fieldName) => FIELD_TYPE_MAP[fieldName] || "text";

const RATING_MAP = [
	{ stars: 5, value: "five_star" },
	{ stars: 4, value: "four_star" },
	{ stars: 3, value: "three_star" },
	{ stars: 2, value: "two_star" },
	{ stars: 1, value: "one_star" },
];

// fieldWidth is { value, unit }. At 100% the field owns the row. Below 100% it
// shares a flex row, so subtract half the column gap and half the field's
// left+right border width to keep two columns from overflowing.
// Legacy string values (e.g. "calc(50% - 9px)") are passed through as-is.
const fieldWidthCss = (fieldWidth, fieldsGap, fieldBorderWidth, device = "Desktop") => {
	if (!fieldWidth) {
		return undefined;
	}
	if (typeof fieldWidth === "string") {
		return fieldWidth;
	}
	const { value, unit = "%" } = fieldWidth;
	if (value === undefined || value === "") {
		return undefined;
	}
	if (Number(value) >= 100 && unit === "%") {
		return "100%";
	}

	const parts = [`${value}${unit}`];

	const gapValue = Number(fieldsGap?.device?.[device]) || 0;
	if (gapValue) {
		const gapUnit = fieldsGap?.unit?.[device] || "px";
		parts.push(`${gapValue / 2}${gapUnit}`);
	}

	const borderBox = fieldBorderWidth?.value || {};
	const borderSum = (Number(borderBox.left) || 0) + (Number(borderBox.right) || 0);
	if (borderSum) {
		parts.push(`${borderSum / 2}${fieldBorderWidth?.unit || "px"}`);
	}

	return parts.length > 1 ? `calc(${parts.join(" - ")})` : parts[0];
};

const FormFields = ({ attributes }) => {
	const { formFields, textAlignment, fieldsGap, fieldBorderWidth, uniqueId } = attributes;

	const device = useDeviceType();

	return formFields?.map((field) => {
		const { id, label, placeholder, fieldName, required, length, limit, fieldWidth, helpText, showField } = field;
		if (!showField) {
			return null;
		}
		const type = getFieldType(fieldName);
		// Scope the field id by uniqueId so multiple forms don't share DOM ids
		// (duplicate label `for` targets break rating selection past the first).
		const fieldId = `${uniqueId ? `${uniqueId}-` : ""}tsf-${id}`;
		const isRating = type === "rating";
		const isFile = type === "file";
		const widthCss = fieldWidthCss(fieldWidth, fieldsGap, fieldBorderWidth, device);

		return (
			<div
				key={id}
				className={`sp-real-tsf-field sp-d-flex sp-flex-col sp-real-tsf-field--${type}`}
				data-field={fieldName}
				style={widthCss ? { width: widthCss } : undefined}
			>
				<div
					className={`sp-real-tsf-field-label-section sp-d-flex sp-align-center sp-justify-${"left" === textAlignment ? "between" : textAlignment} sp-gap-10px`}
				>
					<label className="sp-real-tsf-field__label sp-d-i-flex sp-gap-4px" htmlFor={fieldId}>
						{label}
						{required && (
							<span className="sp-real-tsf-required" aria-hidden="true">
								*
							</span>
						)}
					</label>
					{length === "limited" && (
						<span className="sp-real-tsf-field__length">
							0 {limit?.unit} out of {limit?.value}
						</span>
					)}
				</div>

				{isRating && helpText && (
					<span className="sp-real-tsf-field__note sp-tsf-rating-note sp-d-block">{helpText}</span>
				)}

				{type === "textarea" && (
					<textarea
						id={fieldId}
						name={fieldName}
						className="sp-real-tsf-field__input"
						placeholder={placeholder}
						required={required}
						rows={5}
					/>
				)}

				{isRating && (
					<div
						className="sp-real-tsf-rating sp-d-flex sp-justify-start sp-row-reverse sp-gap-4px"
						role="radiogroup"
						aria-label={label}
					>
						{RATING_MAP.map(({ stars, value }) => {
							const rid = `${fieldId}-${stars}`;
							return (
								<span key={stars} className="sp-real-tsf-rating__item">
									<input
										type="radio"
										id={rid}
										name={fieldName}
										value={value}
										defaultChecked={stars === 0}
										required={required}
									/>
									<label className="sp-cursor-pointer" htmlFor={rid} title={`${stars} stars`}>
										<svg
											width="20"
											height="19"
											viewBox="0 0 20 19"
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
										>
											<path
												d="M10.7961 0.470057L13.4481 5.67684L19.2607 6.61696C19.5877 6.65311 19.842 6.87006 19.951 7.19549C20.0599 7.52091 19.9873 7.84634 19.733 8.06329L15.5915 12.2215L16.4997 18.0068C16.536 18.3322 16.4271 18.6576 16.1364 18.8384C15.8821 19.0192 15.5188 19.0554 15.2282 18.9108L9.99686 16.2712L4.7655 18.9108C4.47487 19.0554 4.11159 19.0192 3.85728 18.8384C3.56665 18.6576 3.45767 18.3322 3.494 18.0068L4.40222 12.2215L0.260729 8.06329C0.0427559 7.84634 -0.0662306 7.52091 0.0427559 7.19549C0.151742 6.87006 0.406044 6.65311 0.733004 6.61696L6.54562 5.67684L9.23395 0.470057C9.37927 0.180791 9.6699 0 9.99686 0C10.3238 0 10.6144 0.180791 10.7961 0.470057Z"
												fill="currentColor"
											/>
										</svg>
									</label>
								</span>
							);
						})}
					</div>
				)}

				{isFile && (
					<input
						id={fieldId}
						type="file"
						name={fieldName}
						className="sp-real-tsf-field__input sp-real-tsf-field__file"
						accept="image/*"
						required={required}
					/>
				)}

				{!["textarea", "rating", "file"].includes(type) && (
					<input
						id={fieldId}
						type={type}
						name={fieldName}
						className="sp-real-tsf-field__input"
						placeholder={placeholder}
						required={required}
					/>
				)}

				{!isRating && helpText && <span className="sp-real-tsf-field__note sp-d-block">{helpText}</span>}
			</div>
		);
	});
};

const TermsAndCondition = ({ label, anchorLabel, link }) => (
	<div className="sp-real-tsf-field sp-real-tsf-field--checkbox sp-d-flex sp-flex-col" data-field="tpro_terms">
		<span className="sp-real-tsf-field__checkbox sp-d-i-flex sp-align-center sp-gap-8px">
			<input type="checkbox" name="tpro_terms" value="1" required />
			<span className="sp-real-tsf-field__terms">
				{label}{" "}
				<a href={link || "#"} target="_blank" rel="noreferrer">
					{anchorLabel}
				</a>
			</span>
		</span>
	</div>
);

const MessageHolder = ({ successMessage, errorMessage }) => (
	<div className="sp-real-tsf-messages" aria-live="polite">
		<div className="sp-real-tsf-message sp-real-tsf-message__success" hidden>
			{successMessage}
		</div>
		<div className="sp-real-tsf-message sp-real-tsf-message__error" hidden>
			{errorMessage}
		</div>
	</div>
);

const Render = ({ attributes }) => {
	const {
		uniqueId,
		template,
		submitButtonLabel,
		submitButtonWidth,
		submitButtonAlignment,
		showSubmitButtonIcon,
		submitButtonIcon,
		submitButtonIconPosition,
		submitButtonIconSize,
		submissionMessagePosition,
		successMessage,
		errorMessage,
		showRequiredNotice,
		requiredNoticeLabel,
		ajaxFormSubmission,
		inputStyle,
		formFields,
		textAlignment,
		fieldsGap,
		fieldBorderWidth,
		showTermsAndCondition,
		termsAndConditionLabel,
		termsAndConditionAnchorLabel,
		termsAndConditionLink,
	} = attributes;

	const renderForm = () => (
		<form
			className={`sp-real-tsf-form sp-d-flex sp-flex-col${ajaxFormSubmission ? " sp-real-tsf-ajax" : ""}`}
			encType="multipart/form-data"
			onSubmit={(e) => e.preventDefault()}
		>
			{"top" === submissionMessagePosition && (
				<MessageHolder successMessage={successMessage} errorMessage={errorMessage} />
			)}
			{showRequiredNotice && requiredNoticeLabel && (
				<p className="sp-real-tsf-required-notice">{requiredNoticeLabel}</p>
			)}
			<div className={`sp-real-tsf-fields sp-d-flex sp-flex-wrap sp-real-tsf-input-${inputStyle}`}>
				<FormFields attributes={{ formFields, textAlignment, fieldsGap, fieldBorderWidth, uniqueId }} />
				{showTermsAndCondition && (
					<TermsAndCondition
						label={termsAndConditionLabel}
						anchorLabel={termsAndConditionAnchorLabel}
						link={termsAndConditionLink}
					/>
				)}
			</div>
			<div
				className={`sp-real-tsf-submit-wrap ${submitButtonWidth === "auto" ? `sp-d-flex sp-justify-${submitButtonAlignment}` : "sp-tsf-full-btn"}`}
			>
				<button
					type="submit"
					className={`sp-real-tsf-submit sp-d-flex sp-align-center sp-justify-center sp-gap-8px${showSubmitButtonIcon && submitButtonIconPosition === "left" ? " sp-row-reverse" : ""}`}
				>
					<span className="sp-real-tsf-submit__label">{submitButtonLabel}</span>
					{showSubmitButtonIcon && submitButtonIcon && (
						<span
							className="sp-real-tsf-submit__icon sp-d-i-flex"
							style={{
								width: `${submitButtonIconSize?.value}${submitButtonIconSize?.unit}`,
								height: `${submitButtonIconSize?.value}${submitButtonIconSize?.unit}`,
							}}
						>
							<DisplayIcon icon={submitButtonIcon} />
						</span>
					)}
				</button>
			</div>

			{"bottom" === submissionMessagePosition && (
				<MessageHolder successMessage={successMessage} errorMessage={errorMessage} />
			)}
		</form>
	);

	return (
		<div id={uniqueId} className="sp-real-testimonial-submission-form sp-real-tsf--inline">
			<div className={`sp-real-tsf-${template} sp-real-template-wrapper`}>{renderForm()}</div>
		</div>
	);
};

export default memo(Render);
