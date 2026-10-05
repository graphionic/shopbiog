import { dispatch } from "@wordpress/data";
import { Button } from "@wordpress/components";
import { useDeviceType } from "@testimonial/controls";
import { store as editorStore } from "@wordpress/editor";
import { DesktopIcon, MobileIcon, TabletIcon } from "./Icons";
import { memo } from "@wordpress/element";

const Responsive = () => {
	const Device = (e) => {
		const deviceType = e?.target?.closest("button").value;
		// `core/editor` owns the device type since WP 6.5 (post + site editors).
		// Fall back to pre-6.5 experimental actions on edit-site / edit-post.
		const editor = dispatch(editorStore);
		if (typeof editor?.setDeviceType === "function") {
			editor.setDeviceType(deviceType);
			return;
		}
		const editSite = dispatch("core/edit-site");
		if (typeof editSite?.__experimentalSetPreviewDeviceType === "function") {
			editSite.__experimentalSetPreviewDeviceType(deviceType);
			return;
		}
		const editPost = dispatch("core/edit-post");
		if (typeof editPost?.__experimentalSetPreviewDeviceType === "function") {
			editPost.__experimentalSetPreviewDeviceType(deviceType);
		}
	};

	const deviceType = useDeviceType();

	const DeviceIcon = () => {
		if ("Desktop" === deviceType) {
			return <DesktopIcon />;
		}
		if ("Tablet" === deviceType) {
			return <TabletIcon />;
		}
		if ("Mobile" === deviceType) {
			return <MobileIcon />;
		}
		return <DesktopIcon />;
	};

	return (
		<div className="sp-real-responsive-picker">
			<div className="sp-real-units-indicator">
				<span className="sp-real-units-label sp-d-flex sp-align-center sp-justify-center sp-cursor-pointer">
					<DeviceIcon />
				</span>
				<div className="sp-real-units-btn">
					<Button className={deviceType === "Desktop" ? "active" : ""} value={"Desktop"} onClick={Device}>
						<DesktopIcon />
					</Button>
					<Button className={deviceType === "Tablet" ? "active" : ""} value={"Tablet"} onClick={Device}>
						<TabletIcon />
					</Button>
					<Button className={deviceType === "Mobile" ? "active" : ""} value={"Mobile"} onClick={Device}>
						<MobileIcon />
					</Button>
				</div>
			</div>
		</div>
	);
};

export default memo(Responsive);
