import { __ } from "@wordpress/i18n";
import { Modal } from "@wordpress/components";
import { Arrow } from "../../Icons";

export const SaveAndReset = ({ onSave, onReset, isChanged }) => {
	return (
		<div className="sp-real-settings-save-wrapper sp-d-flex sp-align-center sp-gap-12px">
			<button
				className={`sp-real-settings-save-btn sp-d-flex sp-align-center sp-justify-center${isChanged ? " sp-cursor-pointer active" : ""}`}
				disabled={!isChanged}
				onClick={onSave}
			>
				{__("Save Change", "testimonial-free")}
			</button>
			<button
				className="sp-real-settings-reset-btn sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer"
				onClick={() => {
					// eslint-disable-next-line no-alert
					const confirmReset = window.confirm(__("Are you sure you want to reset?", "testimonial-free"));
					if (confirmReset) {
						onReset();
					}
				}}
			>
				{__("Reset", "testimonial-free")}
			</button>
		</div>
	);
};

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
					"This information helps us improve performance, fix issues faster, and ensure Location Weather stays compatible with the popular plugins and themes.",
					"testimonial-free"
				)}
			</p>
			<p className="modal-description">
				{__("We", "testimonial-free")}
				<b>{__("do not collect sensitive personal data,", "testimonial-free")}</b>
				{__("and", "testimonial-free")}
				<b>{__("never send spam", "testimonial-free")}</b>
				{__("promise.", "testimonial-free")}
			</p>
			<p className="modal-description">
				<b>{__("Your privacy comes first.", "testimonial-free")}</b>
			</p>
			<a
				href="https://realtestimonials.io/information-we-collect/"
				target="_blank"
				rel="noreferrer"
				className="modal-description"
			>
				{__("Learn More", "testimonial-free")}
				<Arrow />
			</a>
		</Modal>
	);
};
