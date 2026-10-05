import { memo } from "@wordpress/element";
import Responsive from "../responsive";
import ResetButton from "../resetButton";
import Units from "../units";

const ComponentHeader = ({ label, attributes, setAttributes, attributesKey = "", units = false, onReset = false }) => {
	return (
		<div className="sp-real-component-header sp-d-flex sp-justify-between sp-mb-8px">
			<div className="sp-real-component-header-left sp-d-flex sp-align-center">
				<span className="sp-real-component-title">{label}</span>
				{attributes?.device && <Responsive />}
			</div>
			<div className="sp-real-component-header-right sp-d-flex sp-align-center sp-gap-8px">
				{onReset && <ResetButton onClick={() => onReset()} />}
				{units && (
					<Units
						attributes={attributes}
						setAttributes={setAttributes}
						attributesKey={attributesKey}
						units={units}
					/>
				)}
			</div>
		</div>
	);
};

export default memo(ComponentHeader);
