window.addEventListener("load", function () {
	const url = new URL(window.location.href);
	const hasBlockInserter = url.searchParams.has("rtpblock_inserter");
	const hasPatternLibrary = url.searchParams.has("rtp_pattern_library");

	if (!hasBlockInserter && !hasPatternLibrary) {
		return;
	}

	function tryScroll() {
		const headings = document.querySelectorAll(".block-editor-inserter__panel-title");
		const realTestimonialsHeading = Array.from(headings).find((h) => h.textContent.trim() === "Real Testimonials");
		if (realTestimonialsHeading) {
			realTestimonialsHeading.scrollIntoView({
				behavior: "smooth",
				block: "start",
				inline: "nearest",
			});
			return true;
		}
		return false;
	}

	function tryClick() {
		if (!wp.data.dispatch) {
			return false;
		}
		const { dispatch } = wp.data;
		if (dispatch("core/editor")) {
			dispatch("core/editor").setIsInserterOpened(true);
		} else if (dispatch("core/edit-post")) {
			dispatch("core/edit-post").setIsInserterOpened(true);
		}
		// clear url.
		url.searchParams.delete("rtpblock_inserter");
		history.replaceState(null, "", url.toString());

		// Try to scroll every 100ms for up to 3 seconds
		let scrollAttempts = 0;
		const scrollInterval = setInterval(() => {
			scrollAttempts++;
			if (tryScroll() || scrollAttempts > 30) {
				clearInterval(scrollInterval);
			}
		}, 100);

		return true;
	}

	function tryClickPatternButton() {
		const button = document.getElementById("sp-real-patterns-library-modal-button");
		if (button) {
			button.click();
			// clear url.
			url.searchParams.delete("rtp_pattern_library");
			history.replaceState(null, "", url.toString());
			return true;
		}
		return false;
	}

	// Handle pattern library
	if (hasPatternLibrary) {
		let attempts = 0;
		const interval = setInterval(() => {
			attempts++;
			if (tryClickPatternButton() || attempts > 30) {
				clearInterval(interval);
			}
		}, 100);
		return;
	}

	// Handle block inserter
	// Try every 100ms for up to 2 seconds
	let attempts = 0;
	const interval = setInterval(() => {
		attempts++;
		if (tryClick() || attempts > 20) {
			clearInterval(interval);
		}
	}, 100);
});
