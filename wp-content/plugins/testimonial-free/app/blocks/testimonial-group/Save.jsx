import { InnerBlocks } from "@wordpress/block-editor";

const Save = ({ attributes }) => {
	const { uniqueId, align } = attributes;

	return (
		<div className={`sp-real-testimonial-block align${align}`}>
			<div id={uniqueId} className="sp-real-testimonial-group">
				<InnerBlocks.Content />
			</div>
		</div>
	);
};

export default Save;
