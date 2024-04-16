<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-url-prefix="/" data-footer="true" data-color="light-blue">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Action Hub Control Panel</title>
    <meta name="description" content=""/>
    @include('admin._layout.head')
</head>
<body>
<div id="root">
    <div id="nav" class="nav-container d-flex">
        @include('admin._layout.nav')
    </div>
    <main>
        <div>
            <div class="row">
            @include('admin._layout.menu_left')
            <!-- Page Content Start -->
                <div class="col main-cws">
                    @yield('content')
                </div>
            <!-- Page Content End -->
            </div>
        </div>
    </main>
    @include('admin._layout.footer')
</div>
@include('admin._layout.scripts')

<style type="text/css">
    .main-cws {
        background-color: #fff;
    }

    #app {
        padding: 20px;
    }
</style>
</body>
</html>
