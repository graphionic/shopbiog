import { RightArrowLong, TickIcon } from "../../admin/setup-wizard/Icons";
import "./editor.scss";

/**
 * Shared progress stepper used by the setup wizard and the block onboarding
 * (beforeStart) screen.
 *
 * @param {Object}   props
 * @param {string[]} props.steps   Ordered step labels.
 * @param {number}   props.current Active step index (0-based).
 */
const Stepper = ({ steps = [], current = 0 }) => {
	return (
		<div className="sp-real-setup-steps sp-d-flex">
			{steps?.map((step, index) => (
				<div key={index} className="sp-real-setup-step sp-d-flex sp-align-center">
					<span
						className={`sp-real-setup-step-number sp-d-flex sp-align-center sp-justify-center${
							index === current ? " active" : ""
						}${index < current ? " previous" : ""}`}
					>
						{index < current ? <TickIcon /> : "0" + (index + 1)}
					</span>
					<span className="sp-real-setup-step-title">{step}</span>
					{index !== steps.length - 1 && <RightArrowLong />}
				</div>
			))}
		</div>
	);
};

export default Stepper;
