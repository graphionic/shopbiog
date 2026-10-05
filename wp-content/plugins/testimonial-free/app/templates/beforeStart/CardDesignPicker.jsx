import { __ } from "@wordpress/i18n";
import { memo } from "@wordpress/element";
import { useDispatch } from "@wordpress/data";
import { Tooltip } from "@wordpress/components";
import { onOpenSinglePatternPopup } from "../../ready-patterns/Controls";
import { API_ENDPOINTS } from "../../ready-patterns/constants";
import { ProBadgeIcon } from "../../ready-patterns/Icons";
import { cardDesignIcons } from "../../blocks/shared/Icons";
import { applyCardDesign } from "../../blocks/shared/cardDesign";
import "./editor.scss";

const CardDesignPicker = ({ blockName, clientId = "", attributes, setAttributes }) => {
	const { removeBlocks } = useDispatch("core/block-editor");
	const handleRemoveBlockByID = () => {
		removeBlocks(clientId);
	};

	const onChangeCardDesign = (newDesign) => applyCardDesign(newDesign, attributes, setAttributes);

	return (
		<div className="sp-real-before-start__card">
			<div className="sp-real-card-design-variation-picker sp-d-flex sp-flex-col">
				<div className="sp-real-before-start__header sp-d-flex sp-flex-col sp-gap-10px sp-align-center">
					<h3 className="sp-real-before-start__title">{__("Choose Your Card Design", "testimonial-free")}</h3>
					<p className="sp-real-before-start__subtitle">
						{__(
							"Select a design to display your testimonials. You can change it anytime.",
							"testimonial-free"
						)}
					</p>
				</div>
				<div className="sp-real-card-design-picker-popup sp-w-full sp-d-flex sp-justify-center sp-align-center">
					<div className="sp-real-card-design-picker-popup_wrapper sp-d-flex sp-flex-wrap sp-align-center sp-justify-center sp-gap-10px">
						{cardDesignIcons?.map(({ Icon, value, label, pro }) => (
							<Tooltip
								key={value}
								text={pro ? `${label} (${__("Pro", "testimonial-free")})` : label}
								placement="top"
							>
								<span
									className={`sp-real-icon-set sp-cursor-pointer sp-d-flex sp-align-center sp-justify-center${pro ? " sp-real-icon-set-pro" : ""}`}
									onClick={() =>
										pro
											? window.open(API_ENDPOINTS.UPGRADE_URL, "_blank", "noopener,noreferrer")
											: onChangeCardDesign(value)
									}
								>
									<Icon />
									{pro && (
										<span className="sp-real-pro-capsule">
											<ProBadgeIcon color="#fff" fill="#fff" />
											{__("Pro", "testimonial-free")}
										</span>
									)}
								</span>
							</Tooltip>
						))}
					</div>
				</div>
				{/* bottom */}
				<div className="sp-real-design-picker-actions sp-d-flex sp-align-center sp-justify-center sp-gap-10px">
					<span
						className="sp-real-block-preview-button sp-real-ready-patterns sp-d-flex sp-align-center sp-justify-center"
						onClick={(event) => onOpenSinglePatternPopup(event, blockName, handleRemoveBlockByID)}
					>
						{__("Start with Ready Patterns", "testimonial-free")}
					</span>
					<span
						className="sp-real-card-design-skip-button sp-cursor-pointer"
						onClick={() => onChangeCardDesign("design-one")}
					>
						{__("Skip for Now", "testimonial-free")}
					</span>
				</div>
			</div>
		</div>
	);
};

export default memo(CardDesignPicker);
