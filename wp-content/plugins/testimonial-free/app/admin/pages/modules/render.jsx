import Toggle from "react-toggle";
import { __ } from "@wordpress/i18n";
import { toast } from "react-hot-toast";
import { memo, useState } from "@wordpress/element";
import { Modal } from "@wordpress/components";
import { useSelect, useDispatch } from "@wordpress/data";
import { defaultDashboardSettings } from "@testimonial/constants";
import { Demos, Docs, VideoPlayLiteIcon, SettingsIcon } from "../../Icons";
import { ProIconLight } from "./Icons";
import { STORE_NAME } from "../../store";

const dynamicMenuItems = [
	{
		// High index → reference node is undefined → appended at the bottom.
		index: 99,
		key: "testimonial_form",
		label: __("Testimonial Form", "testimonial-free"),
		href: "edit.php?post_type=spt_testimonial_form",
	},
];

// handle toggle menus.
const handleMenus = (name, isActive) => {
	const menuItem = dynamicMenuItems?.find((i) => i.key === name);
	const menuWrapper = document.querySelector("#menu-posts-spt_testimonial .wp-submenu-wrap");
	// return if menu item or wrapper is not exist.
	if (!menuItem || !menuWrapper) {
		return;
	}
	const existingLink = menuWrapper.querySelector(`a[href="${menuItem.href}"]`);
	// REMOVE ITEM.
	if (!isActive) {
		if (existingLink) {
			const li = existingLink.closest("li");
			li?.remove();
		}
		return;
	}
	// RESTORE ITEM (if missing).
	if (isActive && !existingLink) {
		const li = document.createElement("li");
		const a = document.createElement("a");
		a.href = menuItem.href;
		a.textContent = menuItem.label;
		li.appendChild(a);
		// get reference to existing child at that index.
		const referenceNode = menuWrapper.children[menuItem.index];
		menuWrapper.insertBefore(li, referenceNode);
	}
};

// notification message.
const show_notification = (enabled, optionKey = "modules") => {
	const message =
		(optionKey === "modules" ? __("Module", "testimonial-free") : __("Integration", "testimonial-free")) +
		(enabled ? __(" enabled successfully", "testimonial-free") : __(" disabled successfully", "testimonial-free"));
	toast.success(message, { style: { marginTop: "20px", fontSize: "14px" } });
};

const RenderModuleCard = ({ items = [], optionKey = "integrations", hideUpcoming = false }) => {
	const dashboardSettings = useSelect((select) => select(STORE_NAME).getSettings());
	const { saveSettings } = useDispatch(STORE_NAME);
	const [openedPopup, setOpenedPopup] = useState("");
	if (Object.keys(dashboardSettings).length === 0) {
		return;
	}

	const activeItem = items.find((item) => item.id === openedPopup);
	const SettingsComponent = activeItem?.SettingsComponent;
	// default settings.
	const moduleData = dashboardSettings?.[optionKey] || defaultDashboardSettings[optionKey];

	const handleSaveData = (queryData) => {
		const modifiedData = { ...dashboardSettings, [optionKey]: queryData };
		saveSettings({ settings: modifiedData });
	};

	const handleToggle = (key, newValue) => {
		handleSaveData({ ...moduleData, [key]: { ...moduleData[key], is_active: newValue } });
		show_notification(newValue, optionKey);
		// toggle menu.
		if (optionKey === "modules") {
			handleMenus(key, newValue);
		}
	};

	// `config.js` uses "#" as a "link not published yet" placeholder. It is truthy,
	// so treat it as absent — otherwise the chip renders and opens nothing.
	const hasLink = (link) => Boolean(link) && "#" !== link;

	return (
		<div
			className={`sp-real-integrations-page-items-wrapper sp-d-grid sp-grid-cols-${optionKey === "modules" ? "3" : "2"}`}
		>
			{items?.map(({ id, key, Icon, label, desc, demoLink, docLink, videoLink, upcoming, settings, pro }) => (
				<div
					key={id}
					className={`sp-real-integrations-card sp-d-flex${upcoming ? " sp-real-upcoming-card" : ""}`}
				>
					<div className="sp-real-integrations-card__icon">
						<Icon />
					</div>
					<div className="sp-real-integrations-card__content sp-d-flex sp-flex-col sp-justify-between">
						<div className="sp-d-flex sp-flex-col sp-gap-8px">
							<div className="sp-d-flex sp-align-center sp-justify-between">
								<h4 className="sp-real-integrations-card__label">
									{label}
									{!hideUpcoming && upcoming && (
										<span className="sp-real-dashboard-upcoming-text small">
											{__("Upcoming", "testimonial-free")}
										</span>
									)}
								</h4>
								<div className="sp-d-flex sp-align-center sp-gap-8px">
									{pro ? (
										<span className="sp-real-integrations-card__pro-badge sp-d-flex sp-align-center sp-gap-4px">
											<ProIconLight />
											{__("PRO", "testimonial-free")}
										</span>
									) : (
										key && (
											<Toggle
												icons={false}
												defaultChecked={upcoming ? false : moduleData?.[key]?.is_active}
												onChange={() => {
													if (!upcoming) {
														handleToggle(key, !moduleData?.[key]?.is_active);
													}
												}}
											/>
										)
									)}
								</div>
							</div>
							<span className="sp-real-integrations-card__desc">{desc}</span>
						</div>
						{(hasLink(docLink) || hasLink(demoLink) || hasLink(videoLink) || settings) && (
							<div className="sp-real-integrations-card__links sp-d-flex sp-align-center sp-justify-between">
								{(hasLink(docLink) || hasLink(demoLink) || hasLink(videoLink)) && (
									<div className="sp-d-flex sp-align-center sp-gap-10px">
										{hasLink(demoLink) && (
											<a
												className="sp-d-flex sp-align-center sp-gap-4px"
												href={demoLink}
												target="_blank"
												rel="noreferrer"
											>
												<Demos /> Demo
											</a>
										)}
										{hasLink(videoLink) && (
											<a
												className="sp-d-flex sp-align-center sp-gap-4px"
												href={videoLink}
												target="_blank"
												rel="noreferrer"
											>
												<VideoPlayLiteIcon /> Video
											</a>
										)}
										{hasLink(docLink) && (
											<a
												className="sp-d-flex sp-align-center sp-gap-4px"
												href={docLink}
												target="_blank"
												rel="noreferrer"
											>
												<Docs /> Docs
											</a>
										)}
									</div>
								)}
								{settings && !upcoming && (
									<span
										className="sp-real-integrations-card__settings sp-cursor-pointer sp-d-flex"
										role="button"
										tabIndex={0}
										onClick={() => setOpenedPopup(id)}
										onKeyDown={(e) => {
											if (e.key === "Enter") {
												setOpenedPopup(id);
											}
										}}
									>
										<SettingsIcon strokeColor="currentColor" />
									</span>
								)}
							</div>
						)}
					</div>
				</div>
			))}
			{SettingsComponent && (
				<Modal
					title={activeItem?.label || __("Settings", "testimonial-free")}
					className="sp-real-integration-settings-popup"
					onRequestClose={() => setOpenedPopup("")}
				>
					<SettingsComponent onClose={() => setOpenedPopup("")} />
				</Modal>
			)}
		</div>
	);
};

export default memo(RenderModuleCard);
