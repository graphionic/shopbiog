/* eslint-disable no-nested-ternary */
import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { Spinner } from "@wordpress/components";
import { useSelect } from "@wordpress/data";
import { MultipleSelect } from "@testimonial/components";
import toast from "react-hot-toast";
import { STORE_NAME } from "../../store";

const exportTypeOptions = [
	{ label: __("All Testimonials", "testimonial-free"), value: "all_testimonial" },
	{ label: __("All Testimonial Views (Shortcodes)", "testimonial-free"), value: "all_spt_shortcodes" },
	{ label: __("Selected Testimonial Views (Shortcodes)", "testimonial-free"), value: "selected_spt_shortcodes" },
];

const exportFileTypeOptions = [
	{ label: __("JSON File", "testimonial-free"), value: "json_file" },
	{ label: __("CSV File", "testimonial-free"), value: "csv_file" },
];

// Where each imported post type lands once the import finishes. `import()` returns
// the post type it wrote, so the user is dropped on that list table (classic Tools
// behaviour). Anything unrecognised falls back to the Views table.
const IMPORT_REDIRECT_POST_TYPES = ["spt_testimonial", "spt_testimonial_form", "spt_shortcodes"];
const IMPORT_REDIRECT_FALLBACK = "spt_shortcodes";
// Keep the success toast readable before the page navigates away.
const IMPORT_REDIRECT_DELAY = 1500;

// CSV column order — mirrors the classic Tools export (testimonials only).
const CSV_HEADER =
	"version,date,all_testimonial,title,original_id,category,content,image,_edit_last,_edit_lock,_thumbnail_id,company_logo,name,email,designation,company_name,location,country,phone,website,video_url,rating,extra_fields,social_profiles";

const csvCell = (value) => {
	if (value === undefined || value === null) {
		return "";
	}
	return JSON.stringify(value).replace(/"/g, "'").replace(/,/g, "|");
};

// Build the classic CSV string from the exported testimonials payload.
const buildTestimonialsCsv = (payload) => {
	const rows = (payload?.shortcode || []).map((item) => {
		const meta = item.meta || {};
		const opts = meta.sp_tpro_meta_options || {};
		return [
			payload?.metadata?.version || "",
			payload?.metadata?.date || "",
			item.all_testimonial || "",
			JSON.stringify(item.title ?? "") || "",
			item.original_id || "",
			item.category ? csvCell(item.category) : "",
			item.content ? JSON.stringify(item.content) : "",
			item.image || "",
			meta._edit_last || "",
			meta._edit_lock || "",
			meta._thumbnail_id || "",
			opts.tpro_company_logo ? csvCell(opts.tpro_company_logo) : "",
			opts.tpro_name || "",
			opts.tpro_email || "",
			JSON.stringify(opts.tpro_designation ?? "") || "",
			JSON.stringify(opts.tpro_company_name ?? "") || "",
			JSON.stringify(opts.tpro_location ?? "") || "",
			JSON.stringify(opts.tpro_country ?? "") || "",
			JSON.stringify(opts.tpro_phone ?? "") || "",
			JSON.stringify(opts.tpro_website ?? "") || "",
			JSON.stringify(opts.tpro_video_url ?? "") || "",
			opts.tpro_rating || "",
			opts.testimonial_extra_fields ? csvCell(opts.testimonial_extra_fields) : "",
			opts.tpro_social_profiles ? csvCell(opts.tpro_social_profiles) : "",
		].join(",");
	});
	return `${CSV_HEADER}\n${rows.join("\n")}\n`;
};

// Parse a CSV string into a 2D array; handles quoted fields and escaped quotes.
const csvToArray = (strData, strDelimiter = ",") => {
	const objPattern = new RegExp(
		`(\\${strDelimiter}|\\r?\\n|\\r|^)(?:"([^"]*(?:""[^"]*)*)"|([^"\\${strDelimiter}\\r\\n]*))`,
		"gi"
	);
	const arrData = [[]];
	let arrMatches = objPattern.exec(strData);
	while (arrMatches) {
		const strMatchedDelimiter = arrMatches[1];
		if (strMatchedDelimiter.length && strMatchedDelimiter !== strDelimiter) {
			arrData.push([]);
		}
		const strMatchedValue = arrMatches[2] ? arrMatches[2].replace(/""/g, '"') : arrMatches[3];
		arrData[arrData.length - 1].push(strMatchedValue);
		arrMatches = objPattern.exec(strData);
	}
	return arrData;
};

// CSV string → array of row objects keyed by the header row.
const csvToObjects = (csv) => {
	const rows = csvToArray(csv);
	const objects = [];
	// Stop at length - 1 to skip the trailing empty row from the final newline.
	for (let i = 1; i < rows.length - 1; i++) {
		const obj = {};
		for (let k = 0; k < rows[0].length && k < rows[i].length; k++) {
			obj[rows[0][k]] = rows[i][k];
		}
		objects.push(obj);
	}
	return objects;
};

// Parse a JSON-ish CSV cell; returns "" when it is not valid JSON.
const safeJsonParse = (data) => {
	try {
		return JSON.parse(data);
	} catch (error) {
		return "";
	}
};

// Unpack a packed CSV cell (commas → '|', quotes → "'") back to JSON.
const unpackCsvCell = (value) => safeJsonParse(value.replace(/\|/g, ",").replace(/'/g, '"'));

// CSV row objects → the { shortcode:[...] } structure the importer expects.
const csvRowsToShortcodes = (rows) =>
	rows.map((data) => ({
		title: data.title || "",
		original_id: data.original_id || "",
		content: data.content || "",
		image: data.image || "",
		category: data.category ? unpackCsvCell(data.category) : "",
		all_testimonial: data.all_testimonial || "",
		spt_post: "spt_testimonial",
		gallery_img_id: data.gallery_img_id ? safeJsonParse(data.gallery_img_id) : "",
		meta: {
			_edit_last: data._edit_last ?? "",
			_edit_lock: data._edit_lock ?? "",
			_thumbnail_id: data._thumbnail_id ?? "",
			sp_tpro_meta_options: {
				tpro_company_logo: data.company_logo ? unpackCsvCell(data.company_logo) : "",
				tpro_website: data.website ?? "",
				tpro_email: data.email ?? "",
				tpro_name: data.name ?? "",
				tpro_designation: data.designation ?? "",
				tpro_company_name: data.company_name ?? "",
				tpro_location: data.location ?? "",
				tpro_country: data.country ?? "",
				tpro_phone: data.phone ?? "",
				tpro_video_url: data.video_url ?? "",
				tpro_rating: data.rating ?? "",
				testimonial_extra_fields: data.extra_fields ? unpackCsvCell(data.extra_fields) : "",
				tpro_social_profiles: data.social_profiles ? unpackCsvCell(data.social_profiles) : "",
				sptp_mobile: data.mobile ?? "",
			},
		},
	}));

const Tools = () => {
	const [exportType, setExportType] = useState("all_testimonial");
	const [exportFileType, setExportFileType] = useState("json_file");
	const [selectedViews, setSelectedViews] = useState([]);
	const [importFile, setImportFile] = useState(null);
	const [isExporting, setIsExporting] = useState(false);
	const [isImporting, setIsImporting] = useState(false);
	const [isDragging, setIsDragging] = useState(false);

	// null = not fetched yet, [] = fetched (possibly empty).
	const [viewsList, setViewsList] = useState(null);

	// Bumped after export to remount the (uncontrolled) MultipleSelect and clear it.
	const [resetKey, setResetKey] = useState(0);

	const ajaxUrl = sp_real_admin_dashboard_localize?.ajax_url;
	const nonce = sp_real_admin_dashboard_localize?.import_export_nonce;
	// Trailing-slashed wp-admin URL; the dashboard already lives under wp-admin, so a
	// bare relative path still resolves if the REST payload has not landed yet.
	const { adminUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);

	const isSelectedViews = "selected_spt_shortcodes" === exportType;
	const isTestimonials = "all_testimonial" === exportType;
	// CSV is only offered for the All Testimonials export.
	const fileType = isTestimonials ? exportFileType : "json_file";

	// Drag and drop handlers
	const handleDragOver = (e) => {
		e.preventDefault();
		e.stopPropagation();
		setIsDragging(true);
	};

	const handleDragLeave = (e) => {
		e.preventDefault();
		e.stopPropagation();
		setIsDragging(false);
	};

	const handleDrop = (e) => {
		e.preventDefault();
		e.stopPropagation();
		setIsDragging(false);
		const file = e.dataTransfer.files[0];
		if (file && (file.name.endsWith(".json") || file.name.endsWith(".csv"))) {
			setImportFile(file);
		}
	};

	// Fetch the post list only when its "Selected …" option is picked.
	useEffect(() => {
		const fetchPosts = async (type, setList) => {
			try {
				const data = await apiFetch({ path: `/sp-rtp/v2/export-posts?type=${type}` });
				setList(Array.isArray(data) ? data : []);
			} catch (error) {
				setList([]);
			}
		};

		if (isSelectedViews && viewsList === null) {
			fetchPosts("spt_shortcodes", setViewsList);
		}
	}, [isSelectedViews, viewsList]);

	const handleExport = async () => {
		// Status cleared

		if (isSelectedViews && selectedViews.length === 0) {
			toast.error(__("Choose at least one testimonial view.", "testimonial-free"));
			return;
		}

		setIsExporting(true);
		try {
			const formData = new FormData();
			formData.append("action", "spt_export_shortcodes");
			formData.append("nonce", nonce);
			formData.append("file_type", fileType);

			if (isSelectedViews) {
				selectedViews.forEach((opt) => formData.append("lcp_ids[]", opt.value));
				formData.append("text_ids", "select_shortcodes");
			} else {
				formData.append("lcp_ids", exportType);
			}

			const response = await fetch(ajaxUrl, { method: "POST", body: formData });
			const data = await response.json();

			const isCsv = "csv_file" === fileType;
			const fileBody = isCsv ? buildTestimonialsCsv(data) : JSON.stringify(data, null, 2);
			const blob = new Blob([fileBody], { type: isCsv ? "text/csv" : "application/json" });
			const url = URL.createObjectURL(blob);
			const link = document.createElement("a");
			link.href = url;
			link.download = `testimonial-pro-export-${Date.now()}.${isCsv ? "csv" : "json"}`;
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
			URL.revokeObjectURL(url);
			// Clear the selected views after a successful export.
			if (isSelectedViews) {
				setSelectedViews([]);
				setResetKey((prev) => prev + 1);
			}
			toast.success(__("Export downloaded.", "testimonial-free"));
		} catch (error) {
			toast.error(error.message);
		}
		setIsExporting(false);
	};

	const handleImport = async () => {
		if (!importFile) {
			return;
		}
		setIsImporting(true);
		// Status cleared
		try {
			const fileText = await importFile.text();
			const isCsv = importFile.name.toLowerCase().endsWith(".csv");

			let payload;
			let importFileType;
			if (isCsv) {
				const rows = csvToObjects(fileText);
				if (rows.length === 0) {
					toast.error(__("No rows found in CSV.", "testimonial-free"));
					setIsImporting(false);
					return;
				}
				const props = {
					shortcode: csvRowsToShortcodes(rows),
					metadata: { version: rows[0].version || "", date: rows[0].date || "" },
				};
				// Double-encode: the server json_decodes the payload twice.
				payload = JSON.stringify(JSON.stringify(props));
				importFileType = "csv";
			} else {
				payload = JSON.stringify(fileText);
				importFileType = "json";
			}

			const formData = new FormData();
			formData.append("action", "spt_import_shortcodes");
			formData.append("nonce", nonce);
			formData.append("file_type", importFileType);
			formData.append("shortcode", payload);

			const response = await fetch(ajaxUrl, { method: "POST", body: formData });
			const data = await response.json();
			if (data?.success) {
				toast.success(__("Import completed.", "testimonial-free"));
				setImportFile(null);
				// `Import_Export::import()` answers with the post type it wrote, so land the
				// user on that list table — testimonials, forms, or views.
				const importedType = IMPORT_REDIRECT_POST_TYPES.includes(data?.data)
					? data.data
					: IMPORT_REDIRECT_FALLBACK;
				setTimeout(() => {
					window.location.replace(`${adminUrl || ""}edit.php?post_type=${importedType}`);
				}, IMPORT_REDIRECT_DELAY);
				// Keep the spinner up: the page is about to navigate away.
				return;
			}
			toast.error(data?.data?.message || __("Import failed.", "testimonial-free"));
		} catch (error) {
			toast.error(error.message);
		}
		setIsImporting(false);
	};

	return (
		<div className="sp-real-dashboard-tools-settings sp-d-flex sp-flex-col">
			<div className="sp-real-settings-option sp-d-flex sp-align-start sp-justify-between">
				<span className="sp-real-component-title sp-real-tools-title">{__("Export", "testimonial-free")}</span>
				<div className="sp-real-tools-control sp-d-flex sp-flex-col sp-gap-12px">
					<span className="sp-real-component-title sp-real-tools-subtitle">
						{__("Choose What To Export", "testimonial-free")}
					</span>
					<div className="sp-real-tools-radio sp-d-flex sp-flex-col sp-gap-10px">
						{exportTypeOptions.map((opt) => {
							const inputId = `sp-real-export-type-${opt.value}`;
							return (
								<label
									key={opt.value}
									htmlFor={inputId}
									className="sp-real-tools-radio-option sp-d-flex sp-align-center sp-gap-8px sp-cursor-pointer"
								>
									<input
										id={inputId}
										type="radio"
										name="sp-real-export-type"
										value={opt.value}
										checked={exportType === opt.value}
										onChange={() => setExportType(opt.value)}
									/>
									<span>{opt.label}</span>
								</label>
							);
						})}
					</div>

					{isSelectedViews && (
						<div className="sp-real-tools-select-list">
							{viewsList === null ? (
								<Spinner />
							) : viewsList.length === 0 ? (
								<span className="sp-real-tools-empty">
									{__("No testimonial views found.", "testimonial-free")}
								</span>
							) : (
								<MultipleSelect
									key={`views-${resetKey}`}
									label={__("Choose testimonial view(s)", "testimonial-free")}
									attributes={selectedViews}
									items={viewsList}
									onChange={(data) => setSelectedViews(data || [])}
								/>
							)}
						</div>
					)}

					{isTestimonials && (
						<>
							<span className="sp-real-component-title sp-real-tools-subtitle">
								{__("Export File Type", "testimonial-free")}
							</span>
							<div className="sp-real-tools-radio sp-d-flex sp-flex-col sp-gap-10px">
								{exportFileTypeOptions.map((opt) => {
									const inputId = `sp-real-export-file-type-${opt.value}`;
									return (
										<label
											key={opt.value}
											htmlFor={inputId}
											className="sp-real-tools-radio-option sp-d-flex sp-align-center sp-gap-8px sp-cursor-pointer"
										>
											<input
												id={inputId}
												type="radio"
												name="sp-real-export-file-type"
												value={opt.value}
												checked={exportFileType === opt.value}
												onChange={() => setExportFileType(opt.value)}
											/>
											<span>{opt.label}</span>
										</label>
									);
								})}
							</div>
						</>
					)}

					<button
						className="sp-real-tools-action-btn primary sp-cursor-pointer"
						onClick={handleExport}
						disabled={isExporting}
					>
						{isExporting ? <Spinner /> : __("Export File", "testimonial-free")}
					</button>
				</div>
			</div>
			<div className="sp-real-settings-separator"></div>
			<div className="sp-real-settings-option sp-d-flex sp-align-start sp-justify-between">
				<span className="sp-real-component-title sp-real-tools-title">{__("Import", "testimonial-free")}</span>
				<div className="sp-real-tools-control sp-d-flex sp-flex-col sp-gap-12px">
					<span className="sp-real-component-title sp-real-tools-subtitle">
						{__("Import JSON/CSV File To Upload", "testimonial-free")}
					</span>
					<div
						className={`sp-real-tools-file sp-d-flex sp-align-center sp-gap-10px${isDragging ? " dragging" : ""}`}
						onDragOver={handleDragOver}
						onDragLeave={handleDragLeave}
						onDrop={handleDrop}
					>
						<label htmlFor="sp-real-import-file" className="sp-real-tools-file-label sp-cursor-pointer">
							{__("Choose File", "testimonial-free")}
						</label>
						<input
							id="sp-real-import-file"
							type="file"
							accept=".json,.csv"
							onChange={(e) => setImportFile(e.target.files?.[0] || null)}
							style={{ display: "none" }}
						/>
						<span className="sp-real-tools-file-name">
							{importFile?.name || __("No File Choosen", "testimonial-free")}
						</span>
					</div>
					<button
						className={`sp-real-tools-action-btn secondary sp-cursor-pointer${importFile ? " active" : " disabled"}`}
						onClick={handleImport}
						disabled={!importFile || isImporting}
					>
						{isImporting ? <Spinner /> : __("Apply Input", "testimonial-free")}
					</button>
				</div>
			</div>
		</div>
	);
};

export default Tools;
