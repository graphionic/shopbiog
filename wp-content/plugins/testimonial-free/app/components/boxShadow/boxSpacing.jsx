/**
 * Labeled box-shadow numeric inputs (X / Y / Blur / Spread) rendered as one connected group.
 *
 * Operates on the shared `box_shadow` attribute shape produced by `Utils::box_shadow`:
 * `{ value: { top, right, bottom, left }, unit, color, isActive }`. The four sides map to
 * the four box-shadow lengths via `labelItem` (top → X, right → Y, bottom → Blur, left → Spread)
 * and are consumed by `boxShadowCss` in `@testimonial/controls`. Unit switch + reset live in the
 * parent `BoxShadow` header.
 *
 * @param {Object}   props
 * @param {Object}   props.attributes    Current shadow attribute object.
 * @param {string}   props.attributesKey Attribute key to write back.
 * @param {Function} props.setAttributes Block setAttributes (used when `onChange` is absent).
 * @param {Function} [props.onChange]    Optional `(key, value)` writer; overrides setAttributes.
 */
export const BoxSpacing = ({ attributes, attributesKey, setAttributes, onChange = false }) => {
	const sides = ["top", "right", "bottom", "left"];
	const value = attributes?.value || {};

	const writeShadow = (next) => {
		if (onChange) {
			onChange(attributesKey, next);
		} else {
			setAttributes({ [attributesKey]: next });
		}
	};

	const setSpacingData = (raw, side) => {
		const parsed = raw === "" ? "" : parseInt(raw, 10);
		writeShadow({
			...attributes,
			value: {
				...value,
				[side]: Number.isNaN(parsed) ? "" : parsed,
			},
		});
	};

	return (
		<div className="sp-real-bs-spacing sp-real-component-mb">
			<div className="sp-real-bs-spacing-fields sp-d-grid sp-grid-cols-4">
				{sides.map((side, i) => (
					<div key={side} className="sp-real-bs-field sp-d-flex sp-flex-col sp-align-center">
						<input
							id={`sp-real-bs-${side}-${i}`}
							type="number"
							value={value?.[side] ?? ""}
							onChange={(e) => setSpacingData(e.target.value, side)}
						/>
					</div>
				))}
			</div>
			<div className="sp-real-custom-bs-labels sp-d-grid sp-grid-cols-4">
				{["X Offset", "Y Offset", "Blur", "Spread"].map((side, i) => (
					<label key={i} htmlFor={`sp-real-bs-${side}-${i}`}>
						{side}
					</label>
				))}
			</div>
		</div>
	);
};
