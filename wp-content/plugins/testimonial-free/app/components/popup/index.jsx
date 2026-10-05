import { useEffect, useRef, useState } from "@wordpress/element";
import { Button } from "@wordpress/components";
import { jsonParse } from "@testimonial/controls";
import { BorderIcon } from "../../icons";
import "./editor.scss";

const Popup = ({ label, children, toggleButton }) => {
	const [isContentsVisible, setIsContentsVisible] = useState(false);
	const popupRef = useRef(null);
	const buttonRef = useRef(null);

	const handleButtonClick = () => {
		setIsContentsVisible((prev) => !prev);
	};
	// Close the dropdown when clicking outside of it.
	useEffect(() => {
		const handleClickOutside = (event) => {
			const typographyPopup = document.querySelector(".sp-testimonial-typography-fonts");
			if (
				popupRef.current &&
				!typographyPopup &&
				!popupRef.current.contains(event.target) &&
				!buttonRef.current.contains(event.target)
			) {
				setIsContentsVisible(false);
			}
		};

		document.addEventListener("mousedown", handleClickOutside);
		return () => {
			document.removeEventListener("mousedown", handleClickOutside);
		};
	}, [popupRef, buttonRef]);

	const value = toggleButton?.props?.attributes;
	const isActivePopUpButton = value ? jsonParse(value) : true;

	return (
		<>
			<div className="sp-testimonial-button sp-real-component-mb">
				<div className={`sp-testimonial-header-left ${toggleButton && "wide-area"}`}>
					{label && <span className="sp-real-component-title">{label}</span>}
					{toggleButton && toggleButton}
				</div>
				<div className="sp-testimonial-header-right">
					<Button
						disabled={!isActivePopUpButton}
						className={`sp-testimonial-border-icon-button ${
							isActivePopUpButton ? "active" : ""
						} ${isContentsVisible ? "button-clicked" : ""}`}
						icon={<BorderIcon color={isContentsVisible ? "#ffffff" : "#2F2F2F"} />}
						ref={buttonRef}
						onClick={handleButtonClick}
					/>
				</div>
			</div>
			{isContentsVisible && isActivePopUpButton && (
				<div ref={popupRef} className="sp-testimonial-popup-content">
					{children} {/* Render children content here */}
				</div>
			)}
		</>
	);
};

export default Popup;
