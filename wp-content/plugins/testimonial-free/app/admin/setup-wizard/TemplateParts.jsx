import { __ } from "@wordpress/i18n";
import { Modal } from "@wordpress/components";
import { Arrow } from "../Icons";

export const UserDataInfoModal = ({ closeModal }) => {
	return (
		<Modal
			title={__("What We Collect?", "testimonial-free")}
			onRequestClose={closeModal}
			className="sp-real-setup-page-modal"
		>
			<hr />
			<p className="modal-description">
				{__(
					"We collect only non-sensitive diagnostic data and basic plugin usage information. This may include:",
					"testimonial-free"
				)}
			</p>
			<ul>
				<li className="modal-description">{__("WordPress & PHP version", "testimonial-free")}</li>
				<li className="modal-description">{__("Active theme and plugins", "testimonial-free")}</li>
				<li className="modal-description">{__("General system details", "testimonial-free")}</li>
				<li className="modal-description">
					{__("Email address only for sending helpful updates or optional offers.", "testimonial-free")}
				</li>
			</ul>
			<p className="modal-description">
				{__(
					"This information helps us improve performance, fix issues faster, and ensure Real Testimonials stays compatible with the popular plugins and themes.",
					"testimonial-free"
				)}
			</p>
			<p className="modal-description sp-d-flex sp-align-center sp-gap-4px">
				<b>{__("No personal data is collected, and we never send spam—promise.", "testimonial-free")}</b>
			</p>
			<p className="modal-description">
				{__("Your Privacy is First ", "testimonial-free")}
				<a
					href="https://realtestimonials.io/information-we-collect/"
					target="_blank"
					className="modal-description sp-d-flex sp-align-center sp-gap-4px"
					rel="noreferrer"
				>
					{__("Learn More", "testimonial-free")}
					<Arrow />
				</a>
			</p>
		</Modal>
	);
};
