<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-url-prefix="/" data-footer="true" data-color="light-blue">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Quiz game</title>
    <meta name="description" content="" />
    @include('quiz-game._layout.head')
</head>

<body>
    <main>
        @yield('content')
    </main>
    @include('quiz-game._layout.scripts')
    @include('quiz-game._layout.scripts_answer')
</body>

</html>
