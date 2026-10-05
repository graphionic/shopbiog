import { __ } from "@wordpress/i18n";
import { ToggleControl, InputControl, BackgroundControl, Spacing } from "@testimonial/components";

export const AdvancedGeneralTab = ({ attributes, setAttributes }) => {
	const { realBackground, realPadding, realMargin } = attributes;
	return (
		<>
			<BackgroundControl
				label={__("Background Type", "testimonial-free")}
				attributes={realBackground}
				attributesKey={"realBackground"}
				setAttributes={setAttributes}
				activeState={"normal"}
			/>
			<Spacing
				label={__("Padding", "testimonial-free")}
				attributes={realPadding}
				attributesKey={"realPadding"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 20, right: 20, bottom: 20, left: 20 },
				}}
			/>
			<Spacing
				label={__("Margin", "testimonial-free")}
				attributes={realMargin}
				attributesKey={"realMargin"}
				setAttributes={setAttributes}
				units={["px", "%", "em"]}
				defaultValue={{
					unit: "px",
					value: { top: 20, right: 20, bottom: 20, left: 20 },
				}}
			/>
		</>
	);
};

export const VisibilityTab = ({ attributes, setAttributes }) => {
	const { hideOnDesktop, hideOnTablet, hideOnMobile } = attributes;
	return (
		<>
			<ToggleControl
				label={__("Hide on Desktop", "testimonial-free")}
				attributes={hideOnDesktop}
				attributesKey={"hideOnDesktop"}
				setAttributes={setAttributes}
			/>
			<ToggleControl
				label={__("Hide on Tablet", "testimonial-free")}
				attributes={hideOnTablet}
				attributesKey={"hideOnTablet"}
				setAttributes={setAttributes}
			/>
			<ToggleControl
				label={__("Hide on Mobile", "testimonial-free")}
				attributes={hideOnMobile}
				attributesKey={"hideOnMobile"}
				setAttributes={setAttributes}
			/>
		</>
	);
};

export const AdvancedTab = ({ attributes, setAttributes }) => {
	const { customClassName, customIdName } = attributes;

	return (
		<>
			<InputControl
				label={__("Custom CSS Class", "testimonial-free")}
				inputType="text"
				attributes={customClassName}
				attributesKey={"customClassName"}
				setAttributes={setAttributes}
			/>
			<InputControl
				label={__("Custom CSS ID", "testimonial-free")}
				inputType="text"
				attributes={customIdName}
				attributesKey={"customIdName"}
				setAttributes={setAttributes}
			/>
		</>
	);
};
