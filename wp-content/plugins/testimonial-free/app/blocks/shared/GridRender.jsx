import { TestimonialCard } from "@testimonial/templates";

/**
 * Shared editor render for grid-family layouts.
 *
 * Mirrors the frontend `RenderBlock::render_grid_blocks()`. Serves the grid,
 * masonry, bento-grid and polaroid-grid blocks. Layout-specific wrapper class
 * and inline style are derived from `blockName` + the `layout` prop.
 *
 * @param {Object} props
 * @param {Object} props.attributes       Block attributes.
 * @param {Array}  [props.testimonials=[]] Testimonials supplied by EditorWrapper.
 */
const GridRender = ({ attributes, testimonials = [] }) => {
	return (
		<div className="sp-real-grid-wrapper sp-real-cards-wrapper">
			{testimonials.map((testimonial, index) => (
				<TestimonialCard key={testimonial.id || index} testimonial={testimonial} attributes={attributes} />
			))}
		</div>
	);
};

export default GridRender;
