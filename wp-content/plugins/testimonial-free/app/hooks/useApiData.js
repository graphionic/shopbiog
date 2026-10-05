import { useEffect, useState, useMemo } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import demoTestimonials from "../controls/demo-testimonial.json";

// Flatten a MultipleSelect value (option objects or ids) to a comma-separated
// id list for the REST query.
const optionIdsCsv = (items) =>
	Array.isArray(items)
		? items
				.map((i) => (i && typeof i === "object" ? (i.value ?? i.id) : i))
				.filter(Boolean)
				.join(",")
		: "";

const useApiData = (attributes) => {
	const { align, excludesItems, limit, orderBy, order } = attributes;

	// Initial state.
	const [allData, setAllData] = useState({
		posts: [],
		postCount: 0,
		postsStatus: true,
		message: "",
		isDemoTestimonials: false,
	});

	const exclude = optionIdsCsv(excludesItems);

	const queryData = useMemo(
		() => ({
			per_page: limit || 10,
			filterBy: "latest",
			exclude,
			orderby: orderBy || "date",
			order: order || "desc",
		}),
		[limit, exclude, orderBy, order, align]
	);

	// -----------------------------------------
	// Fetch posts only when queryData changes
	// -----------------------------------------

	useEffect(() => {
		let isMounted = true;

		const fetchPosts = async () => {
			setAllData((prev) => ({ ...prev, postsStatus: true }));

			try {
				const queryParams = new URLSearchParams();
				Object.entries(queryData).forEach(([key, value]) => {
					if (value) {
						queryParams.append(key, value);
					}
				});

				const response = await apiFetch({
					path: `/sp-rtp/v2/testimonial?${queryParams.toString()}`,
					method: "GET",
				});
				if (isMounted && response?.success) {
					const fetched = response.data || [];
					// Editor-only fallback: when the site has no testimonials yet,
					// show bundled demo data so blocks preview correctly.
					const isEmpty = fetched.length === 0;
					setAllData({
						posts: isEmpty ? demoTestimonials : fetched,
						postCount: isEmpty ? demoTestimonials.length : response.total || 0,
						postsStatus: false,
						message: "",
						isDemoTestimonials: isEmpty,
					});
				}
			} catch (error) {
				if (isMounted) {
					setAllData({
						posts: [],
						postCount: 0,
						postsStatus: false,
						message: error.message || "Failed to fetch testimonials",
						isDemoTestimonials: false,
					});
				}
			}
		};

		fetchPosts();

		return () => {
			isMounted = false;
		};
	}, [queryData]);

	return { ...allData, queryData };
};

export default useApiData;
