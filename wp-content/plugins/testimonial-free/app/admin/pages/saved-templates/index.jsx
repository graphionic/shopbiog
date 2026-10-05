/* eslint-disable no-nested-ternary */
import { __ } from "@wordpress/i18n";
import { useCallback, useEffect, useRef, useState, memo } from "@wordpress/element";
import { Tooltip, Spinner } from "@wordpress/components";
import { useDispatch, resolveSelect, useSelect } from "@wordpress/data";
import { copyText, toastErrorMsg, toastSuccessMsg } from "../../functions";
import { CheckIcon, CopyIcon, DeleteBinIcon, EditPencilIcon, LeftArrow, RightArrow } from "./Icons";
import { STORE_NAME } from "../../store/constants";
import { SavedTemplatesPromo } from "./SavedTemplatesPromo";

const SavedTemplates = () => {
	const [selectBulkValue, setSelectBulkValue] = useState("");
	const [searchValue, setSearchValue] = useState("");
	const [currentPage, setCurrentPage] = useState(1);
	const [allCheck, setAllCheck] = useState(false);
	const [checkId, setCheckId] = useState([]);
	const [shortcodeCopied, setShortcodeCopied] = useState("");
	const timeoutRef = useRef(null);
	const { homeUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);

	// Saved templates from REST API (server-side search + pagination).
	const {
		templates: savedTemplateList = [],
		total_items: totalPostCount = 0,
		classic_count: classicShortcodeCount = 0,
	} = useSelect((select) => select(STORE_NAME).getSaveTemplates(), []);
	const templatesLoading = useSelect((select) => select(STORE_NAME).isTemplatesLoading(), []);

	const { fetchSavedTemplates } = useDispatch(STORE_NAME);

	// Fetch templates on mount and whenever the search term or page changes.
	useEffect(() => {
		fetchSavedTemplates(searchValue, currentPage, 10);
	}, [fetchSavedTemplates, searchValue, currentPage]);

	// Re-fetch the REST-backed list after a mutation (delete / status / duplicate).
	const refreshTemplates = useCallback(() => {
		fetchSavedTemplates(searchValue, currentPage, 10);
	}, [fetchSavedTemplates, searchValue, currentPage]);

	// Dynamic table columns based on whether classic shortcode posts exist.
	const tableCol =
		classicShortcodeCount < 1
			? ["checkBox", "title", "shortcode", "date", "action"]
			: ["checkBox", "title", "editor_type", "shortcode", "date", "action"];

	// Helper to determine post type from item (REST payload carries post_type).
	const getPostType = useCallback(
		(item) =>
			item?.post_type || savedTemplateList?.find((tpl) => tpl.id === item?.id)?.post_type || "sp_real_template",
		[savedTemplateList]
	);

	// Copy Shortcode with correct format based on post type
	const copyShortCodeHandler = useCallback((value) => {
		const postType = getPostType({ id: value });
		const shortcodeKey = postType === "sp_real_template" ? "sp_real_template" : "sp_testimonial";
		const updateValue = `[${shortcodeKey} id="${value}"]`;
		const copied = copyText(updateValue);

		if (copied) {
			setShortcodeCopied(value);
		} else {
			toastErrorMsg(__("Failed to copy shortcode", "testimonial-free"));
		}
	}, []);

	const checkIdHandler = (itemId) => {
		const hasValue = checkId.includes(itemId);
		const updateValue = hasValue ? checkId?.filter((value) => value !== itemId) : [...checkId, itemId];
		setCheckId(updateValue);
		setAllCheck(false);
	};

	// Set Search value with debounce.
	const searchValueHandler = (e) => {
		const searchInputValue = e.target?.value;
		if (timeoutRef.current) {
			clearTimeout(timeoutRef.current);
		}
		timeoutRef.current = setTimeout(() => {
			setSearchValue(searchInputValue);
			setCurrentPage(1);
		}, 100);
	};

	// Core-store mutations (no REST equivalent in the plugin store).
	const { deleteEntityRecord, editEntityRecord, saveEntityRecord, saveEditedEntityRecord } = useDispatch("core");

	// Delete Item.
	const deleteItemHandler = async (itemId = null) => {
		const deleteId = itemId ? [itemId] : checkId;
		if (deleteId?.length < 1) {
			return;
		}
		// eslint-disable-next-line no-alert
		const confirmed = window.confirm("Are you sure you want to delete this saved template?");
		if (confirmed) {
			await Promise.all(
				deleteId.map(async (id) => {
					try {
						const postType = getPostType({ id });
						await deleteEntityRecord("postType", postType, id, { force: true });
					} catch (error) {
						toastErrorMsg(`Error deleting template ID: ${id}: ${error.message}`);
					}
				})
			);
			const updateData = itemId ? checkId?.filter((itemValueId) => itemValueId !== deleteId) : [];
			setCheckId(updateData);
			refreshTemplates();
			toastSuccessMsg("Template deleted successfully.");
		}
	};

	// Update Post Status.
	const updateStatusHandler = async (newStatus = "publish") => {
		const updateId = checkId;
		if (updateId?.length < 1) {
			return;
		}
		await Promise.all(
			updateId?.map(async (id) => {
				try {
					if (!id) {
						return;
					}
					const postType = getPostType({ id });
					// Check if record exists in the store
					const record = await resolveSelect("core").getEntityRecord("postType", postType, id);

					if (!record) {
						return;
					}
					await editEntityRecord("postType", postType, id, { status: newStatus });
					await saveEditedEntityRecord("postType", postType, id);
					setCheckId([]);
					toastSuccessMsg("Template post status updated successfully.");
				} catch (error) {
					toastErrorMsg(`Error while updating template ID: ${id}: ${error.message}`);
				}
			})
		);
		setCheckId([]);
		refreshTemplates();
	};

	// Bulk Action Function.
	const bulkActionHandler = () => {
		if (selectBulkValue === "") {
			return;
		}
		switch (selectBulkValue) {
			case "publish":
				updateStatusHandler("publish");
				break;
			case "draft":
				updateStatusHandler("draft");
				break;
			case "delete":
				deleteItemHandler();
				break;
			default:
				break;
		}
		setCheckId([]);
		setAllCheck(false);
		setSelectBulkValue("");
	};

	const duplicateShortcodeHandler = async (templateId) => {
		try {
			const postType = getPostType({ id: templateId });
			// Get original template
			const original = await resolveSelect("core").getEntityRecord("postType", postType, templateId);

			if (!original) {
				toastErrorMsg("Template not found");
				return;
			}

			// Save new template (no ID = new record)
			await saveEntityRecord("postType", postType, {
				title: `${original.title?.raw || "(No title)"} (Copy)`,
				content: original.content?.raw || "",
				meta: original.meta || {},
				status: "draft",
			});

			// Refresh list
			refreshTemplates();

			toastSuccessMsg("Template duplicated successfully.");
		} catch (error) {
			toastErrorMsg(`Failed to duplicate template: ${error.message}`);
		}
	};

	// Render editor type column
	const renderEditorType = useCallback(
		(item) => {
			const postType = getPostType(item);
			const isBlock = postType === "sp_real_template";
			return (
				<div className="sp-real-editor-type-badge">
					{isBlock ? __("Block Editor", "testimonial-free") : __("Classic", "testimonial-free")}
				</div>
			);
		},
		[getPostType]
	);

	// Get edit URL based on post type
	const getEditUrl = useCallback(
		(item) => {
			const postType = getPostType(item);
			if (postType === "sp_real_template") {
				return `${homeUrl}wp-admin/post.php?post=${item.id}&action=edit`;
			}
			return `${homeUrl}wp-admin/post.php?post=${item.id}&action=edit`;
		},
		[homeUrl, getPostType]
	);

	useEffect(() => {
		if (!shortcodeCopied) {
			return;
		}

		const timer = setTimeout(() => {
			setShortcodeCopied("");
		}, 2000);

		return () => clearTimeout(timer);
	}, [shortcodeCopied]);

	// Cleanup timeoutRef on unmount.
	useEffect(() => {
		return () => {
			if (timeoutRef.current) {
				clearTimeout(timeoutRef.current);
			}
		};
	}, []);

	// Pagination.
	const totalPages = Math.ceil(totalPostCount / 10);
	const pages = Array.from({ length: totalPages }, (_, i) => i + 1);

	return (
		<div className="sp-real-saved-templates-page-wrapper">
			{/* Table Container */}
			<div className="sp-real-saved-templates-page-container">
				<div className="sp-real-saved-template-header sp-d-flex sp-align-center sp-justify-between">
					<div className="sp-real-saved-template-header-left sp-d-flex">
						<select
							name="bulk-action"
							className="sp-real-saved-template-select"
							value={selectBulkValue}
							onChange={(e) => setSelectBulkValue(e.target.value)}
						>
							<option value="">Bulk Action</option>
							<option value={"publish"}>Publish</option>
							<option value={"draft"}>Draft</option>
							<option value={"delete"}>Delete</option>
						</select>
						<button className="sp-real-saved-template-select-apply" onClick={bulkActionHandler}>
							Apply
						</button>
						<input
							name="search-weather-template"
							className="sp-real-saved-template-search-field"
							type="text"
							placeholder="Search..."
							spellCheck="false"
							data-ms-editor="true"
							onChange={searchValueHandler}
						/>
					</div>
					<div className="sp-real-saved-template-header-right">
						<a
							href={`${homeUrl}wp-admin/post-new.php?post_type=sp_real_template&rtpblock_inserter=true`}
							className="sp-real-saved-template-add-new sp-d-flex sp-align-center sp-justify-center sp-gap-8px sp-cursor-pointer"
							rel="noreferrer"
						>
							<i className="dashicons dashicons-plus-alt2"></i>
							Add New Template
						</a>
					</div>
				</div>
				<table className="sp-real-saved-template-content-table sp-d-flex sp-flex-col">
					<thead className="sp-real-saved-template-table-head">
						<tr>
							{tableCol?.map((item, i) => (
								<th key={i} className={`sp-real-saved-template-table-${item}`}>
									{item !== "checkBox" ? (
										item === "editor_type" ? (
											__("Editor Type", "testimonial-free")
										) : "action" === item ? (
											"actions"
										) : (
											item
										)
									) : (
										<input
											type="checkbox"
											onChange={() => {
												setAllCheck((prev) => !prev);
												setCheckId(
													!allCheck ? savedTemplateList?.map((listItem) => listItem.id) : []
												);
											}}
											checked={allCheck}
										/>
									)}
								</th>
							))}
						</tr>
					</thead>
					<tbody
						className="sp-real-saved-template-table-body"
						style={{
							opacity: templatesLoading ? 0.5 : 1,
							// Reserve a full page of rows (10 × 64px) while paginating so short/last
							// pages don't collapse the table height — prevents layout shift.
							minHeight: totalPages > 1 ? "640px" : undefined,
						}}
					>
						{templatesLoading && (!savedTemplateList || savedTemplateList.length === 0) && (
							<tr>
								<td className="sp-real-saved-template-preloader-no-data" colSpan={tableCol.length}>
									<Spinner />
								</td>
							</tr>
						)}
						{!templatesLoading && (!savedTemplateList || savedTemplateList.length === 0) && (
							<tr>
								<td className="sp-real-saved-template-preloader-no-data" colSpan={tableCol.length}>
									<span className="sp-real-saved-template-no-data">
										{__("No saved template found!", "testimonial-free")}
									</span>
								</td>
							</tr>
						)}
						{savedTemplateList?.map((item, i) => {
							const date = new Date(item?.modified);
							const checkBoxValue = allCheck ? true : checkId?.some((itemId) => itemId === item?.id);

							return (
								<tr key={i} className="sp-real-saved-template-table-row">
									<td id={item?.id} className="sp-real-saved-template-table-checkBox">
										<input
											type="checkbox"
											onChange={() => checkIdHandler(item?.id)}
											checked={checkBoxValue}
										/>
									</td>
									<td className="sp-real-saved-template-table-title">
										<a href={getEditUrl(item)} rel="noreferrer noopener">
											<span
												dangerouslySetInnerHTML={{
													__html:
														item?.title?.rendered?.length > 37
															? `${item?.title?.rendered?.slice(0, 37)}...`
															: item?.title?.rendered || "(No Title)",
												}}
												className="sp-real-saved-template-title"
											/>
										</a>
									</td>
									{classicShortcodeCount >= 1 && (
										<td className="sp-real-saved-template-table-editor_type">
											{renderEditorType(item)}
										</td>
									)}
									<td className="sp-real-saved-template-table-shortcode">
										<span
											className="sp-real-saved-template-shortcode-text"
											onClick={() => copyShortCodeHandler(item?.id)}
										>
											{getPostType(item) === "sp_real_template"
												? `[sp_real_template id="${item?.id}"]`
												: `[sp_testimonial id="${item?.id}"]`}
										</span>{" "}
										<span
											className="sp-real-shortcode-copy-tooltip"
											style={{
												opacity: shortcodeCopied === item.id ? 1 : 0,
											}}
										>
											<CheckIcon />
											Copied!
										</span>
										{shortcodeCopied !== item.id && (
											<svg
												width="14"
												height="14"
												viewBox="0 0 14 14"
												fill="none"
												xmlns="http://www.w3.org/2000/svg"
												onClick={() => copyShortCodeHandler(item?.id)}
											>
												<path
													fillRule="evenodd"
													clipRule="evenodd"
													d="M1.35417 1.25H9.47917C9.53667 1.25 9.58333 1.29667 9.58333 1.35417V9.47917C9.58333 9.50679 9.57236 9.53329 9.55282 9.55282C9.53329 9.57236 9.50679 9.58333 9.47917 9.58333H1.35417C1.32654 9.58333 1.30004 9.57236 1.28051 9.55282C1.26097 9.53329 1.25 9.50679 1.25 9.47917V1.35417C1.25 1.29667 1.29667 1.25 1.35417 1.25ZM0 1.35417C0 0.606667 0.606667 0 1.35417 0H9.47917C10.2275 0 10.8333 0.606667 10.8333 1.35417V9.47917C10.8333 10.2275 10.2275 10.8333 9.47917 10.8333H1.35417C0.995019 10.8333 0.650582 10.6907 0.396626 10.4367C0.142671 10.1828 0 9.83831 0 9.47917V1.35417ZM12.0833 11.0675V3.5675H13.3333V11.0675C13.3333 12.3333 12.3083 13.3333 11.0425 13.3333H1.875V12.0833H11.0425C11.6175 12.0833 12.0833 11.6433 12.0833 11.0675Z"
													fill="#757575"
												></path>
											</svg>
										)}
									</td>
									<td className="sp-real-saved-template-table-date">
										<div className="sp-real-saved-template-status">{item?.status}</div>
										<div>{date?.toLocaleString("en-US")}</div>
									</td>
									<td className="sp-real-saved-template-table-action">
										<div className="sp-real-saved-template-table-action-btn">
											<Tooltip text="Edit" delay={300} placement="top">
												<a
													aria-label="Edit"
													href={getEditUrl(item)}
													className="sp-real-saved-template-action sp-action-edit"
													rel="noreferrer"
												>
													<EditPencilIcon />
												</a>
											</Tooltip>
											<Tooltip text="Duplicate" delay={300} placement="top">
												<button
													aria-label="Duplicate"
													className="sp-real-saved-template-action sp-action-copy"
													onClick={() => duplicateShortcodeHandler(item?.id)}
												>
													<CopyIcon />
												</button>
											</Tooltip>
											<Tooltip text="Delete" delay={300} placement="top">
												<button
													aria-label="Delete"
													className="sp-real-saved-template-action sp-action-delete"
													onClick={() => deleteItemHandler(item?.id)}
												>
													<DeleteBinIcon />
												</button>
											</Tooltip>
										</div>
									</td>
								</tr>
							);
						})}
					</tbody>
				</table>
				{!templatesLoading && (
					<div className="sp-real-saved-template-footer sp-d-flex sp-align-center sp-justify-between">
						<div className="sp-real-saved-template-count">
							Page {currentPage} of {Math.ceil(totalPostCount / 10) || 1} &nbsp;{" "}
							<span>[ {totalPostCount} Items ]</span>
						</div>
						<div className="sp-real-saved-template-pagination sp-d-flex sp-flex-row sp-align-center sp-justify-between sp-gap-8px">
							{pages?.length > 1 && (
								<>
									<button
										className={`sp-real-saved-template-pagination-btn btn-prev${
											currentPage === 1 ? " btn-disabled" : ""
										}`}
										onClick={() => setCurrentPage(currentPage !== 1 ? currentPage - 1 : 1)}
									>
										<LeftArrow />
									</button>
									{pages.map((item, i) => (
										<button
											key={i}
											className={`sp-real-saved-template-pagination-btn ${
												currentPage === item ? "btn-active" : "sp-btn-numb"
											}`}
											onClick={(e) => setCurrentPage(Number(e.target?.value))}
											value={item}
										>
											{item}
										</button>
									))}
									<button
										className={`sp-real-saved-template-pagination-btn sp-btn-next${
											currentPage === pages?.length ? " btn-disabled" : ""
										}`}
										onClick={() =>
											setCurrentPage(
												currentPage !== pages?.length ? currentPage + 1 : pages?.length
											)
										}
									>
										<RightArrow />
									</button>
								</>
							)}
						</div>
					</div>
				)}
			</div>

			{/* Promo Section with Video */}
			{!templatesLoading && <SavedTemplatesPromo homeUrl={homeUrl} />}
		</div>
	);
};

export default memo(SavedTemplates);
