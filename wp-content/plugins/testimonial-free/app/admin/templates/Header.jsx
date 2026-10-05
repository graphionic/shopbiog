import { __ } from "@wordpress/i18n";
import { Fragment, useState, useRef, useEffect } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import Drawer from "@mui/material/Drawer";
import useChangelogData from "../hooks/useChangelogData";
import { STORE_NAME } from "../store";
import { generateDocLink } from "@testimonial/constants";
import {
	Arrow,
	DocumentationIcon,
	TechSupport,
	SetupWizard,
	Roadmap,
	FeatRequest,
	Video,
	WhatsNew,
	Blog,
	Community,
	ChangelogIcon,
	CloseIcon,
	Support,
	Logo,
	AboutUsIcon,
	NavMenuIcon,
} from "../Icons";

const Header = ({ menuItems, currentPage, setPageAndHash }) => {
	const [showMobileNav, setShowMobileNav] = useState(false);
	const [showDrawer, setShowDrawer] = useState(false);
	const [activeItemPosition, setActiveItemPosition] = useState({ left: 0, width: 0 });
	const navRef = useRef();
	const itemsRef = useRef({});

	const { pluginVersion, homeUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);

	const { status: changelogStatus, changelog, errorMessage } = useChangelogData(showDrawer);

	const toggleDrawer = (open) => () => {
		setShowDrawer(open);
	};

	// Close the mobile nav dropdown on outside click or Escape.
	useEffect(() => {
		if (!showMobileNav) {
			return;
		}
		const onDocumentClick = (event) => {
			if (navRef.current && !navRef.current.contains(event.target)) {
				setShowMobileNav(false);
			}
		};
		const onKeyDown = (event) => {
			if (event.key === "Escape") {
				setShowMobileNav(false);
			}
		};
		document.addEventListener("click", onDocumentClick);
		document.addEventListener("keydown", onKeyDown);
		return () => {
			document.removeEventListener("click", onDocumentClick);
			document.removeEventListener("keydown", onKeyDown);
		};
	}, [showMobileNav]);

	// Update sliding underline position when the active item changes or the nav resizes.
	useEffect(() => {
		const item = itemsRef.current[currentPage];
		const nav = navRef.current;
		if (!item || !nav) {
			return;
		}
		const updatePosition = () => {
			const navRect = nav.getBoundingClientRect();
			const itemRect = item.getBoundingClientRect();
			setActiveItemPosition({
				left: itemRect.left - navRect.left,
				width: itemRect.width,
			});
		};
		updatePosition();
		if (typeof ResizeObserver === "undefined") {
			return;
		}
		const observer = new ResizeObserver(updatePosition);
		observer.observe(nav);
		return () => observer.disconnect();
	}, [currentPage]);

	const GET_HELP_ITEMS = [
		{
			title: __("Documentation", "testimonial-free"),
			Icon: DocumentationIcon,
			link: generateDocLink("introduction"),
		},
		{
			title: __("Technical Support", "testimonial-free"),
			Icon: TechSupport,
			link: "https://shapedplugin.com/create-new-ticket/",
		},
		{
			title: __("Setup Wizard", "testimonial-free"),
			Icon: SetupWizard,
			link: homeUrl + "wp-admin/admin.php?page=rtp_dashboard#setupwizard",
		},
		{
			title: __("Public Roadmap", "testimonial-free"),
			Icon: Roadmap,
			link: "",
		},
		{
			title: __("Request a Feature", "testimonial-free"),
			Icon: FeatRequest,
			link: "https://shapedplugin.com/contact-us/",
		},
		{
			title: __("Video Tutorials", "testimonial-free"),
			Icon: Video,
			link: "https://www.youtube.com/playlist?list=PLoUb-7uG-5jM2sjscSqBVj07VXOqt0qHZ",
		},
		{
			title: __("What's New", "testimonial-free"),
			Icon: WhatsNew,
			link: "https://realtestimonials.io/changelog/",
		},
		{
			title: __("Blog: Latest News", "testimonial-free"),
			Icon: Blog,
			link: "https://shapedplugin.com/blog/",
		},
		{
			title: __("Join Community", "testimonial-free"),
			Icon: Community,
			link: "https://community.shapedplugin.com/",
		},
		{
			title: __("About Us", "testimonial-free"),
			Icon: AboutUsIcon,
			link: "#about_us",
		},
	];

	return (
		<Fragment>
			<div className="sp-real-admin-dashboard-header">
				<div className="sp-real-block-setting-header-wrapper sp-d-flex sp-justify-between sp-align-center">
					{/* Left: Logo + Version */}
					<div className="sp-real-admin-dashboard-header-left sp-d-flex sp-align-center sp-gap-10px">
						<span className="sp-real-dashboard-logo">
							<Logo />
						</span>
						{pluginVersion && (
							<span onClick={toggleDrawer(true)} className="sp-real-plugin-version">
								<ChangelogIcon />
								{pluginVersion}
							</span>
						)}
					</div>

					{/* Center: Navigation (dropdown on mobile) */}
					<div
						className={`sp-real-admin-dashboard-nav${showMobileNav ? " sp-real-nav-open" : ""}`}
						ref={navRef}
					>
						<span
							className="sp-real-nav-sliding-indicator"
							style={{
								left: `${activeItemPosition.left}px`,
								width: `${activeItemPosition.width}px`,
							}}
						></span>
						<button
							type="button"
							className="sp-real-nav-mobile-toggle sp-align-center sp-cursor-pointer"
							aria-label={__("Dashboard menu", "testimonial-free")}
							aria-expanded={showMobileNav}
							onClick={() => setShowMobileNav((open) => !open)}
						>
							<NavMenuIcon />
						</button>
						<ul className="sp-d-flex">
							{menuItems &&
								menuItems.map((item) => (
									<li key={item.value}>
										<a
											ref={(el) => (itemsRef.current[item.value] = el)}
											href={item.hash}
											className={`sp-d-flex sp-align-center sp-gap-6px${currentPage === item.value ? " active" : ""}`}
											onClick={(e) => {
												e.preventDefault();
												setShowMobileNav(false);
												setPageAndHash(item.value);
											}}
										>
											{item?.icon && <span className="sp-real-nav-icon">{item.icon}</span>}
											{item.label}
											{item.badge && (
												<span className="sp-real-nav-badge">
													{__("NEW!", "testimonial-free")}
												</span>
											)}
										</a>
									</li>
								))}
						</ul>
					</div>

					{/* Right: Get Help */}
					<div className="sp-real-admin-dashboard-header-right sp-d-flex sp-gap-6px sp-cursor-pointer">
						<Support />
						<span className="sp-real-dashboard-get-help-label">{__("Get Help", "testimonial-free")}</span>
						<div className="sp-real-help-drop-down sp-d-hidden sp-flex-col">
							{GET_HELP_ITEMS.map(({ title, link, Icon }, index) => (
								<Fragment key={index}>
									{link && (
										<a
											href={link}
											target={link.startsWith("#") ? "_self" : "_blank"}
											rel={link.startsWith("#") ? "" : "noopener noreferrer"}
											className="sp-real-support-link sp-d-flex sp-align-center sp-gap-10px"
											onClick={(e) => {
												if (link.startsWith("#")) {
													e.preventDefault();
													setPageAndHash(link.replace("#", ""));
												}
											}}
										>
											<Icon />
											<span>{title}</span>
											<span className="sp-real-dropdown-arrow">
												<Arrow />
											</span>
										</a>
									)}
								</Fragment>
							))}
						</div>
					</div>
				</div>

				{/* Changelog Drawer */}
				<Drawer
					anchor="right"
					open={showDrawer}
					onClose={toggleDrawer(false)}
					slotProps={{
						paper: {
							className: "sp-real-changelog-wrapper",
						},
					}}
				>
					<div className="sp-real-changelog-heading sp-d-flex sp-justify-between">
						<p className="sp-real-changelog-heading-title">
							{__("Latest Updates - Changelog", "testimonial-free")}
						</p>
						<button
							className="sp-real-changelog-close-btn sp-d-flex sp-align-center sp-cursor-pointer"
							onClick={toggleDrawer(false)}
							type="button"
						>
							<CloseIcon />
						</button>
					</div>
					{changelogStatus === "loading" && (
						<div className="sp-real-changelog-details">{__("Loading...", "testimonial-free")}</div>
					)}
					{changelogStatus === "success" && (
						<div
							className="sp-real-changelog-details"
							dangerouslySetInnerHTML={{ __html: changelog }}
						></div>
					)}
					{changelogStatus === "error" && (
						<div className="sp-real-changelog-details">
							<p>{errorMessage}</p>
							<a href="https://realtestimonials.io/changelog/" target="_blank" rel="noopener noreferrer">
								{__("View the changelog", "testimonial-free")}
							</a>
						</div>
					)}
				</Drawer>
			</div>
		</Fragment>
	);
};

export default Header;
