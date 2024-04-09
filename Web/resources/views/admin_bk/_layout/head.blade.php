<!-- Font Tags Start -->
<link rel="preconnect" href="https://fonts.gstatic.com"/>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet"/>
{{--<link rel="stylesheet" href="/font/CS-Interface/style.css"/>--}}
<!-- Font Tags End -->
<!-- Vendor Styles Start -->
<link rel="stylesheet" href="{{ asset('css/admin/vendor.css') }}"/>
@stack('css')
<!-- Vendor Styles End -->
<!-- Template Base Styles Start -->
<link rel="stylesheet" href="{{ asset('css/admin/template.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/admin/custom.css') }}"/>
<!-- Template Base Styles End -->
<script src="{{ asset('js/admin/theme/base/loader.js') }}"></script>
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<style>
    .file-input__input {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .file-input__label {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        font-size: 14px;
        padding: 10px 12px;
        background-color: #4245a8;
        box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.25);
    }

    .file-input__label svg {
        height: 16px;
        margin-right: 4px;
    }
</style>
