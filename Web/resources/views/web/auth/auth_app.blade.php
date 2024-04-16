<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        {!! seo() !!}
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        @notifyCss
        @vite(['resources/sass/event-auth.scss'])

        <!-- Add icon library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <style>
            /* Style all font awesome icons */
            .fa {
                padding: 20px;
                font-size: 30px;
                width: 72px;
                text-align: center;
                border-radius: 50%;
                text-decoration: none;
                margin: 5px 2px;
            }

            /* Add a hover effect if you want */
            .fa:hover {
                opacity: 0.7;
            }

            /* Set a specific color for each brand */

            /* Facebook */
            .fa-facebook {
                background: #3B5998;
                color: white;
            }

            /* Twitter */
            .fa-twitter {
                background: #55ACEE;
                color: white;
            }

            .fa-google {
                background: #dd4b39;
                color: white;
            }
        </style>
    </head>
    <body>
        <div id="auth" class="row justify-content-center">
            <div class="col-md-12">
                <p class="text-center">
{{--                    <a href="{{url('/')}}" class="text-center home home-back">Back to Home</a>--}}
                </p>
                @yield('content')
            </div>
        </div>

        <x-notify::notify />
        @notifyJs
    </body>
</html>
