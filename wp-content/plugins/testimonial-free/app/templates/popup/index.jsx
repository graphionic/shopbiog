import { useState } from "@wordpress/element";

const Popup = ({ id = "", triggerLabel, children, isOpen, setIsOpen, buttonAlignment = "center" }) => {
	const [internalOpen, setInternalOpen] = useState(false);

	const openState = isOpen !== undefined ? isOpen : internalOpen;
	const setOpenState = setIsOpen ? setIsOpen : setInternalOpen;

	return (
		<div id={id} className="sp-real-popup">
			{triggerLabel && (
				<div
					className={`sp-real-popup-trigger-button-wrapper sp-d-flex sp-justify-${buttonAlignment} sp-align-center`}
				>
					<button
						className="sp-real-popup-trigger-button sp-cursor-pointer"
						onClick={() => setOpenState(true)}
					>
						{triggerLabel}
					</button>
				</div>
			)}

			<div
				className={`sp-real-popup-overlay${openState ? " sp-realpopup-active" : ""}`}
				onClick={() => setOpenState(false)}
			></div>

			{openState && (
				<div className={`sp-real-popup-body${openState ? " sp-realpopup-active" : ""}`}>
					<button
						className="sp-real-popup-close-button sp-cursor-pointer"
						onClick={() => setOpenState(false)}
					>
						×
					</button>
					{children}
				</div>
			)}
		</div>
	);
};

export default Popup;
