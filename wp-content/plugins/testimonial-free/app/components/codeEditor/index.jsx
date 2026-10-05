/**
 * Thin React wrapper over WP core CodeMirror (wp.codeEditor).
 *
 * Zero bundle cost — assets come from wp_enqueue_code_editor(). Falls back to a
 * plain textarea when the user has disabled the code editor in their profile.
 *
 * Pass `settings` — the array wp_enqueue_code_editor() returns, localized to the
 * page — to get that language's full core configuration: mode, linting, bracket
 * matching and the Ctrl-Space autocomplete binding. Without it the editor starts
 * from wp.codeEditor.defaultSettings with the mapped `mode` applied and linting
 * off.
 */

import { useEffect, useRef } from "@wordpress/element";
import "./editor.scss";

/**
 * Map the language name used by the call sites to a CodeMirror MIME mode.
 *
 * @param {string} language "css" or "javascript".
 * @return {string} CodeMirror MIME mode.
 */
const getEditorMode = (language) => {
	if ("javascript" === language || "js" === language) {
		return "text/javascript";
	}
	return "text/css";
};

/**
 * @param {Object}          props
 * @param {string}          [props.label]           Field label.
 * @param {string}          [props.attributes]      Current code string.
 * @param {Function}        [props.setAttributes]   Block setter, used when `onChange` is absent.
 * @param {string}          [props.attributesKey]   Attribute name for `setAttributes`.
 * @param {Function|false}  [props.onChange]        Called with the new code string.
 * @param {string|number}   [props.height]          Editor height, e.g. "180px" or 180.
 * @param {boolean}         [props.resizable]       Resize via the browser's native CSS grip; CodeMirror is repainted through a ResizeObserver.
 * @param {string}          [props.defaultLanguage] "css" or "javascript".
 * @param {Object}          [props.settings]        Full wp.codeEditor settings from wp_enqueue_code_editor().
 */
const CodeEditor = ({
	label = "",
	attributes = "",
	setAttributes,
	attributesKey = "",
	onChange = false,
	height = "180px",
	resizable = false,
	defaultLanguage = "css",
	settings = null,
}) => {
	const textareaRef = useRef(null);
	const editorRef = useRef(null);
	const editorHeight = parseInt(height, 10) || 180;

	const setCustomCss = (value) => {
		if (onChange) {
			onChange(value);
		} else {
			setAttributes({ [attributesKey]: value });
		}
	};

	const onChangeRef = useRef(setCustomCss);
	onChangeRef.current = setCustomCss;

	useEffect(() => {
		const textarea = textareaRef.current;
		if (!textarea) {
			return undefined;
		}

		const codeEditorApi = window.wp?.codeEditor;
		if (!codeEditorApi?.initialize) {
			const handleInput = () => {
				onChangeRef.current(textarea.value);
			};
			textarea.addEventListener("input", handleInput);
			return () => {
				textarea.removeEventListener("input", handleInput);
			};
		}

		const defaults = codeEditorApi.defaultSettings || {};
		// Without page-supplied settings, do not force lint: WP core ships CSS mode
		// with lint:false and does not enqueue csslint for it — enabling lint without
		// window.CSSLint throws "window.CSSLint not defined, CodeMirror CSS linting
		// cannot run." A caller that wants linting passes the settings that
		// wp_enqueue_code_editor() returned, which arrive with the linters loaded.
		const instanceSettings = settings || {
			...defaults,
			codemirror: {
				...(defaults.codemirror || {}),
				mode: getEditorMode(defaultLanguage),
				lineNumbers: true,
				lint: false,
			},
		};
		const instance = codeEditorApi.initialize(textarea, instanceSettings);

		const codeMirror = instance.codemirror;
		editorRef.current = codeMirror;

		const handleChange = (editor) => {
			onChangeRef.current(editor.getValue());
		};
		codeMirror.on("change", handleChange);
		codeMirror.setSize(null, editorHeight);

		return () => {
			codeMirror.off("change", handleChange);
			codeMirror.toTextArea();
			editorRef.current = null;
		};
		// Init once per mount; height/onChange synced via refs / separate effect.
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	// Sync the value coming back from the parent without moving the caret.
	useEffect(() => {
		const codeMirror = editorRef.current;
		const next = attributes || "";
		if (codeMirror) {
			if (next !== codeMirror.getValue()) {
				const cursor = codeMirror.getCursor();
				codeMirror.setValue(next);
				codeMirror.setCursor(cursor);
			}
			return;
		}
		const textarea = textareaRef.current;
		if (textarea && textarea.value !== next) {
			textarea.value = next;
		}
	}, [attributes]);

	useEffect(() => {
		const codeMirror = editorRef.current;
		if (codeMirror) {
			codeMirror.setSize(null, editorHeight);
		}
	}, [editorHeight]);

	/**
	 * A CSS-resized CodeMirror needs a nudge: the browser changes the wrapper's
	 * height behind its back, so watch the wrapper and repaint on change. The
	 * plain-textarea fallback needs no observer — the browser reflows it.
	 */
	useEffect(() => {
		if (!resizable) {
			return undefined;
		}
		const codeMirror = editorRef.current;
		const wrapper = codeMirror?.getWrapperElement?.();
		if (!wrapper || "undefined" === typeof ResizeObserver) {
			return undefined;
		}
		const observer = new ResizeObserver(() => {
			codeMirror.refresh();
		});
		observer.observe(wrapper);
		return () => {
			observer.disconnect();
		};
	}, [resizable]);

	return (
		<div className="sp-real-code-editor-component sp-real-component-mb">
			<div className="sp-real-code-editor-label">
				<p className="sp-real-component-title">{label}</p>
			</div>
			<div className={resizable ? "sp-real-code-editor sp-real-code-editor--resizable" : "sp-real-code-editor"}>
				<textarea
					ref={textareaRef}
					defaultValue={attributes || ""}
					className="sp-real-code-editor__textarea"
					style={{ height: `${editorHeight}px`, width: "100%" }}
					spellCheck={false}
				/>
			</div>
		</div>
	);
};
export default CodeEditor;
