const ChevronIcon = () => (
	<svg
		width="10"
		height="10"
		viewBox="0 0 10 10"
		fill="currentColor"
		xmlns="http://www.w3.org/2000/svg"
		aria-hidden="true"
	>
		<path d="M3.7 1 2.3 2.4 4.9 5 2.3 7.6 3.7 9l4-4z" />
	</svg>
);

const Pagination = ({ attributes }) => {
	const {
		paginationButtonType,
		loadMoreLabel,
		paginationAlignment,
		paginationNumberType,
		paginationPrevLabel,
		paginationNextLabel,
		paginationShorten,
	} = attributes;

	const isNumber = ["number", "number-arrow", "number-prev-next-arrow"].includes(paginationNumberType);
	const hasArrows = ["number-arrow", "number-prev-next-arrow", "prev-next"].includes(paginationNumberType);
	const showLabels = ["number-prev-next-arrow", "prev-next"].includes(paginationNumberType);

	return (
		<div className={`sp-real-pagination-wrapper sp-d-flex sp-justify-${paginationAlignment}`}>
			{paginationButtonType === "load-more" && (
				<div className="sp-real-load-more-button">
					<a href="#" className="sp-real-pagination-item" onClick={(e) => e.preventDefault()}>
						{loadMoreLabel}
					</a>
				</div>
			)}

			{paginationButtonType === "number" && (
				<div className="sp-real-pagination-buttons sp-d-flex">
					{hasArrows && (
						<a
							href="#"
							className="sp-real-pagination-item next-prev-button prev-button sp-d-flex sp-align-center sp-gap-2px sp-d-disabled"
							onClick={(e) => e.preventDefault()}
						>
							<span className="sp-d-block sp-real-pagination-prev-icon">
								<ChevronIcon />
							</span>
							{showLabels && paginationPrevLabel}
						</a>
					)}

					{isNumber && (
						<>
							<a href="#" className="sp-real-pagination-item current" onClick={(e) => e.preventDefault()}>
								1
							</a>
							<a href="#" className="sp-real-pagination-item" onClick={(e) => e.preventDefault()}>
								2
							</a>
							<a href="#" className="sp-real-pagination-item" onClick={(e) => e.preventDefault()}>
								3
							</a>
							{paginationShorten ? (
								<a
									href="#"
									className="sp-real-pagination-item sp-real-pagination-dots"
									onClick={(e) => e.preventDefault()}
								>
									...
								</a>
							) : (
								<a href="#" className="sp-real-pagination-item" onClick={(e) => e.preventDefault()}>
									4
								</a>
							)}
							<a href="#" className="sp-real-pagination-item" onClick={(e) => e.preventDefault()}>
								5
							</a>
						</>
					)}

					{hasArrows && (
						<a
							href="#"
							className="sp-real-pagination-item next-prev-button next-button sp-d-flex sp-align-center sp-gap-2px"
							onClick={(e) => e.preventDefault()}
						>
							{showLabels && paginationNextLabel}
							<span className="sp-d-block sp-real-pagination-next-icon">
								<ChevronIcon />
							</span>
						</a>
					)}
				</div>
			)}
		</div>
	);
};

export default Pagination;
