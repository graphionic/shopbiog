export const CloseIcon = ({ fill = "currentColor" }) => (
	<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none" aria-hidden="true">
		<path
			d="M6.36397 7.77819L11.3137 12.7279L12.7279 11.3137L7.77819 6.36397L12.7279 1.41423L11.3137 1.1603e-05L6.36397 4.94976L1.41421 0L0 1.41421L4.94976 6.36397L1.18415e-05 11.3137L1.41423 12.7279L6.36397 7.77819Z"
			fill={fill}
		/>
	</svg>
);

export const BackArrowIcon = () => (
	<svg width={14} height={14} viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M1 5h10a2 2 0 0 1 2 2v5"
			stroke="#757575"
			strokeWidth={1.4}
			strokeLinecap="round"
			strokeLinejoin="round"
		/>
		<path d="M4 8 1 5l3-3" stroke="#757575" strokeWidth={1.4} strokeLinecap="round" strokeLinejoin="round" />
	</svg>
);

/* Preview-width switcher icons — outline style, drawn at 24px in the 34px chip. */
export const DesktopIcon = () => (
	<svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<g clipPath="url(#DesktopIcona)">
			<path
				d="M22 1H2C.897 1 0 1.897 0 3v14c0 1.103.897 2 2 2h7.002c-.02.798-.173 2.074-.745 2.676A.98.98 0 0 1 7.5 22a.5.5 0 1 0 0 1h9a.5.5 0 1 0 0-1 .98.98 0 0 1-.756-.323c-.57-.6-.724-1.878-.746-2.677H22c1.103 0 2-.897 2-2V3c0-1.103-.898-2-2-2M12 18.003a1.004 1.004 0 0 1 0-2.006 1.004 1.004 0 0 1 0 2.006M2 15V3h20v12z"
				fill="currentColor"
			/>
		</g>
		<defs>
			<clipPath id="DesktopIcona">
				<path fill="#fff" d="M0 0h24v24H0z" />
			</clipPath>
		</defs>
	</svg>
);

export const TabletIcon = () => (
	<svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M19.152 0H4.85c-.902 0-1.627.73-1.627 1.63v20.74c0 .899.725 1.63 1.627 1.63h14.302a1.63 1.63 0 0 0 1.628-1.63V1.63C20.782.73 20.052 0 19.152 0m-7.15 23.39A.967.967 0 1 1 12 21.454a.967.967 0 0 1 .002 1.934m7.131-2.785H4.87V1.959h14.263z"
			fill="currentColor"
		/>
	</svg>
);

export const MobileIcon = () => (
	<svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M17.048 0H6.95c-.967 0-1.747.784-1.747 1.748v20.505c0 .964.78 1.747 1.747 1.747h10.098c.964 0 1.746-.783 1.746-1.747V1.749C18.794.784 18.012 0 17.047 0M9.506 1.062h4.985c.126 0 .228.188.228.42 0 .233-.102.422-.228.422H9.507c-.127 0-.227-.189-.227-.422s.1-.42.227-.42m2.492 21.213a1.115 1.115 0 1 1 0-2.23 1.115 1.115 0 0 1 0 2.23m5.487-3.822H6.512V2.95h10.974z"
			fill="currentColor"
		/>
	</svg>
);

export const GridTwoColIcon = () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		width="24"
		height="24"
		viewBox="0 0 24 24"
		role="img"
		aria-label="2x2 grid filled"
	>
		<rect x="3" y="3" width="8" height="8" rx="1" fill="currentColor" />
		<rect x="13" y="3" width="8" height="8" rx="1" fill="currentColor" />
		<rect x="3" y="13" width="8" height="8" rx="1" fill="currentColor" />
		<rect x="13" y="13" width="8" height="8" rx="1" fill="currentColor" />
	</svg>
);

export const GridThreeColIcon = () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		width="24"
		height="24"
		viewBox="0 0 24 24"
		role="img"
		aria-label="3x3 grid filled"
	>
		<rect x="2" y="2" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="9" y="2" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="16" y="2" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="2" y="9" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="9" y="9" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="16" y="9" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="2" y="16" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="9" y="16" width="6" height="6" rx="1" fill="currentColor" />
		<rect x="16" y="16" width="6" height="6" rx="1" fill="currentColor" />
	</svg>
);

export const HeartIcon = () => (
	<svg
		width="34"
		height="24"
		viewBox="0 0 20 20"
		fill="none"
		stroke="currentColor"
		strokeWidth="2"
		xmlns="http://www.w3.org/2000/svg"
	>
		<path
			d="M10 17.5l-1.45-1.32C4.4 12.36 2 10.28 2 7.5 2 5.42 3.42 4 5.5 4c1.54 0 3.04 1 3.57 2.36h1.87C11.46 5 12.96 4 14.5 4 16.58 4 18 5.42 18 7.5c0 2.78-2.4 4.86-6.55 8.68L10 17.5z"
			fill="none"
		/>
	</svg>
);

export const HeartIconFull = () => (
	<svg xmlns="http://www.w3.org/2000/svg" width="23" height="21" viewBox="0 0 23 21" fill="currentColor">
		<path
			d="M20.2913 2.61183C19.7805 2.10083 19.1741 1.69547 18.5066 1.41891C17.8392 1.14235 17.1238 1 16.4013 1C15.6788 1 14.9634 1.14235 14.2959 1.41891C13.6285 1.69547 13.022 2.10083 12.5113 2.61183L11.4513 3.67183L10.3913 2.61183C9.3596 1.58013 7.96032 1.00053 6.50129 1.00053C5.04226 1.00053 3.64298 1.58013 2.61129 2.61183C1.5796 3.64352 1 5.04279 1 6.50183C1 7.96086 1.5796 9.36013 2.61129 10.3918L11.4513 19.2318L20.2913 10.3918C20.8023 9.88107 21.2076 9.27464 21.4842 8.60718C21.7608 7.93972 21.9031 7.22431 21.9031 6.50183C21.9031 5.77934 21.7608 5.06393 21.4842 4.39647C21.2076 3.72901 20.8023 3.12258 20.2913 2.61183Z"
			fill="currentColor"
			stroke="currentColor"
			strokeWidth="2"
			strokeLinecap="round"
			strokeLinejoin="round"
		/>
	</svg>
);

export const DownloadIcon = () => (
	<svg viewBox="0 0 16 16" width="16" height="16" fill="currentColor" aria-hidden="true">
		<path d="M1.25 12.667V10C1.25 9.58579 1.58579 9.25 2 9.25C2.41421 9.25 2.75 9.58579 2.75 10V12.667C2.75009 12.8216 2.81158 12.9698 2.9209 13.0791C3.03022 13.1884 3.17842 13.2499 3.33301 13.25H12.667C12.8216 13.2499 12.9698 13.1884 13.0791 13.0791C13.1884 12.9698 13.2499 12.8216 13.25 12.667V10C13.25 9.58579 13.5858 9.25 14 9.25C14.4142 9.25 14.75 9.58579 14.75 10V12.667C14.7499 13.2194 14.5303 13.749 14.1396 14.1396C13.749 14.5303 13.2194 14.7499 12.667 14.75H3.33301C2.78059 14.7499 2.25098 14.5303 1.86035 14.1396C1.46973 13.749 1.25009 13.2194 1.25 12.667Z" />
		<path d="M10.8037 6.13764C11.0966 5.84491 11.5714 5.8448 11.8642 6.13764C12.1569 6.43049 12.1569 6.90533 11.8642 7.19819L8.53022 10.5312C8.23732 10.8241 7.76256 10.8241 7.46967 10.5312L4.13666 7.19819C3.84377 6.90529 3.84377 6.43053 4.13666 6.13764C4.42956 5.84475 4.90432 5.84475 5.19721 6.13764L7.99994 8.94037L10.8037 6.13764Z" />
		<path d="M7.25 10V2C7.25 1.58579 7.58579 1.25 8 1.25C8.41421 1.25 8.75 1.58579 8.75 2V10C8.75 10.4142 8.41421 10.75 8 10.75C7.58579 10.75 7.25 10.4142 7.25 10Z" />
	</svg>
);

export const RealTestimonialLogo = ({ fill = "var(--sp-real-primary-color)" }) => (
	<svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M22.9 2.227h-9.59a1.105 1.105 0 0 0-1.1 1.1v4.926a1.105 1.105 0 0 0 1.1 1.1h.839v1.94a.36.36 0 0 0 .21.314c.052 0 .104.052.157.052a.27.27 0 0 0 .21-.104l2.567-2.201H22.9a1.106 1.106 0 0 0 1.1-1.1V3.326a1.143 1.143 0 0 0-1.1-1.1m-6.865 3.406-.524.524.105.681a.2.2 0 0 1-.052.158h-.158l-.628-.315-.577.262h-.157a.2.2 0 0 1-.052-.157l.104-.681-.524-.524a.2.2 0 0 1-.052-.157c.052 0 .105-.053.157-.053l.734-.105.314-.628c0-.053.053-.053.105-.053q.062.01.105.053l.314.628.734.105a.115.115 0 0 1 .105.105.2.2 0 0 1-.053.157m3.25 0-.525.524.105.681a.2.2 0 0 1-.052.158h-.158l-.628-.315-.577.262h-.157a.2.2 0 0 1-.052-.157l.104-.681-.524-.524a.2.2 0 0 1-.052-.157.116.116 0 0 1 .105-.105l.733-.105.315-.629c0-.052.052-.052.105-.052q.061.009.104.052l.315.629.786.157a.115.115 0 0 1 .105.105.2.2 0 0 1-.053.157m3.248 0-.524.524.105.681a.2.2 0 0 1-.053.158h-.157l-.628-.315-.577.262h-.157a.2.2 0 0 1-.053-.157l.105-.681-.524-.524a.2.2 0 0 1-.052-.157.116.116 0 0 1 .105-.105l.733-.105.315-.629c0-.052.052-.052.105-.052q.061.009.104.052l.315.629.786.157a.115.115 0 0 1 .104.105.2.2 0 0 1-.052.157M7.65 5.633c-2.358 0-3.668 1.205-3.615 3.564.052 3.196 1.415 5.135 3.615 5.135 0 0 3.616.157 3.616-5.135 0-2.359-1.205-3.564-3.616-3.564m6.394 11.109-3.616-1.415-.838-.733a.57.57 0 0 0-.787.052L7.651 15.8l-1.206-1.153a.63.63 0 0 0-.786-.052l-.786.733-3.615 1.468C.105 17.266.105 21.406 0 21.773h15.354c-.105-.42-.105-4.56-1.31-5.03"
			fill={fill}
		/>
	</svg>
);

// Pro badge — used by the editor's Pro-block teasers, the Pro inspector
// controls and the Lite vs Pro comparison page (not by the pattern library).
export const ProBadgeIcon = ({ color = "#3d9e56", fill = "" }) => (
	<svg width={16} height={16} viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M10.384 5.882c.18.329.478.572.842.648.322.068.652-.007.922-.203l.112-.092 1.396-1.279 1.159-1.062-.81 6.437H2.124l-.812-6.437 2.556 2.341c.288.264.666.373 1.035.295a1.26 1.26 0 0 0 .841-.648l2.32-4.254z"
			stroke={color}
			strokeWidth={1.185}
			fill={fill}
		/>
		<path
			d="M13.852 14.873H2.274c-.373 0-.674-.369-.674-.824V12.24h12.925v1.81c0 .454-.301.823-.674.823"
			fill={color}
		/>
	</svg>
);
