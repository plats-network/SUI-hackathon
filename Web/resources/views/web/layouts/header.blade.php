<header class="header-area">
    <div class="classy-nav-container breakpoint-off">
        <div class="container">
            <nav class="classy-navbar justify-content-between" id="conferNav">
                <a class="nav-brand" href="{{url('/')}}">
                    <img src="{{url('events/logo-event.svg')}}" alt="">
                    {{-- <div class="lang">
                        <a href="{{route('web.lang', ['lang' => lang() == 'en' ? 'vi' : 'en'])}}">
                            <img src="{{url('/')}}/events/{{lang()}}.png">
                        </a>
                    </div> --}}
                </a>
                <div class="classy-navbar-toggler">
                    <span class="navbarToggler">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
                <div class="classy-menu">
                    <div class="classycloseIcon">
                        <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                    </div>
                    <div class="classynav">
                        <ul id="nav">
                            @if (auth()->check() && auth()->user()->role == GUEST_ROLE)
                                <li class="sp-hidden">Hi! {{auth()->user()->name}}</li>
                            @else
                                <li class="sp-hidden"><a href="https://{{config('plats.cws')}}">Create Event</a></li>
                            @endif
                            @if($version !=2)
                                <li class="{{request()->is('solution') ? 'active' : ''}}">
                                    <a href="{{route('web.solution')}}">Solutions</a>
                                </li>
                                <li class="{{request()->is('template') ? 'active' : ''}}">
                                    <a href="{{route('web.template')}}">Templates</a>
                                </li>
                                <li class="{{request()->is('pricing') ? 'active' : ''}}">
                                    <a href="{{route('web.pricing')}}">Pricing</a>
                                </li>
                                <li class="{{request()->is('resource') ? 'active' : ''}}">
                                    <a href="{{route('web.resource')}}">Resources</a>
                                </li>
                                <li class="border-r {{request()->is('contact') ? 'active' : ''}}">
                                    <a href="{{route('web.contact')}}">Contact</a>
                                </li>
                            @endif
                            @if (auth()->guest())
                                {{--                                <li>--}}
                                {{--                                    <a href="{{route('web.formLogin')}}">Sign In</a>--}}
                                {{--                                </li>--}}
                                {{--                                <li class="btn-signup">--}}
                                {{--                                    <a class="btn btn-info" href="{{route('web.formLogin')}}">Sign Up for Free</a>--}}
                                {{--                                </li>--}}
                            @else
                                <li class="pl-5">
                                    @php
                                        $avatar = optional(auth()->user())->avatar_path;
                                    @endphp
                                    <div class="dropdown">
                                        <button id="info"
                                                class="dropbtn"
                                                style="
                                            background-image: url({{imgAvatar($avatar)}});
                                            background-position: center;
                                            background-size: contain;"
                                        ></button>
                                        <div id="e-menu" class="dropdown-content">
                                            <a href="{{route('web.profile')}}">Profile</a>
                                            <a href="#" class="wallet-user" style="display: none!important;">Wallet</a>
                                            {{-- <a href="#">Link 2</a> --}}
                                            <a href="{{route('web.logout')}}">Logout</a>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
