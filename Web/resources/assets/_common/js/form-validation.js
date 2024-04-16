/**
 * Initialize and Override existing jquery-validate methods.
 *
 * @requires ~/jquery-validation
 */
require('jquery-validation');
require('jquery-validation/dist/additional-methods.min');
//require('./form-validation-additional');
//require('./i18n/jquery-validation');

// Init function
const init = function () {
    $('input[name^="p_images"]').each(function () {
        $(this).rules('add', {
            extension: 'jpg|jpeg|png'
        });
    });

    $(document).on('click', 'button[type="submit"]', function (e) {
        e.preventDefault();
        let $this = $(this);
        let form = $this.parents('form');
        if (form.hasClass('has_validate') && !form.valid()) {
            form.addClass('was-validated');
            return false;
        }

        $this.prop('disabled', true);
        form.submit();

        return true;
    });

    setTimeout(function () {
        $(".time-out-message").fadeOut()
    }, 5000);
};
// FormValidation
jQuery.fn.formValidation = function (options = {}) {
    const $this = $(this);

    let settings = {
        errorElement: 'div',
        errorClass: 'error help-block help-block-error invalid-feedback',
        focusInvalid: true,
        //ignore: "",// validate hidden fields, required for cs-select
        highlight: () => {},
        unhighlight: () => {},
        errorPlacement: () => {},
        success: () => {}
    };

    const dataOpts = $this.data();

    settings = $.extend({}, settings, dataOpts, options);

    // highlight default
    settings.highlight = (element) => {
        $(element).closest('.form-group').addClass('has-error');
    };

    // unhighlight default
    settings.unhighlight = (element) => {
        $(element).closest('.form-group').removeClass('has-error help-block help-block-error');
    };

    // errorPlacement default
    settings.errorPlacement = (error, element) => {
        validateErrorPlacement(error, element, true);
    };

    // success default
    settings.success = (label) => {
        label.closest('.form-group').removeClass('has-error help-block help-block-error');
    };

    // form validation
    $this.each(function () {
        $(this).validate(settings);
    });

    // validateErrorPlacement
    const validateErrorPlacement = (error, element, place) => {
        if (!place) {
            place = false;
        }
        if ($(element).attr('type') == 'radio' || $(element).attr('name') == 'g-recaptcha-response') {
            $(element).parent().parent().append(error);
            return true;
        }
        let parent = $(element).closest('.form-group');
        if (parent.hasClass('form-group-default')) {
            parent.addClass('has-error');
            error.insertAfter(parent);
        } else {
            let cont = $(element).parent();
            if (cont.hasClass('checkbox-inline') || cont.hasClass('date-calendar')) {
                cont.parent().prepend(error);
            } else if (cont.hasClass('input-group')) {
                cont.parent().append(error);
            } else {
                if (!place) {
                    element.parent().prepend(error);
                } else {
                    element.parent().append(error);
                }
            }
        }

    };
};

jQuery(document).ready(function () {
    $('form.has_validate').formValidation();
    //form.classList.add('was-validated');
    init();
});

