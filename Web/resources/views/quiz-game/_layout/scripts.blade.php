<!-- Vendor Scripts Start -->
<script src="{{ url('static/js/admin/vendor.js') }}"></script>
<script type="text/javascript">
    const PUSHER_APP_KEY = "{{ env('PUSHER_APP_KEY') }}"
    const PUSHER_APP_CLUSTER = "{{ env('PUSHER_APP_CLUSTER') }}"
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="{{ url('static/js/admin/theme/plugins/pusher.js') }}"></script>
@include('quiz-game.reload_confirm_modal')
@yield('js_vendor')
<!-- Vendor Scripts End -->
<!-- Page Specific Scripts Start -->
@stack('scripts')
<!-- Page Specific Scripts End -->
