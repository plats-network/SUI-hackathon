import flatpickr from "flatpickr";
//Language Vietnamese
import {Vietnamese} from "flatpickr/dist/l10n/vn.js";


import EditorJS from '@editorjs/editorjs';
//Import Header
import Header from '@editorjs/header';
/*//Import SimpleImage
import SimpleImage from '@editorjs/simple-image';
//Import List
import List from '@editorjs/list';
//Import Checklist
import Checklist from '@editorjs/checklist';
//Import Quote
import Quote from '@editorjs/quote';
//Import Warning
import Warning from '@editorjs/warning';
//Import Marker
import Marker from '@editorjs/marker';
//Import Code
import CodeTool from '@editorjs/code';
//Import Delimiter
import Delimiter from '@editorjs/delimiter';
//Import InlineCode
import InlineCode from '@editorjs/inline-code';
//Import LinkTool
import LinkTool from '@editorjs/link';
//Import Embed
import Embed from '@editorjs/embed';
//Import Table
import Table from '@editorjs/table';
//Import Paragraph
import Paragraph from '@editorjs/paragraph';
//Import Raw
import Raw from '@editorjs/raw';*/

var EVENTFORM = (function () {
    //is_off_work
    let isOffWorkInput = $('input[name="is_off_work"]');
    //start_at
    let startAtInput = $('input[name="start_at"]');
    //End_at
    let endAtInput = $('input[name="end_at"]');
    //flexCheckPaid Checkbox input
    let flexCheckPaid = $('input[name="paid"]');


    //Function check checkbox is_off_work is checked then disable worked_hours and project_id

    //Init form event
    function initFormEvent() {
        //Check flexCheckPaid is checked then show div #paid
        $('#flexCheckPaid').on('change', function () {
            if ($(this).is(':checked')) {
                $('#row_paid').show();
            } else {
                $('#row_paid').hide();
            }
        });
        //start_at datepicker
        $('#start_at').flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: 'vn'
            }
        )
        //End_at datepicker
        $('#end_at').flatpickr(
            {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: 'vn'

            }
        )
    }


    return {
        _: function () {
            initFormEvent();
        },
    };
})();

jQuery(function () {
    EVENTFORM._();
});
