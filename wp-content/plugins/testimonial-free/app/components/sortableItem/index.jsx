import { useSortable } from "@dnd-kit/sortable";
import { CSS } from "@dnd-kit/utilities";

const SortableItem = ({ id, children, disabled = false }) => {
	const { attributes, listeners, setNodeRef, transform, transition } = useSortable({ id });
	const style = {
		transform: CSS.Transform.toString(transform),
		transition,
	};

	return (
		<div
			style={style}
			ref={setNodeRef}
			{...attributes}
			{...listeners}
			className={`sp-real-sortable-item${disabled ? " sp-sortable-disabled" : ""}`}
		>
			{children}
		</div>
	);
};

export default SortableItem;
