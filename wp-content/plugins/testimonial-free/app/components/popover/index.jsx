import { Popover } from "@wordpress/components";
import { useEffect, useState } from "@wordpress/element";
import { getRandomId } from "@testimonial/controls";
import { PopOverToggleIcon } from "@testimonial/icons";
import "./editor.scss";

// compound component.
const SpPopover = ({ children, label = "popover", toggleIcon = false, className = "" }) => {
	const [open, setOpen] = useState(false);
	const uniqueId = `${getRandomId("sp-real")}popover`;

	useEffect(() => {
		const clickOutSite = (e) => {
			const componentView = e.target.closest(".sp-real-popover__content-visible");
			const colorPicker = e.target.closest(".sp-real-color-picker-renderer");
			const buttonTarget = e.target.closest(`.sp-real-popover-toggle-button.${uniqueId}`);
			const select = e.target.closest(".css-1nmdiq5-menu");
			if (open && !componentView && !buttonTarget && !select && !colorPicker) {
				setOpen(false);
			}
		};
		window.addEventListener("click", clickOutSite);

		return () => window.removeEventListener("click", clickOutSite);
	});

	return (
		<div className={`sp-real-popover-component sp-real-component-mb${className ? ` ${className}` : ""}`}>
			{label && (
				<div className="sp-real-popover-toggle-wrapper sp-d-flex sp-justify-between">
					<span className="sp-real-component-title">{label}</span>
					<button
						onClick={() => setOpen((prev) => !prev)}
						className={`sp-real-popover-toggle-button ${uniqueId} sp-cursor-pointer${open ? " active" : ""}`}
					>
						{toggleIcon ? toggleIcon : <PopOverToggleIcon />}
					</button>
				</div>
			)}
			{open && (
				<Popover shift={true}>
					<div className="sp-real-popover__content-visible sp-testimonial-tabs-panel">
						<div className="sp-real-popover__content-visible-content">{children}</div>
					</div>
				</Popover>
			)}
		</div>
	);
};

SpPopover.Header = ({ children }) => <div className="sp-real-popover__content-visible-label">{children}</div>;

SpPopover.Content = ({ children }) => <div className="sp-real-popover__content">{children}</div>;

export default SpPopover;
