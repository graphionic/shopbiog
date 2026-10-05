import { __ } from "@wordpress/i18n";
import { useRef, useState } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import { LeftArrow, RightArrow } from "./Icons";
import { Stepper } from "@testimonial/components";
import { Toaster } from "react-hot-toast";
import WelcomePage from "../pages/welcome";
import BlocksSetup from "../pages/setup";
import FinishPage from "../pages/finish";
import { STORE_NAME } from "../store";

const SetupWizard = () => {
	const { dashboardInfo } = useSelect((select) => {
		const store = select(STORE_NAME);
		return {
			dashboardInfo: store.getDashboardInfo(),
		};
	}, []);
	const { pluginUrl, homeUrl } = dashboardInfo;
	const footer = document.querySelector("#wpfooter");
	if (footer) {
		footer.style.display = "none";
	}
	const nextBtnRef = useRef(null);
	const [stepNumber, setStepNumber] = useState(0);
	const [isExiting, setIsExiting] = useState(false);
	const [direction, setDirection] = useState("left");
	const [animationClass, setAnimationClass] = useState("");

	// steps.
	const setupSteps = ["Welcome", "Blocks", "Finish"];

	const onAnimationEnd = () => {
		if (!isExiting) {
			return;
		}
		if (direction === "left") {
			setStepNumber((prev) => prev + 1);
		} else {
			setStepNumber((prev) => prev - 1);
		}
		setAnimationClass("is-entering");
		setIsExiting(false);
	};

	const nextStep = () => {
		setDirection("left");
		setIsExiting(true);
		setAnimationClass("is-exiting");
	};

	const handleNext = () => {
		nextStep();
	};

	const handlePrev = () => {
		setDirection("right");
		setIsExiting(true);
		setAnimationClass("is-exiting");
	};

	return (
		<div className="sp-real-admin-dashboard-container">
			<div className="sp-real-setup-wizard-wrapper sp-d-flex sp-flex-col sp-align-center sp-justify-center">
				<div className="sp-real-setup-wizard-content sp-d-flex sp-flex-col sp-align-center sp-justify-center">
					<Stepper steps={setupSteps} current={stepNumber} />
					<div className={`sp-real-setup-step-page ${animationClass}`} onAnimationEnd={onAnimationEnd}>
						{setupSteps[stepNumber] === "Welcome" && <WelcomePage />}
						{setupSteps[stepNumber] === "Blocks" && <BlocksSetup />}
						{setupSteps[stepNumber] === "Finish" && <FinishPage />}
					</div>
					<div className="sp-real-setup-wizard-btn-wrapper sp-d-flex">
						{stepNumber !== 0 && stepNumber !== setupSteps.length - 1 && (
							<button className="sp-real-setup-wizard-nav-btn prev-btn" onClick={handlePrev}>
								<LeftArrow />
								{__("Previous", "testimonial-free")}
							</button>
						)}

						{stepNumber === 0 && (
							<a
								className="sp-real-setup-wizard-nav-btn prev-btn"
								href={`${homeUrl}wp-admin/admin.php?page=rtp_dashboard`}
							>
								{"Skip it"}
							</a>
						)}
						{stepNumber !== setupSteps.length - 1 && (
							<button
								className="sp-real-setup-wizard-nav-btn next-btn"
								onClick={handleNext}
								ref={nextBtnRef}
							>
								{__("Next Step", "testimonial-free")}
								<RightArrow />
							</button>
						)}
					</div>
				</div>
				<img
					className="setup-wizard-bg"
					src={`${pluginUrl}src/Admin/assets/images/setup-wizard/setup-wizard-bg.svg`}
					alt="setup wizard"
				/>
				<Toaster
					position="top-right"
					toastOptions={{
						style: {
							padding: "16px 24px",
							fontSize: "18px",
							borderRadius: "10px",
							maxWidth: "400px",
						},
					}}
				/>
			</div>
		</div>
	);
};

export default SetupWizard;
