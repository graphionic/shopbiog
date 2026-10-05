import { __, sprintf } from "@wordpress/i18n";
import { useEffect, useRef, useState } from "@wordpress/element";
import { decodeEntities } from "@wordpress/html-entities";
import { BackArrowIcon, CloseIcon, DesktopIcon, DownloadIcon, MobileIcon, ProBadgeIcon, TabletIcon } from "./Icons";
import { API_ENDPOINTS } from "./constants";

/**
 * Preview device widths. The iframe renders at this width, then scales down with
 * a CSS transform to fit the measured stage box.
 */
const PREVIEW_DEVICES = [
	{ key: "desktop", width: 1440, Icon: DesktopIcon },
	{ key: "tablet", width: 834, Icon: TabletIcon },
	{ key: "mobile", width: 390, Icon: MobileIcon },
];

// Frame height used before the stage has been measured, and in the (theoretical)
// environment without ResizeObserver.
const FALLBACK_STAGE_HEIGHT = 560;

/**
 * Translated label for a device key.
 *
 * @param {string} key Device key.
 * @return {string} Label.
 */
function deviceLabel(key) {
	if ("tablet" === key) {
		return __("Tablet", "testimonial-free");
	}
	if ("mobile" === key) {
		return __("Mobile", "testimonial-free");
	}
	return __("Desktop", "testimonial-free");
}

/**
 * In-modal preview drawer: a single header row — Back + title, the centered
 * device switcher, Insert + close (Back and close both return to the pattern
 * grid) — over a full-width stage holding the live
 * iframe.
 *
 * A pattern flagged `pro` in the library payload builds on Pro-only blocks, so
 * the header offers an Upgrade link instead of Insert — the same swap
 * `PatternCard` makes in the grid. Preview itself stays open to everyone; it is
 * the upsell. Testimonial patterns carry no description/tags/requirements, so there
 * is no meta column; Insert lives in the header.
 *
 * The iframe is sandboxed without `allow-same-origin` so the framed demo page
 * cannot reach the editor origin. The drawer owns Escape (capture phase) while
 * mounted so it never bubbles up to close the whole modal.
 *
 * @param {Object}   props
 * @param {Object}   props.pattern     Manifest item being previewed.
 * @param {Function} props.onClose     Close the preview — back to the pattern grid.
 * @param {Function} props.onInsert    Insert handler — receives the pattern.
 * @param {boolean}  props.isInserting Whether this pattern's import is in flight.
 */
const PreviewDrawer = ({ pattern, onClose, onInsert, isInserting }) => {
	const [device, setDevice] = useState(PREVIEW_DEVICES[0].key);
	const [isFrameLoading, setIsFrameLoading] = useState(true);
	// Measured content box of the stage — the frame is scaled to fit it, so the
	// preview stays fitted at any drawer width and through window resizes.
	const [stage, setStage] = useState({ width: 0, height: 0 });
	const backRef = useRef(null);
	const stageRef = useRef(null);
	const drawerRef = useRef(null);

	useEffect(() => {
		backRef.current?.focus();
	}, []);

	useEffect(() => {
		const handleKeyDown = (event) => {
			if ("Escape" === event.key) {
				event.preventDefault();
				event.stopPropagation();
				onClose();
			}
		};
		document.addEventListener("keydown", handleKeyDown, true);
		return () => document.removeEventListener("keydown", handleKeyDown, true);
	}, [onClose]);

	useEffect(() => {
		const node = stageRef.current;
		if (!node || "undefined" === typeof ResizeObserver) {
			return;
		}
		const observer = new ResizeObserver(([entry]) => {
			// `contentRect` excludes the stage's own padding.
			const { width, height } = entry.contentRect;
			setStage({ width: Math.round(width), height: Math.round(height) });
		});
		observer.observe(node);
		return () => observer.disconnect();
	}, []);

	// Mouse-down rather than click, so releasing a text selection that started
	// inside the drawer never counts as clicking the dimmed area.
	const handleBackdropMouseDown = (event) => {
		if (drawerRef.current && !drawerRef.current.contains(event.target)) {
			onClose();
		}
	};

	const activeDevice = PREVIEW_DEVICES.find((entry) => entry.key === device) || PREVIEW_DEVICES[0];
	const scale = stage.width ? Math.min(1, stage.width / activeDevice.width) : 1;
	const frameHeight = Math.round((stage.height || FALLBACK_STAGE_HEIGHT) / scale);
	const previewUrl = pattern?.url || "";
	const patternName = decodeEntities(pattern?.name || "");
	const thumb = pattern?.image || "";
	const isPro = !!pattern?.pro;

	// A new pattern swaps the iframe src — show the preloader again until it lands.
	// Device switching only resizes the frame, so it must not reset this.
	useEffect(() => {
		setIsFrameLoading(true);
	}, [previewUrl]);

	return (
		<div className="sp-real-ready-patterns-drawer-backdrop" onMouseDown={handleBackdropMouseDown}>
			<section
				className="sp-real-ready-patterns-drawer"
				ref={drawerRef}
				role="dialog"
				aria-modal="true"
				aria-labelledby="sp-real-ready-patterns-drawer-title"
			>
				<header className="sp-real-ready-patterns-drawer-header">
					<div className="sp-real-ready-patterns-drawer-header-left sp-d-flex sp-align-center">
						<button
							type="button"
							className="sp-real-ready-patterns-drawer-back"
							onClick={onClose}
							ref={backRef}
						>
							<BackArrowIcon />
							{__("Back", "testimonial-free")}
						</button>
						<h2 className="sp-real-ready-patterns-drawer-title" id="sp-real-ready-patterns-drawer-title">
							{patternName}
						</h2>
					</div>

					<div
						className="sp-real-ready-patterns-drawer-devices"
						role="group"
						aria-label={__("Preview width", "testimonial-free")}
					>
						{PREVIEW_DEVICES.map((entry) => {
							const { Icon } = entry;
							return (
								<button
									key={entry.key}
									type="button"
									className={`sp-real-ready-patterns-drawer-device${
										entry.key === device ? " is-active" : ""
									}`}
									aria-pressed={entry.key === device}
									aria-label={deviceLabel(entry.key)}
									title={deviceLabel(entry.key)}
									onClick={() => setDevice(entry.key)}
								>
									<Icon />
								</button>
							);
						})}
					</div>

					<div className="sp-real-ready-patterns-drawer-header-right sp-d-flex sp-align-center">
						{isPro ? (
							<a
								className="sp-real-ready-patterns-drawer-upgrade-btn sp-d-flex sp-align-center sp-gap-6px"
								href={API_ENDPOINTS.UPGRADE_URL}
								target="_blank"
								rel="noopener noreferrer"
							>
								<ProBadgeIcon color="#fff" />
								{__("Upgrade to Pro", "testimonial-free")}
							</a>
						) : (
							<button
								type="button"
								className={`sp-real-ready-patterns-insert-btn${isInserting ? " is-busy" : ""}`}
								onClick={() => onInsert(pattern)}
								disabled={isInserting}
							>
								{isInserting ? __("Inserting…", "testimonial-free") : __("Insert", "testimonial-free")}
								{isInserting ? (
									<span className="dashicons dashicons-update sp-real-ready-patterns-spin" />
								) : (
									<DownloadIcon />
								)}
							</button>
						)}
						<button
							type="button"
							className="sp-real-ready-patterns-drawer-close"
							aria-label={__("Close preview", "testimonial-free")}
							onClick={onClose}
						>
							<CloseIcon />
						</button>
					</div>
				</header>

				<div className="sp-real-ready-patterns-drawer-body">
					<div className="sp-real-ready-patterns-drawer-stage" ref={stageRef}>
						{previewUrl ? (
							<iframe
								className={`sp-real-ready-patterns-drawer-frame${isFrameLoading ? " is-loading" : ""}`}
								title={
									patternName
										? sprintf(
												/* translators: %s: pattern name */ __(
													"Preview of %s",
													"testimonial-free"
												),
												patternName
											)
										: __("Pattern preview", "testimonial-free")
								}
								src={previewUrl}
								sandbox="allow-scripts allow-forms allow-popups"
								onLoad={() => setIsFrameLoading(false)}
								style={{
									width: `${activeDevice.width}px`,
									height: `${frameHeight}px`,
									transform: `scale(${scale})`,
								}}
							/>
						) : (
							<div className="sp-real-ready-patterns-drawer-thumb">
								{thumb ? (
									<img src={thumb} alt="" />
								) : (
									<p>{__("No preview available for this pattern.", "testimonial-free")}</p>
								)}
							</div>
						)}

						{previewUrl && isFrameLoading ? (
							<div className="sp-real-ready-patterns-drawer-loader" aria-hidden="true">
								<span className="sp-real-ready-patterns-drawer-loader-spinner" />
								<span className="sp-real-ready-patterns-drawer-loader-text">
									{__("Loading preview…", "testimonial-free")}
								</span>
							</div>
						) : null}
					</div>
				</div>
			</section>
		</div>
	);
};

export default PreviewDrawer;
