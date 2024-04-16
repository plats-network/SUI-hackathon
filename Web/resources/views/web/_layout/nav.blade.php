<div class="container nav-content d-flex">
    <!-- Logo Start -->
    <div class="logo position-relative">
        <a href="{{url('/')}}">
            <img style="width: 200px!important;" src="{{ asset('img/admin/logo-event.png') }}" alt="logo" />
        </a>
    </div>
    <!-- Logo End -->

    <!-- User Menu Start -->
    @if(empty(auth()->user()->name))
        <div class="user-container d-flex ">
            <a href="{{ route(LOGIN_WEB_ROUTE) }}" class="d-flex position-relative" style="margin-right: 15px">
                Log In
            </a>
            <a href="{{ route('web.client.showSignup') }}" class="d-flex position-relative" >
                Sign Up
            </a>
        </div>

    @else
        <div class="user-container d-flex">
            <a href="#" class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="profile" alt="profile" src="{{ asset('img/admin/profile/profile-starbucks.jpg') }}" />
                <div class="name">{{ (auth()->user())->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-end user-menu wide">
                <div class="row mb-3 ms-0 me-0">
                    <div class="col-12 ps-1 mb-2">
                        <div class="text-extra-small text-primary">ACCOUNT</div>
                    </div>
                    <div class="col-md-12 ps-1 pe-1">
                        <ul class="list-unstyled">
                            <li>
                                <a href="/events/likes">Likes</a>
                            </li>
                            <li>
                                <a href="/discord">Linked account</a>
                            </li>
{{--                            <li>--}}
{{--                                <a href="/events/history">Histories</a>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                </div>
                <div class="row mb-1 ms-0 me-0">
                    <div class="col-12 p-1 mb-3 pt-3">
                        <div class="separator-light"></div>
                    </div>
                    <div class="col-6 pe-1 ps-1">
                        <ul class="list-unstyled">
                            <li>
                                <a href="{{ route('web.logout') }}">
                                    <i data-acorn-icon="logout" class="me-2" data-acorn-size="17"></i>
                                    <span class="align-middle">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
<!--    login thanh cong-->

    <!-- User Menu End -->

    <!-- Icons Menu Start -->
    <ul class="list-unstyled list-inline text-center menu-icons">
{{--        <li class="list-inline-item">--}}
{{--            <div class="mb-3 filled">--}}
{{--                <i data-acorn-icon="search" data-acorn-size="18"></i>--}}
{{--                <input class="form-control" placeholder="Email" name="loginEmail">--}}
{{--            </div>--}}
{{--            <a href="#">--}}
{{--                <i data-acorn-icon="search" data-acorn-size="18"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
    <!-- Icons Menu End -->

    <!-- Menu Start -->
    <div class="menu-container flex-grow-1">
        <ul id="menu" class="menu">
            <li style="width: 100%;margin-top: 15px">
                <div class="mb-3 filled">
                    <i data-acorn-icon="search" data-acorn-size="18"></i>
                    <input class="form-control" style="border-radius:20px" placeholder="" name="loginEmail">
                </div>
            </li>
{{--            <li>--}}
{{--                <a href="#">--}}
{{--                    <i data-acorn-icon="trend-up" class="icon" data-acorn-size="18"></i>--}}
{{--                    <span class="label">Upgrade</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a href="#">--}}
{{--                    <i data-acorn-icon="messages" class="icon" data-acorn-size="18"></i>--}}
{{--                    <span class="label">Community</span>--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
    </div>
    <!-- Menu End -->

    <!-- Mobile Buttons Start -->
    <div class="mobile-buttons-container">
        <!-- Menu Button Start -->
        @if(!empty(auth()->user()->name))
{{--            <div class="file-input" style="padding: 12px">--}}
{{--                <input style="display: none"--}}
{{--                       type="file"--}}
{{--                       name="file-input"--}}
{{--                       id="file-input"--}}
{{--                       class="file-input__input"--}}
{{--                />--}}
{{--                <label class="file-input__label" for="file-input">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16"> <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z"/> <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z"/> <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z"/> <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z"/> <path d="M12 9h2V8h-2v1Z"/> </svg>--}}
{{--                </label--}}
{{--                >--}}
{{--            </div>--}}
        @endif
        <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
        </a>
        <!-- Menu Button End -->
    </div>
    <!-- Mobile Buttons End -->
</div>
<div class="nav-shadow"></div>
