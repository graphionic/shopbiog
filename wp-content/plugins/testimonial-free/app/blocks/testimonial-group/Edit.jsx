import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";
import { testimonialBlocksInfo } from "@testimonial/constants";
import { getRealBlockProps } from "@testimonial/controls";
import { useSelect } from "@wordpress/data";
import { useMemo } from "@wordpress/element";

const TestimonialGroupEdit = ({ clientId, attributes }) => {
	const { uniqueId } = attributes;
	const blockProps = getRealBlockProps(useBlockProps());
	const innerBlocks = useSelect((select) => select("core/block-editor").getBlocks(clientId), [clientId]);
	const findBlock = useMemo(
		() => innerBlocks.find((block) => Object.keys(testimonialBlocksInfo).includes(block.name)),
		[innerBlocks]
	);
	const allowedBlocks = useMemo(() => (findBlock?.name ? [findBlock.name] : []), [findBlock]);

	return (
		<div {...blockProps}>
			<div id={uniqueId} className="sp-real-testimonial-group">
				<InnerBlocks allowedBlocks={allowedBlocks} orientation="vertical" renderAppender={false} />
			</div>
		</div>
	);
};

export default TestimonialGroupEdit;
