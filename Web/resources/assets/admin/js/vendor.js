window.$ = window.jQuery = require('jquery');
window.bootstrap = require('bootstrap');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

require('../../_common/js/lodash-install');
require('../../_common/js/translator');
require('../../_common/js/form-validation');

require('overlayscrollbars/js/OverlayScrollbars.min');
require('../../theme/acorn/vendor/autoComplete.min');
require('../../theme/acorn/vendor/clamp.min');
require('select2');
