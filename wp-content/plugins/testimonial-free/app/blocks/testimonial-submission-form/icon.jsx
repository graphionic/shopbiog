export const SubmissionFormBlockIcon = () => (
	<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path
			d="M21.35 2a.35.35 0 0 0-.35-.35H3a.35.35 0 0 0-.35.35v20c0 .193.157.35.35.35h18a.35.35 0 0 0 .35-.35zm1.3 20A1.65 1.65 0 0 1 21 23.65H3A1.65 1.65 0 0 1 1.35 22V2c0-.911.739-1.65 1.65-1.65h18c.911 0 1.65.739 1.65 1.65z"
			fill="#1e67d8"
		/>
		<path
			d="M5.5 5.5h11v-1h-11zm12 .2a.8.8 0 0 1-.8.8H5.3a.8.8 0 0 1-.8-.8V4.3a.8.8 0 0 1 .8-.8h11.4a.8.8 0 0 1 .8.8zm-12 4h13v-1h-13zm14 .2a.8.8 0 0 1-.8.8H5.3a.8.8 0 0 1-.8-.8V8.5a.8.8 0 0 1 .8-.8h13.4a.8.8 0 0 1 .8.8zm-14 5.5h13v-2.5h-13zm14 .2a.8.8 0 0 1-.8.8H5.3a.8.8 0 0 1-.8-.8v-2.9a.8.8 0 0 1 .8-.8h13.4a.8.8 0 0 1 .8.8zm-14 3.8h5v-.8h-5zm6 .2a.8.8 0 0 1-.8.8H5.3a.8.8 0 0 1-.8-.8v-1.2a.8.8 0 0 1 .8-.8h5.4a.8.8 0 0 1 .8.8z"
			fill="#1e67d8"
		/>
	</svg>
);

export const InputStyleOneIcon = ({ isActive }) => (
	<svg width={118} height={48} viewBox="0 0 118 48" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect x={0.5} y={0.5} width={117} height={47} rx={2.5} fill="#fff" />
		<rect x={0.5} y={0.5} width={117} height={47} rx={2.5} stroke={isActive ? "#1a74e4" : "#718491"} />
		<rect x={10} y={10} width={42} height={5} rx={2.5} fill="#718491" />
		<rect x={17} y={27} width={64} height={4} rx={2} fill="#9ea9b2" />
		<rect x={10.4} y={20.4} width={97.2} height={17.2} rx={1.6} stroke="#8796a1" strokeWidth={0.8} />
		{isActive && (
			<>
				<rect x={99} y={4} width={15} height={15} rx={7.5} fill="#1a74e4" />
				<path d="m102.562 11.605 2.446 3.27h1.047l4.383-6.06-1.017-.69-3.937 4.26-1.937-1.68z" fill="#fff" />
			</>
		)}
	</svg>
);

export const InputStyleTwoIcon = ({ isActive }) => (
	<svg width={118} height={48} viewBox="0 0 118 48" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect x={0.5} y={0.5} width={117} height={47} rx={2.5} fill="#fff" />
		<rect x={0.5} y={0.5} width={117} height={47} rx={2.5} stroke={isActive ? "#1a74e4" : "#718491"} />
		<rect x={9} y={21} width={29} height={5} rx={2.5} fill="#718491" />
		<rect x={54} y={22} width={39} height={4} rx={2} fill="#9ea9b2" />
		<rect x={48.4} y={15.4} width={59.2} height={17.2} rx={1.6} stroke="#8796a1" strokeWidth={0.8} />
		{isActive && (
			<>
				<rect x={99} y={4} width={15} height={15} rx={7.5} fill="#1a74e4" />
				<path d="m102.562 11.605 2.446 3.27h1.047l4.383-6.06-1.017-.69-3.937 4.26-1.937-1.68z" fill="#fff" />
			</>
		)}
	</svg>
);

export const INPUT_STYLE_ITEMS = [
	{ label: "Style One", value: "style-one", Icon: InputStyleOneIcon },
	{ label: "Style Two", value: "style-two", Icon: InputStyleTwoIcon },
];

export const TestimonialSubmissionFormPreviewImage = () => (
	<svg viewBox="0 0 236 158" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect x={0.75} y={0.75} width={234.5} height={156.5} rx={3.25} stroke="#9ea9b2" strokeWidth={1.5} />
		<path
			d="M166.802 18.59c0-.77-.626-1.395-1.399-1.395H70.597c-.772 0-1.399.625-1.399 1.395v121.82c0 .77.626 1.395 1.399 1.395h94.806c.772 0 1.399-.625 1.399-1.395zM168 140.41a2.593 2.593 0 0 1-2.597 2.59H70.597A2.593 2.593 0 0 1 68 140.41V18.59c0-1.43 1.163-2.59 2.597-2.59h94.806A2.593 2.593 0 0 1 168 18.59z"
			fill="#718491"
		/>
		<rect x={76} y={24} width={30} height={3} rx={1.5} fill="#718491" />
		<path
			d="m79.113 112 .984 1.885 2.129.332-1.525 1.491.339 2.083-1.927-.953-1.917.953.329-2.083L76 114.217l2.139-.332zm6.695.105.984 1.885 2.128.332-1.524 1.492.339 2.082-1.927-.953-1.917.953.328-2.082-1.524-1.492 2.138-.332zm6.692 0 .985 1.885 2.128.332-1.525 1.492.339 2.082-1.927-.953-1.916.953.328-2.082-1.525-1.492 2.139-.332zm6.693 0 .985 1.885 2.128.332-1.525 1.492.339 2.082-1.927-.953-1.916.953.328-2.082-1.525-1.492 2.139-.332zm6.694.104.985 1.886 2.128.331-1.525 1.492.339 2.082-1.927-.953-1.916.953.328-2.082-1.525-1.492 2.139-.331z"
			fill="#718491"
		/>
		<rect x={76} y={48} width={30} height={3} rx={1.5} fill="#718491" />
		<rect x={76.5} y={30.5} width={83} height={11} rx={1.5} stroke="#718491" />
		<rect x={76.5} y={54.5} width={83} height={11} rx={1.5} stroke="#718491" />
		<rect x={76} y={72} width={30} height={3} rx={1.5} fill="#718491" />
		<rect x={76.5} y={78.5} width={83} height={23} rx={1.5} stroke="#718491" />
		<mask id="a" fill="#fff">
			<path d="m153.531 101.117 5.607-5.607.701.7-5.607 5.608z" />
		</mask>
		<path
			d="m153.531 101.117-.707-.707-.707.707.707.707zm5.607-5.607.707-.708-.707-.707-.707.707zm.701.7.707.708.707-.708-.707-.707zm-5.607 5.608-.707.707.707.707.707-.707zm-.701-.701.707.707 5.607-5.607-.707-.707-.707-.708-5.607 5.608zm5.607-5.607-.707.707.701.7.707-.707.707-.707-.701-.7zm.701.7-.707-.707-5.607 5.607.707.708.707.707 5.607-5.607zm-5.607 5.608.707-.708-.701-.7-.707.707-.707.707.701.701z"
			fill="#718491"
			mask="url(#a)"
		/>
		<rect x={76} y={123} width={36} height={12} rx={2} fill="#718491" />
		<rect x={84} y={128} width={20} height={2} rx={1} fill="#fff" />
		<rect x={76} y={105} width={50} height={2} rx={1} fill="#9ea9b2" />
	</svg>
);
