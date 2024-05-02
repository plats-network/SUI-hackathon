<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        {!! seo() !!}
        {{-- Favicon --}}
        <link rel="apple-touch-icon" sizes="180x180" href="{{url('/')}}/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{url('/')}}/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{url('/')}}/favicon-16x16.png">
        <link rel="manifest" href="{{url('/')}}/site.webmanifest">
        {{-- End Favicon --}}
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <script src="{{asset('js/sweetalert2@11.min.js')}}"></script>
        @notifyCss
        @vite(['resources/sass/event-job.scss'])
    </head>
    <body>
        <div id="auth" class="row justify-content-center">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>

        <x-notify::notify />
        @notifyJs
    </body>
</html>
