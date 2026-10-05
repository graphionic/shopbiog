import { useCanvasPanelClick, useSwiperConfig } from "@testimonial/hooks";
import { NavigationArrow, TestimonialCard } from "@testimonial/templates";

/**
 * Shared editor render for swiper-driven layouts.
 *
 * Mirrors the frontend `RenderBlock::render_carousel_blocks()`. Serves the
 * carousel and slider blocks with identical swiper markup (`sp-testimonial-swiper`
 * + `sp-real-carousel-style-*`). The `layout` flag only gates editor slide-
 * grouping: "carousel" supports grouped effect slides, "slider" renders one
 * card per slide.
 *
 * @param {Object} props
 * @param {Object} props.attributes          Block attributes.
 * @param {string} [props.layout="carousel"] "carousel" or "slider".
 * @param {Array}  [props.testimonials=[]]   Testimonials supplied by EditorWrapper.
 */
const CarouselRender = ({ attributes, layout = "carousel", testimonials = [] }) => {
	const {
		slidingEffect,
		enableNavigationArrow,
		enablePaginationDots,
		paginationStyle,
		paginationDotsPosition,
		carouselStyle,
		navIconName,
		navIconPosition,
		align,
	} = attributes;

	const {
		swiperDir,
		swiperRef,
		navNextEl,
		navPrevEl,
		paginationRef,
		shouldGroupSlides,
		testimonialGroups,
		groupSize,
		groupGap,
	} = useSwiperConfig(attributes, testimonials);

	// Clicking the arrows or the dots opens the inspector panel that controls them.
	const onCanvasClick = useCanvasPanelClick();

	return (
		<>
			<div
				ref={swiperRef}
				key={`sp-real-swiper-${slidingEffect}-${carouselStyle}-${align}-${swiperDir}`}
				className={`sp-testimonial-swiper swiper sp-real-carousel-style-${carouselStyle || "default"}`}
				dir="ltr"
			>
				<div className="sp-real-swiper-wrapper swiper-wrapper sp-real-cards-wrapper">
					{layout !== "slider" && shouldGroupSlides
						? testimonialGroups.map((group, groupIndex) => (
								<div
									key={`sp-real-effect-group-${groupIndex}`}
									className="sp-real-swiper-slide swiper-slide sp-real-swiper-effect-group"
									style={{
										"--sp-real-carousel-effect-columns": `${groupSize}`,
										"--sp-real-carousel-effect-gap": `${groupGap}px`,
									}}
								>
									{group.map((testimonial, index) => (
										<TestimonialCard
											key={testimonial.id || `${groupIndex}-${index}`}
											testimonial={testimonial}
											attributes={attributes}
										/>
									))}
								</div>
							))
						: testimonials.map((testimonial, index) => (
								<TestimonialCard
									key={testimonial.id || index}
									testimonial={testimonial}
									attributes={attributes}
									isSwiperSlide={true}
								/>
							))}
				</div>
			</div>
			{enableNavigationArrow && (
				<NavigationArrow
					navNextEl={navNextEl}
					navPrevEl={navPrevEl}
					navIconName={navIconName}
					navIconPosition={navIconPosition}
					onClick={onCanvasClick}
				/>
			)}
			{enablePaginationDots && (
				<div
					key={`sp-pag-${paginationStyle}`}
					ref={paginationRef}
					className={`sp-real-swiper-pagination swiper-pagination sp-d-flex sp-align-center sp-justify-${paginationDotsPosition} ${paginationStyle}`}
					onClick={onCanvasClick}
				></div>
			)}
		</>
	);
};

export default CarouselRender;
