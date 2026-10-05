import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { TestimonialFormShortcodeBlockIcon } from "./icon";

const FormEdit = ({ clientId, attributes, setAttributes }) => {
	const shortCodeList = sp_testimonial_form?.shortCodeList;
	const createLink = sp_testimonial_form?.link;

	return (
		<div {...useBlockProps({ className: "sp-real-shortcode-renderer-block" })}>
			<div className="components-placeholder is-large">
				<div className="components-placeholder__label sp-d-flex sp-align-center sp-gap-8px">
					<TestimonialFormShortcodeBlockIcon />
					<span>{__("Testimonials Form", "testimonial-free")}</span>
				</div>
				{shortCodeList?.length > 0 ? (
					<div className="sp-real-gutenberg-shortcode editor-styles-wrapper">
						<select
							className="sp-real-shortcode-selector"
							onChange={(e) => setAttributes({ shortcode: e.target.value })}
							value={attributes?.shortcode}
							name={`sp-real-form-select-${clientId}`}
						>
							<option value="">{__("-- Select a form (shortcode) --", "testimonial-free")}</option>
							{shortCodeList?.map((shortcode) => {
								const title =
									shortcode.title.length > 35
										? `${shortcode.title.substring(0, 30)}.... #(${shortcode.id})`
										: `${shortcode.title} #(${shortcode.id})`;
								return (
									<option value={shortcode.id.toString()} key={shortcode.id.toString()}>
										{title}
									</option>
								);
							})}
						</select>
					</div>
				) : (
					<div className="sp-real-shortcode-no-found-message">
						{__("No form shortcode found.", "testimonial-free")}{" "}
						<a href={createLink}>{__("Create a form now!", "testimonial-free")}</a>
					</div>
				)}
			</div>
		</div>
	);
};

export default FormEdit;
