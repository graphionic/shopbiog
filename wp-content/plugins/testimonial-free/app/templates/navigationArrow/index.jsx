import { memo } from "@wordpress/element";
import { ARROW_ICON_OPTIONS } from "@testimonial/constants";

const NavigationArrow = ({ navPrevEl, navNextEl, navIconName, navIconPosition, onClick }) => {
	const icon = ARROW_ICON_OPTIONS?.find((i) => i.value === navIconName)?.icon;

	return (
		<div
			className={`sp-real-swiper-nav-arrows sp-d-flex sp-align-center sp-justify-between sp-real-nav-pos-${navIconPosition}`}
			onClick={onClick}
		>
			<div
				ref={navPrevEl}
				className="sp-real-swiper-navigation sp-real-nav-prev sp-d-flex sp-align-center sp-justify-center"
			>
				{icon}
			</div>
			<div
				ref={navNextEl}
				className="sp-real-swiper-navigation sp-real-nav-next sp-d-flex sp-align-center sp-justify-center"
			>
				{icon}
			</div>
		</div>
	);
};

export default memo(NavigationArrow);
