@php
    $name = auth()->user()->name ?: 'client';
    $email = auth()->user()->email ?: 'client@gmail.com';
    $avatar = imgAvatar(auth()->user()->avatar_path);
@endphp

<header id="page-topbar" class="isvertical-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box">
                <a href="{{url('/')}}" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="30">
                    </span>
                    <span class="logo-sm">
                        <img src="assets/images/logo-light-sm.png" alt="" height="26">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                <i class="bx bx-menu align-middle"></i>
            </button>

            @yield('name_page')
        </div>
        <div class="d-flex">
            {{--  <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-search icon-sm align-middle"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <form class="p-2">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" class="form-control rounded bg-light border-0" placeholder="Search...">
                                <i class="bx bx-search search-icon"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>  --}}
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown-v"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{$avatar}}"
                    alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15">{{$name}}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">{{$name}}</h6>
                        <p class="mb-0 font-size-11 text-muted">{{$email}}</p>
                    </div>
                    <a class="dropdown-item" href="{{route('cws.setting')}}">
                        <i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-2"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('cws.logout')}}">
                        <i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i>
                        <span class="align-middle">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
