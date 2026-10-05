import { Button } from "@wordpress/components";
import { ResetIcon } from "../../icons";
import "./editor.scss";

const ResetButton = ({ onClick }) => {
	return (
		<Button className="sp-real-component-header-reset" onClick={onClick}>
			<ResetIcon />
		</Button>
	);
};

export default ResetButton;
