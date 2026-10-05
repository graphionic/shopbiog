import * as ReactDOM from "@wordpress/element";
import Render from "./Render";
import "./editor.scss";
import "./store";

window.addEventListener("DOMContentLoaded", () => {
	const element = document.getElementById("sp-real-admin-dashboard-wrapper");
	if (!element) {
		return;
	}
	if (typeof ReactDOM.createRoot === "function") {
		const root = ReactDOM.createRoot(element);
		root.render(<Render />);
	} else if (typeof ReactDOM.render === "function") {
		ReactDOM.render(<Render />, element);
	}
});
