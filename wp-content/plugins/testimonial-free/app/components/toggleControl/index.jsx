import { ToggleControl } from "@wordpress/components";
import { memo, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { InfoIcon, DndIndicatorIcon } from "../Icons";
import { API_ENDPOINTS } from "../../ready-patterns/constants";
import "./editor.scss";

const Toggle = ({
	label,
	attributes,
	setAttributes = true,
	attributesKey,
	onChange = false,
	updated = false,
	infoText = false,
	// Pro-lock: when true the toggle can't be switched on and shows a "(Pro)"
	// tag. If `redirectUrl` is set, interacting with the row opens that URL.
	isPro = false,
	redirectUrl = API_ENDPOINTS.UPGRADE_URL,
}) => {
	const [isVisible, setIsVisible] = useState(false);

	const handleProClick = () => {
		if (redirectUrl) {
			window.open(redirectUrl, "_blank", "noopener,noreferrer");
		}
	};

	return (
		<div
			className={`sp-real-toggle sp-real-component-mb sp-d-flex sp-align-center sp-justify-between${updated ? " updated-toggle " : ""}${isPro ? " sp-real-pro-toggle" : ""}`}
		>
			<div className="sp-real-toggle-left sp-d-flex sp-align-center sp-gap-4px">
				{updated && <DndIndicatorIcon />}
				<span className="sp-real-component-title">{label}</span>
				{isPro && (
					<span
						role="button"
						tabIndex={0}
						onClick={handleProClick}
						onKeyDown={(e) => {
							if (e.key === "Enter" || e.key === " ") {
								handleProClick();
							}
						}}
						className="sp-real-pro-tag"
					>
						({__("Pro", "testimonial-free")})
					</span>
				)}
				{infoText && (
					<span
						className="sp-real-label-info-text__popup-trigger sp-cursor-pointer"
						onMouseEnter={() => setIsVisible(true)}
						onMouseLeave={() => setIsVisible(false)}
					>
						<InfoIcon />
					</span>
				)}
			</div>
			{isVisible && infoText && (
				<div
					onMouseEnter={() => setIsVisible(true)}
					onMouseLeave={() => setIsVisible(false)}
					className="sp-real-label-info-text__popup"
				>
					<div className="sp-reallabel-info-text__text">{infoText}</div>
				</div>
			)}
			<ToggleControl
				label={false}
				checked={isPro ? false : attributes}
				onChange={(newField) => {
					// Pro-locked toggles never change state.
					if (isPro) {
						return;
					}
					return onChange
						? onChange(newField)
						: setAttributes({
								[attributesKey]: !attributes,
							});
				}}
				__nextHasNoMarginBottom={true}
			/>
		</div>
	);
};

export default memo(Toggle);
