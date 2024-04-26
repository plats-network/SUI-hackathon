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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0/css/v4-shims.min.css" />
    <link rel="stylesheet" href="{{ url('css/loading.css') }}">
    <meta name='type_network' content="{{ env('TYPE_NETWORK')}}" data-dz-name="">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/sass/event.scss',  'resources/js/event.js'])

    @notifyCss

    <style>
        .valid-feedback {
            color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
        }
    </style>

</head>
<body id="event-page" data-env="{{env('APP_ENV') == 'local' ? false : true}}">
<div class="loading" style="display: none">Loading&#8230;</div>
@include('web.layouts.header')
@yield('content')
@include('web.layouts.footer')

<x-notify::notify />
@notifyJs

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/1.1.0/parallax.min.js"></script>
<script src="https://cdn.usebootstrap.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax/1.1.0/jquery.parallax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/scrollup/2.4.1/jquery.scrollUp.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>

<script type="text/javascript">
    if ($('#event-page').data('env') == true) {
        document.addEventListener('keydown', function() {
            if (event.keyCode == 123) {
                return false;
            } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
                return false;
            } else if (event.ctrlKey && event.keyCode == 85) {
                return false;
            }
        }, false);

        if (document.addEventListener) {
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            }, false);
        } else {
            document.attachEvent('oncontextmenu', function() {
                window.event.returnValue = false;
            });
        }
    }

</script>
@yield('scripts')
@stack('custom-scripts')
</body>
</html>
