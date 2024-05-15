<style>
    .sp-hidden {
        {{--  position: absolute; 
        top: 0px; 
        left: 0px; 
        width: 420px; 
        height: 93px; 
        padding: 0 12px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-family: Lexend; 
        font-size: 40px; 
        line-height: 56px; 
        font-weight: 400; 
        color: #FFFFFFFF;
        background: #00659FFF; 
        opacity: 1; 
        border: none; 
        border-radius: 8px;  --}}
    }
    .text-white{
        color: #FFFFFFFF !important;
    }
    .create-event{
        border-radius: 5px;
        color: #FFFFFFFF;
        background: #00659FFF; 
    }
    .user-name{
        border-radius: 5px;
        background: #5dbff6;
        color: #FFFFFFFF;
    }
    .user-name a{
        color: #131314 !important;
    }
</style>
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
                                <li class="sp-hidden create-event">
                                    <a class="text-white" href="{{ route('cws.eventList') }}">Create Event</a>
                                </li>
                                <li class="sp-hidden user-name">
                                    <a href="#">
                                        {{Str::limit(auth()->user()->name, 15)}}
                                    </a>
                                </li>
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
                                            <a href="{{route('web.logout')}}">Logout</a>
                                        </div>
                                    </div>
                                </li>
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
                            
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
