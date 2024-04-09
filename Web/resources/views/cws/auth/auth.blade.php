<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <title>Login Event Plats</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Login Event Plats" name="description" />
        <meta content="Login" name="author" />
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        @notifyCss
        @vite(['resources/sass/admin.scss', 'resources/js/admin.js', 'resources/js/admin/pages/pass-addon.init.js'])
    </head>
    <body>
        <div class="authentication-bg min-vh-100">
            <div class="bg-overlay bg-light"></div>
            <div class="container">
                <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                    <div class="row justify-content-center my-auto">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            @yield('content')
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center p-4">
                                <p>© <script>document.write(new Date().getFullYear())</script> Plats.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-notify::notify />
        @notifyJs
    </body>
</html>
