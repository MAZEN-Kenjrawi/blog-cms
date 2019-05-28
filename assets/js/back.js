'use strict';

require('../css/back.scss');


const $ = require('jquery');
window.jQuery = global.$ = global.jQuery = $;
const bootstrap = require('bootstrap');

require('datatables.net-bs4')(window, $);
const froala = require('froala-editor/js/froala_editor.pkgd.min.js')(window, $);
const axios = require('axios');

$(document).ready(function () {
    var $dataTableSelector = $('.dataTable');
    if ($dataTableSelector.hasClass('ajax') && $dataTableSelector.data('endpoint')) {
        $dataTableSelector.DataTable({
            ajax: $dataTableSelector.data('endpoint')
        });
    } else {
        $dataTableSelector.DataTable();
    }

    var $editor = $('.editor');
    if ($editor.length > 0) {
        $editor.froalaEditor({
            height: 500,
            // Set the image upload URL
            imageUploadURL: '/admin/api/upload/image/type',
            // Set the file upload URL
            fileUploadURL: '/admin/api/upload/file/type',
            // Set the video upload URL
            videoUploadURL: '/admin/api/upload/video/type',
            // Set the image upload URL
            imageManagerLoadURL: '/admin/api/load/image/type',
            // Set the image delete URL
            imageManagerDeleteURL: '/admin/api/delete/image/type',
            videoResponsive: true
        });
    }

    var $forSlug = $('.for-slug');
    var $slug = $('.slug');
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

        $editor.froalaEditor('selection.save');
        $editor.froalaEditor('html.wrap', true, true, true);
        $editor.froalaEditor('selection.restore');

        var elements = $editor.froalaEditor('selection.blocks');
        $editor.froalaEditor('selection.save');
        console.log(elements.length)
        $('input, textarea').css('text-align', align);
        for (var i = 0; i < elements.length; i++) {
            var element = elements[i];
            $(element)
                .css('direction', direction)
                .css('text-align', align)
                .removeClass('fr-temp-div');
        }

        // Unwrap temp divs.
        $editor.froalaEditor('html.unwrap');
        // Restore selection.
        $editor.froalaEditor('selection.restore');
    });

});