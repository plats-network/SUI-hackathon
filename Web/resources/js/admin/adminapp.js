import './bootstrap';


import $ from 'jquery';
window.jQuery = window.$ = $;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


// recommended approach
import { MetisMenu } from 'metismenujs';

window.MetisMenu = MetisMenu;
