import { createRoot } from "@wordpress/element";
import { testimonialBlocksInfo } from "@testimonial/constants";
import Library from "./Library";

export const currentBlockTitle = (blockName) => {
	const fullName = `sp-testimonial-pro/${blockName}`;
	const block = testimonialBlocksInfo[fullName];
	return block ? block?.title : "";
};

// Close modal.
let modalRoot = null;

export const onRemoveSinglePatternPopup = () => {
	if (modalRoot) {
		modalRoot.unmount();
		modalRoot = null;
	}
	const modalNode = document.querySelector(".sp-real-patterns-builder-modal");
	if (modalNode) {
		modalNode.remove();
	}
	document.body.classList.remove("sp-real-patterns-popup-open");
};
// Open modal.
export const onOpenSinglePatternPopup = (e, blockName, removeBlock = false) => {
	e.preventDefault();

	// If modal already exists, do nothing.
	if (document.querySelector(".sp-real-patterns-builder-modal")) {
		return;
	}

	const node = document.createElement("div");
	node.className = "sp-real-patterns-builder-modal sp-real-patterns-blocks-layouts";
	document.body.appendChild(node);

	modalRoot = createRoot(node);
	modalRoot.render(
		<Library
			isShow={true}
			onClose={onRemoveSinglePatternPopup}
			currentBlockName={blockName}
			removeBlock={removeBlock}
		/>
	);
	document.body.classList.add("sp-real-patterns-popup-open");

	// Close when clicking outside.
	setTimeout(() => {
		node.addEventListener("click", (event) => {
			if (event.target === node) {
				onRemoveSinglePatternPopup();
			}
		});
	}, 0);
};
