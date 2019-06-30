'use strict';

require('../css/back.scss');

const $ = require('jquery');
window.jQuery = global.$ = global.jQuery = $;
const bootstrap = require('bootstrap');

require('datatables.net-bs4')(window, $);
const axios = require('axios');
// Import TinyMCE
var tinymce = require('tinymce/tinymce');
const fancybox = require('@fancyapps/fancybox');

$(document).ready(function () {
    var $dataTableSelector = $('.dataTable');
    if ($dataTableSelector.hasClass('ajax') && $dataTableSelector.data('endpoint')) {
        var columns = tableColumnsStrcture($dataTableSelector);
        var table = $dataTableSelector.DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                type: 'post',
                url: $dataTableSelector.data('endpoint'),
                data: function (defaultSubmitedData) {
                    return $.extend({}, defaultSubmitedData, formDataToJSON($('#filters-from').serializeArray()));
                },
            },
            "columns": columns
        });
    } else {
        var table = $dataTableSelector.DataTable();
    }

    var $tableFilters = $('.table-filter');
    $tableFilters.change(function () {
        table.draw();
    });

    var $editor = $('.editor');
    if ($editor.length > 0) {
        var config = getTinyMCEConfig('.editor', 'ltr');
        tinymce.init(config);
    }

    var $forSlug = $('.for-slug');
    var $slug = $('.slug');
    var $meta = $('.meta-title');
    $forSlug.keyup(function () {
        if ($(this).closest('form').hasClass('editingForm')) {
            return;
        }
        var slugValue = $(this).val();
        slugValue = slugValue.replace(/[&\\#,+()$~%.'":*?<>{}!@^=]/g, '');
        slugValue = slugValue.replace(/ /g, '-');
        slugValue = slugValue.replace(/--/g, '-');
        $slug.val(slugValue.toLowerCase());
    });

    var $languageSelect = $('.language-value');
    $languageSelect.change(function () {
        var align = 'left';
        var direction = 'ltr';
        if ($(this).val() == 'ar') {
            align = 'right';
            direction = 'rtl';
        }
        $('input, textarea').css({'text-align': align, 'direction': direction});

        var editorID = $editor.attr('id');
        var editorContent = tinymce.get(editorID).getContent();
        var config = getTinyMCEConfig('#' + editorID, direction);
        tinymce.get(editorID).destroy();
        tinymce.init(config);
        tinymce.get(editorID).setContent(editorContent);
    }).change();

    $(document).on('click', '.change-status', function () {
        axios.get($(this).data('url')).then(resp => {
            if (resp.status != 200) {
                return false;
            }
            $(this).toggleClass('fa-times text-danger fa-check text-success');
        });
    });
});

function tableColumnsStrcture($table) {
    var $thead = $table.find('thead tr th');
    var columns = [];
    $thead.each(function (index) {
        columns.push({ 'data': $(this).data('label'), 'orderable': (typeof $(this).data('orderable') != 'undefined') });
    });

    return columns;
}

function getTinyMCEConfig(selector, direction) {
    return {
        selector: selector,
        height: 500,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor filemanager code"
        ],
        toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
        toolbar2: "| filemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
        image_advtab: true,
        image_caption: true,
        directionality: direction,
        external_filemanager_path: "/bundles/filemanager/",
        filemanager_title: "File Manager",
        external_plugins: { "filemanager": "/bundles/filemanager/plugin.min.js" },
        filemanager_access_key: window.csrf,
    }
}

function formDataToJSON(data) {
    let loginFormObject = {};
    $.each(data, function (index, value) {
        loginFormObject[value.name] = value.value;
    });

    return loginFormObject;
}