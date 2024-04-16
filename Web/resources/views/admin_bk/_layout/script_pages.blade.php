@switch(request()->route()->getName())
    @case(TASK_EDIT_ADMIN_ROUTER)
    @case(TASK_CREATE_ADMIN_ROUTER)
    @case(COMPANY_CREATE_ADMIN_ROUTER)
    @case(COMPANY_EDIT_ADMIN_ROUTER)
    <script src="{{ asset('js/admin/theme/plugins/singleimageupload.js') }}"></script>
    <script src="{{ asset('js/admin/theme//plugins/dropzone.templates.js') }}"></script>
    <script src="{{ mix('/static/js/admin/pages/task.js') }}"></script>
    @break
    @case(TASK_DEPOSIT_ADMIN_ROUTER)
    {{-- <script src="{{ mix('/static/js/admin/pages/deposit.js') }}"></script> --}}
    {{-- <script src="{{ mix('/static/js/admin/pages/depositETH.js') }}"></script> --}}
    <script src="{{ mix('/static/js/admin/pages/depositNear.js') }}"></script>
    @break
@endswitch
