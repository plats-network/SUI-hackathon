<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-url-prefix="/" data-footer="true"
      data-color="light-blue">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Action Hub Control Panel</title>
    <meta name="description" content=""/>
    @include('web._layout.head')
</head>
<body>
<div id="root">
    <div id="nav" class="nav-container d-flex">
        @include('web._layout.nav')
    </div>
    <main>
        @yield('content')
    </main>
    @include('web._layout.footer')
</div>
@include('web._layout.scripts')
</body>
</html>
