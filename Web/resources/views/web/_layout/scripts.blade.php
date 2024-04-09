<!-- Vendor Scripts Start -->
<script src="{{ asset('js/admin/vendor.js') }}"></script>
<script src="{{ asset('js/admin/theme/plugins/scrollbarByCount.js') }}"></script>

<script src="{{ asset('js/admin/theme/icon/acorn-icons.js') }}"></script>
<script src="{{ asset('js/admin/theme/icon/acorn-icons-interface.js') }}"></script>
<script src="{{ asset('js/admin/theme/icon/acorn-icons-commerce.js') }}"></script>

@yield('js_vendor')
<!-- Vendor Scripts End -->
<!-- Template Base Scripts Start -->
<script src="{{ asset('js/admin/theme/base/helpers.js') }}"></script>
<script src="{{ asset('js/admin/theme/base/globals.js') }}"></script>
<script src="{{ asset('js/admin/theme/base/nav.js') }}"></script>
<script src="{{ asset('js/admin/theme/base/search.js') }}"></script>
<script src="{{ asset('js/admin/theme/base/settings.js') }}"></script>
<script src="{{ asset('js/admin/theme/plugins/checkall.js') }}"></script>
<!-- Template Base Scripts End -->
<!-- Page Specific Scripts Start -->
@stack('scripts')

<script src="{{ asset('js/admin/theme/common.js') }}"></script>
<script src="{{ asset('js/admin/theme/scripts.js?v=1.0') }}"></script>
<script src="{{ asset('js/admin/app.js') }}"></script>
<!-- Page Specific Scripts End -->
