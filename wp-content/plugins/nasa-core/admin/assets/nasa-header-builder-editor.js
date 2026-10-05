var section_on_edit;
var img_select_change;
var dialog_editor = null;
var data_save = {
    "ns-header-topbar-left": {},
    "ns-header-topbar-middle": {},
    "ns-header-topbar-right": {},
    "ns-header-main-left": {},
    "ns-header-main-middle": {},
    "ns-header-main-right": {},
    "ns-header-bottom-left": {},
    "ns-header-bottom-middle": {},
    "ns-header-bottom-right": {}
};
var isSubmitting = false;

jQuery(document).ready(function ($) {

    $(document).on("submit", 'form[name="post"]', function () {
        isSubmitting = true;
        $('.nasa-header-editor').addClass('loading');
    });

    $('.nasa-header-editor').removeClass('loading');

    $(window).on("beforeunload", function () {
        var editor = $("#nasa_header_custom_editor_data");

        if (isSubmitting) {
            return;
        }

        if ($(editor).length && $(editor).hasClass("nasa-on-change")) {
            return "Changes you made may not be saved.";
        }
    });

    elessi_init_dragable($, $('.ns-draggable'));

    $(".ns-droppable").droppable({
        accept: ".ns-draggable",
        over: function (event, ui) {
            if (!$(this).find('.ns-draggable-template').length) {
                var text = ui.helper.text().trim();
                $(this).append('<div class="ns-draggable-template" >' + text + '</div>');
            }
        },
        out: function (event, ui) {
            $(this).find('.ns-draggable-template').remove();
        },
        drop: function (event, ui) {
            $(this).find('.ns-draggable-template').remove();
            $(this).append(ui.draggable);
            ui.draggable.css({
                top: 0,
                left: 0
            });

            elessi_save_editor_header($)
        }
    });


    $("#popup-header-editor").dialog({
        autoOpen: false,
        modal: true,
        width: 600,
        maxWidth: '90vw',
        maxHeight: '90vh',
        minWidth: 400,
        height: 500,
        closeOnEscape: true,
        position: { my: "center", at: "center", of: window },
        show: { effect: "fade", duration: 300 },
        hide: { effect: "fade", duration: 300 },
        buttons: [
            {
                text: $("#popup-header-editor").attr('data-text-close'),
                class: "button",
                click: function () {
                    $(this).dialog("close");
                }
            }
        ],
        open: function () {
            $('body').css('overflow', 'hidden');

            $('.ui-widget-overlay').on('click', function () {
                $("#popup-header-editor").dialog("close");
            });
        },
        close: function () {
            $('body').css('overflow', '');
            $('.ui-widget-overlay').off('click');
        }
    });

    $('body').on('keydown input', '.ns-spacing-input input', function (e) {
        // if (e.key === ' ' || e.keyCode === 32) {
            let val = $(this).val();

            val = val.replace(/\s+/g, ' ');

            let parts = val.split(' ');

            parts = parts.map(function (part) {
                return part.replace(/[^0-9]/g, '');
            });

            if (parts.length > 4) {
                e.preventDefault();
                parts = parts.slice(0, 4);

            }
            $(this).val(parts.join(' '));
        // }
    });

    $('body').on('click', '.ns-add-item-header', function () {
        section_on_edit = $(this).parents('.ns-droppable');
        $('#popup-header-editor').dialog('open');
    });

    $('body').on('click', '#popup-header-editor .popup-items-wrap .popup-item', function (e) {
        e.preventDefault();
        $('#popup-header-editor').dialog('close');
        var _this = $(this);
        var item = $(_this).attr('data-item');
        var dataName = $(_this).attr('data-name');

        elessi_init_dialog_item_editor($, _this, item, dataName, '');

        // $('.ui-widget-overlay').off('click').on('click', function () {
        //     $dialog.dialog('close');
        // });
    });




    $('body').on('click', '.nasa-media-upload', function (event) {
        var _this = $(this);
        event.preventDefault();

        if (dialog_editor) {
            $(dialog_editor).parent().hide();
        }

        // If the media frame already exists, clear it.
        if (img_select_change) {
            img_select_change.close();
            img_select_change = null
        }

        // Create the media frame.
        img_select_change = wp.media.frames.downloadable_file = wp.media({
            title: $(_this).attr('data-title'),
            button: {
                text: $(_this).attr('data-text')
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        img_select_change.on('select', function () {
            var attachment = img_select_change.state().get('selection').first().toJSON();
            var attachment_thumbnail = attachment.sizes.full || attachment.sizes.thumbnail;

            $('#' + $(_this).attr('data-target')).val(JSON.stringify({
                id: attachment.id,
                url: attachment_thumbnail.url
            }));

            $(_this).parent().find('img').attr('src', attachment_thumbnail.url);
            $('.nasa-media-remove').show();

            if (dialog_editor) {
                $(dialog_editor).parent().show();
            }
        });

        img_select_change.on('close', function () {
            if (dialog_editor) {
                $(dialog_editor).parent().show();
            }
        });

        // Finally, open the modal.
        img_select_change.open();

    });

    $('body').on('click', '.nasa-media-remove', function (event) {
        var _this = $(this);
        event.preventDefault();

        $('#' + $(_this).attr('data-target')).val('');
        $(_this).parent().find('img').attr('src', $(_this).attr('data-url-placeholder'));
        $(_this).hide();
    });

    $('body').on('nasa-init-html-editor', function () {
        $('textarea.nasa-html-editor').each(function () {
            var textarea = $(this);

            if ($(textarea).hasClass('editor-initialized')) {
                return;
            }

            $(textarea).addClass('editor-initialized');

            var editor = wp.codeEditor.initialize($(textarea), {
                codemirror: {
                    mode: 'html',
                    lineNumbers: true,
                    indentUnit: 2,
                    tabSize: 2,
                    autoCloseBrackets: true,
                    matchBrackets: true,
                    lineWrapping: true,
                    viewportMargin: Infinity
                }
            });

            if (editor && editor.codemirror) {
                var cm = editor.codemirror;
                setTimeout(function () {
                    cm.refresh();
                }, 500);

                cm.on('change', function (cmInstance) {
                    $(textarea).val(cmInstance.getValue());
                });
            }
        });
    });

    $('body').on('click', '.edit-item-header', function () {
        var _this = $(this);
        var _pa = $(_this).parents('.ns-item-header');
        var json = $(_pa).attr('data-json').trim() != '' ? JSON.parse($(_pa).attr('data-json')) : '';
        var name = $(_pa).text();
        elessi_init_dialog_item_editor($, _this, json['item'], name, json);
    });

    $('body').on('click', '.ns-edit-header-wrap, .ns-edit-section-header', function () {
        var _this = $(this);
        var _pa = $(_this).hasClass('ns-edit-header-wrap') ? $(_this).parents('.nasa-header-editor-wrap') : $(_this).parents('.ns-droppable');
        var json = $(_pa).attr('data-json').trim() != '' ? JSON.parse($(_pa).attr('data-json')) : '';
        var name = $(_this).attr('data-name');
        var template = $(_this).hasClass('ns-edit-header-wrap') ? 'header-wrap' : 'section-wrap';
        section_on_edit = $(_pa);
        elessi_init_dialog_item_editor($, _this, template, name, json);
    });

    $('body').on('click', '.remove-item-header', function () {

        var _this = $(this);
        var _pa = $(_this).parents('.ns-item-header');

        if (confirm($(_this).attr('data-confirm'))) {
            $(_pa).remove();
            elessi_save_editor_header($);
        }
    });

    $('body').on('change input', '.nasa-dialog-edit-item-header form input, .nasa-dialog-edit-item-header form select, .nasa-dialog-edit-item-header form textarea', function () {
        $('.nasa-dialog-edit-item-header form').addClass('nasa-dialog-editing');
    });


    $('body').on('click', '.ns-toggle-otp a', function () {

        var _this = $(this);
        var _pa = $(_this).parents('.ns-toggle-otp');
        var _form = $(_this).parents('.form-table');
        var target = $(_this).attr('data-target');

        if (!$(_this).hasClass('ns-active')) {
            var is_actived = $(_pa).find('a.ns-active');
            var target_actived = $(is_actived).attr('data-target');

            $(is_actived).removeClass('ns-active');
            $(_this).addClass('ns-active');

            $(_form).find('.' + target_actived).hide();
            $(_form).find('.' + target).show();
        }
    });
});

function elessi_init_dragable($, target) {
    $(target).draggable({
        revert: "invalid",
        revertDuration: 100,
        containment: "window",
        start: function (event, ui) { }
    });
}


function elessi_init_dialog_item_editor($, button, item, dataName, data) {
    var tmplId = '#tmpl-nasa-' + item;
    var content = $(tmplId).html();
    var id = Date.now();
    var buttons = {
        'Save': function () {
            elessi_save_item_header($, this, item, dataName, false);
        },
        'Save and close': function () {
            elessi_save_item_header($, this, item, dataName, true);
        },
        'Close': function () {
            var form = $(this).find('form');
            if ($(form).hasClass('nasa-dialog-editing')) {
                if (!confirm($('.nasa-header-editor').attr('data-ask-close'))) {
                    return;
                }
            }

            $(this).dialog('close');
            $(form).removeClass('nasa-dialog-editing');
        }
    };

    if (!content) {
        return;
    }

    if ($(button).attr('data-id-dialog')) {
        var id_dialog = $(button).attr('data-id-dialog');
        if ($('.ui-dialog-content[data-id-dialog="' + id_dialog + '"]').length) {
            $('.ui-dialog-content[data-id-dialog="' + id_dialog + '"]').dialog('open');
            return;
        }
    }

    if (data && typeof data === 'object' && Object.keys(data).length > 0) {
        id = data['id'] != '' && data['id'] != 'undefined' ? data['id'] : id;
        content = content.replace(/{{item-id}}/g, id);

        if (data.item !== 'section-wrap' && data.item !== 'header-wrap') {
            buttons['Duplicate'] = function () {
                var duplicate_item = $('#' + id);
                var item_clone = duplicate_item.clone();
                var json = JSON.parse($(item_clone).attr('data-json'));
                json.id = Date.now();

                $(this).dialog('close');
                $(item_clone).attr({ 'data-json': JSON.stringify(json), 'id': json.id });
                $(item_clone).find('.edit-item-header').attr('data-id-dialog', json.id);

                $(duplicate_item).parent().append(item_clone);
                elessi_save_editor_header($);
                elessi_init_dragable($, $(item_clone));
            };
        }
    } else {
        content = content.replace(/{{item-id}}/g, id);
        if (item == 'header-wrap') {
            if (!$(section_on_edit).find('.ns-edit-header-wrap').attr('data-id-dialog')) {
                $(section_on_edit).find('.ns-edit-header-wrap').attr({ 'data-id-dialog': id });
            }
        } else if (item == 'section-wrap') {
            if (!$(section_on_edit).find('.ns-edit-section-header').attr('data-id-dialog')) {
                $(section_on_edit).find('.ns-edit-section-header').attr({ 'data-id-dialog': id });
            }
        }
    }

    dialog_editor = $('<div data-id-dialog="' + id + '"></div>').html(content).dialog({
        modal: true,
        width: 700,
        height: 700,
        closeOnEscape: true,
        position: { my: "center", at: "center", of: window },
        show: { effect: "fade", duration: 300 },
        hide: { effect: "fade", duration: 300 },
        modal: true,
        title: dataName,
        dialogClass: 'nasa-dialog-edit-item-header',
        width: 600,
        buttons: buttons,
        open: function () {
            var form = $(this).find('form');

            if (data && typeof data === 'object') {
                data = data['data'];
                $.each(data, function (key, value) {
                    var field = form.find('#' + key);

                    if (field.length) {
                        if (field.is(':checkbox')) {
                            field.prop('checked', !!value);
                        } else if (field.is(':radio')) {
                            form.find('input[name="' + key + '"][value="' + value + '"]').prop('checked', true);
                        } else if (field.hasClass('ns-media-input') && value !== '') {
                            var img = field.closest('td').find('img').first();
                            var json_img = JSON.parse(value);

                            if (img.length && json_img && typeof json_img === 'object') {
                                img.attr('src', json_img['url']);
                            }
                            field.val(value);
                        } else {
                            field.val(value);
                        }
                    }
                });
            }

            if ($(form).find('.ns-toggle-otp')) {
                var toggle_wrap = $(form).find('.ns-toggle-otp')
                var data_std = $(toggle_wrap).attr('data-std');
                $(toggle_wrap).find('a[data-target="' + data_std + '"]').trigger('click');
            }

            $('body').css('overflow', 'hidden');
            $('body').trigger('nasa-init-html-editor');
            loadColorPicker($);
        },
        close: function () {
            $('body').css('overflow', '');
            // $(this).find(".wp-picker-container").wpColorPicker("destroy");
            // $(this).dialog('destroy').remove();
            $(this).dialog("close");

        }
    });
}


function elessi_save_item_header($, _this, item, dataName, close) {
    var form = $(_this).find('form');
    var jsonData = {};
    var newItem = $('#tmpl-nasa-item-header').html();
    var id = $(form).attr('data-id');

    if ($(form).length) {
        var data_form = $(form).serializeArray();
    }

    $(form).removeClass('nasa-dialog-editing');

    $.each(data_form, function (index, field) {
        if (field.value) {
            jsonData[field.name] = field.value;
        }
    });

    jsonData = {
        id: id,
        item: item,
        data: jsonData
    };

    if (item == 'section-wrap' || item == 'header-wrap') {
        $(section_on_edit).attr({ 'data-json': JSON.stringify(jsonData) });
    } else {
        if (!$('#' + id).length) {
            newItem = newItem.replace(/{{name}}/g, dataName);
            newItem = newItem.replace(/{{data-id-dialog}}/g, id);
            
            newItem = $(newItem);

            $(section_on_edit).append($(newItem));
        } else {
            newItem = $('#' + id);
        }

        $(newItem).attr({ 'data-json': JSON.stringify(jsonData), id: id });

        elessi_init_dragable($, $(newItem));
    }

    elessi_save_editor_header($)

    if (close) {
        $('body').css('overflow', '');
        // $(_this).find(".wp-picker-container").wpColorPicker("destroy");
        // $(_this).dialog('destroy').remove();
        $(_this).dialog("close");

    }
}

function elessi_save_editor_header($) {
    $('form[name="post"]').find('.button[type="submit"]').addClass('disabled');
    data_save['ns-header-topbar'] = {};
    data_save['ns-header-main'] = {};
    data_save['ns-header-bottom'] = {};

    $.each(data_save, function (key) {
        var container = $('[data-pos="' + key + '"]');
        const validKeys = ['ns-header-topbar', 'ns-header-main', 'ns-header-bottom'];
        if (!container.length || validKeys.includes(key)) {
            return true;
        } else {
            var items = [];
            var settings = $(container).attr('data-json') != '' ? JSON.parse($(container).attr('data-json')) : [];

            $(container).find('.ns-item-header').each(function () {
                var jsonStr = $(this).attr('data-json');
                try {
                    var jsonObj = JSON.parse(jsonStr);
                    items.push(jsonObj);
                } catch (e) {
                    console.warn(this, e);
                }
            });

            data_save[key] = {
                items: items,
                settings: settings
            };
        }
    });

    data_save['ns-header-topbar']['settings'] = $('.nasa-header-topbar-editor-wrap').attr('data-json') != '' ? JSON.parse($('.nasa-header-topbar-editor-wrap').attr('data-json')) : {};
    data_save['ns-header-main']['settings'] = $('.nasa-header-main-editor-wrap').attr('data-json') != '' ? JSON.parse($('.nasa-header-main-editor-wrap').attr('data-json')) : {};
    data_save['ns-header-bottom']['settings'] = $('.nasa-header-bottom-editor-wrap').attr('data-json') != '' ? JSON.parse($('.nasa-header-bottom-editor-wrap').attr('data-json')) : {};

    $('#nasa_header_custom_editor_data').val(JSON.stringify(data_save));
    $('#nasa_header_custom_editor_data').addClass('nasa-on-change');
    $('form[name="post"]').find('.button[type="submit"]').removeClass('disabled');
}
