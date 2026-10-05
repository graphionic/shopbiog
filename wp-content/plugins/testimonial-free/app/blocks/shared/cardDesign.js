import { cardItemsPositions } from "@testimonial/constants";
import { inArray } from "@testimonial/controls";

// Reorder cardContents to match the design's position template, appending any
// items not covered by the template so nothing is lost.
const getReorderedContents = (newDesign, cardContents) => {
	const positions = cardItemsPositions[newDesign];
	const reordered = positions.map((id) => cardContents.find((item) => item.id === id)).filter(Boolean);
	cardContents.forEach((item) => {
		if (!reordered.some((i) => i.id === item.id)) {
			reordered.push(item);
		}
	});
	return reordered;
};

const changeSpacing = (attr, newVal) => {
	return {
		...attr,
		device: {
			...attr.device,
			Desktop: newVal,
		},
	};
};

// Build the attribute patch for a card design change: reorders cardContents and
// applies per-design default margins. Shared by GeneralTab and CardDesignPicker.
export const buildCardDesignAttributes = (newDesign, attributes) => {
	const { cardContents, imageMargin, excerptMargin, ratingIconMargin, designationMargin } = attributes;

	return {
		cardDesign: newDesign,
		cardAlignment: inArray(["design-one", "design-two"], newDesign) ? "center" : "left",
		cardContents: getReorderedContents(newDesign, cardContents),
		imageMargin: inArray(["design-five", "design-six"], newDesign)
			? changeSpacing(imageMargin, { top: 32, right: 0, bottom: 0, left: 0 })
			: changeSpacing(imageMargin, { top: 0, right: 0, bottom: 12, left: 0 }),
		excerptMargin:
			newDesign === "design-three"
				? changeSpacing(excerptMargin, { top: 0, right: 0, bottom: 12, left: 0 })
				: changeSpacing(excerptMargin, { top: 12, right: 0, bottom: 12, left: 0 }),
		ratingIconMargin: inArray(["design-three", "design-five", "design-six"], newDesign)
			? changeSpacing(ratingIconMargin, { top: 0, right: 0, bottom: 0, left: 0 })
			: changeSpacing(ratingIconMargin, { top: 12, right: 0, bottom: 12, left: 0 }),
		designationMargin:
			newDesign === "design-six"
				? changeSpacing(designationMargin, { top: 2, right: 0, bottom: 12, left: 0 })
				: changeSpacing(designationMargin, { top: 2, right: 0, bottom: 2, left: 0 }),
	};
};

// Convenience: apply a card design change directly via setAttributes.
export const applyCardDesign = (newDesign, attributes, setAttributes) => {
	setAttributes(buildCardDesignAttributes(newDesign, attributes));
};
