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
<body class="h-100">
<div id="root" class="h-100">
    <!-- Background Start -->
    <div class="fixed-background" style="background-image: url({{ asset('img/admin/background/background-blue.webp') }})"></div>
    <!-- Background End -->
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <div class="container-fluid p-0 h-100 position-relative">
        <div class="row g-0 h-100" style="height: 100vh;">
            <!-- Left Side Start -->
            <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                <div class="min-h-100 d-flex align-items-center">
                    <div class="w-100 w-lg-75 w-xxl-50">
                        <div>
                            <div class="mb-5">
                                <h1 class="display-3 text-white">
                                    PLATS NETWORK
                                </h1>
                                <h1 class="display-3 text-white">
                                    Action-hub for Business
                                </h1>
                            </div>
                            <p class="h6 text-white lh-1-5 mb-5">
                                A decentralized Action Hub for business, which provides all necessary tools and libraries for Clients to create their marketing campaigns with specific tasks
                            </p>
                            <div class="mb-5">
                                <a class="btn btn-lg btn-outline-white" href="#">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Left Side End -->

            <!-- Right Side Start -->
            <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0" >
                <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border" style="height: 100vh !important;">
                    <div class="sw-lg-50 px-5">
                        <div class="sh-11">
                            <a href="{{url('/cws')}}" title="Plats CWS">
                                <div class="logo-default" style="background-image: url({{ asset('img/admin/logo-light-blue.svg') }})"></div>
                            </a>
                        </div>
                        <div class="mb-3">
                            <h2 class="cta-1 mb-0 text-primary">Welcome, let's get started!</h2>
                        </div>
                        <div class="mb-5">
                            <p class="h6">Please use your credentials to login.</p>
                            <p class="h6">
                                If you are is member, please <a href="{{route(LOGIN_ADMIN_ROUTE)}}">login</a>
                            </p>
                        </div>
                        <div>
                            <x-alert />
                            <x-form::open action="{{ route(REGIS_POST_CREATE) }}" class="tooltip-end-bottom">
                                <div class="mb-3 filled form-group tooltip-end-top">
                                    <i data-acorn-icon="user"></i>
                                    <input class="form-control" placeholder="Name" name="name" value="{{ old('name') }}"/>
                                </div>
                                <div class="mb-3 filled form-group tooltip-end-top">
                                    <i data-acorn-icon="email"></i>
                                    <input class="form-control" placeholder="Email" name="email" value="{{ old('email') }}"/>
                                </div>
                                <div class="mb-3 filled form-group tooltip-end-top">
                                    <i data-acorn-icon="lock-off"></i>
                                    <input class="form-control pe-7" name="password" type="password" placeholder="Password"/>
                                </div>
                                <div class="mb-3 filled form-group tooltip-end-top">
                                    <i data-acorn-icon="lock-off"></i>
                                    <input class="form-control pe-7" name="password_confirmation" type="password" placeholder="Password Confirmation"/>
                                </div>
                                <button type="submit" class="btn btn-lg btn-primary">Register</button>
                            </x-form::open>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin._layout.scripts')
</body>
</html>
