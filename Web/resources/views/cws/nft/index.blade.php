@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Event</h4>
    </div>
@endsection

@section('content')

@endsection

{{--Script--}}

@section('scripts')
    <script src="{{asset('js/sweetalert2@11.min.js')}}"></script>
    <script>
        var spinText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';

        jQuery(document).ready(function ($) {
            //start_at datepicker
            var option = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: 'en'
            };

        });
    </script>
@endsection

