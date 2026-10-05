import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { TestimonialShortcodeBlockIcon } from "./icon";
import "./editor.scss";

const ShortcodeEdit = ({ clientId, attributes, setAttributes }) => {
	const shortCodeList = sp_testimonial_pro?.shortCodeList;
	const createLink = sp_testimonial_pro?.link;

	return (
		<div {...useBlockProps({ className: "sp-real-shortcode-renderer-block" })}>
			<div className="components-placeholder is-large">
				<div className="components-placeholder__label sp-d-flex sp-align-center sp-gap-8px">
					<TestimonialShortcodeBlockIcon />
					<span>{__("Real Testimonials Pro", "testimonial-free")}</span>
				</div>
				{shortCodeList?.length > 0 ? (
					<div className="sp-real-gutenberg-shortcode editor-styles-wrapper">
						<select
							className="sp-real-shortcode-selector"
							onChange={(e) => setAttributes({ shortcode: e.target.value })}
							value={attributes?.shortcode}
							name={`sp-real-select-${clientId}`}
						>
							<option value="">{__("-- Select a view (shortcode) --", "testimonial-free")}</option>
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
						{__("No view shortcode found.", "testimonial-free")}{" "}
						<a href={createLink}>{__("Create a view now!", "testimonial-free")}</a>
					</div>
				)}
			</div>
		</div>
	);
};

export default ShortcodeEdit;
