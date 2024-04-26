@php
    $path = request()->segment(1);
    $menuUser = in_array($path, ['users']);
    $e = 'event-';
    $menuEvent = in_array($path, [$e.'list', $e.'preview', $e.'users', $e.'edit', $e.'create']);
    $travelGame = in_array($path, ['travel-games']);
    //Login user
    $user = \Illuminate\Support\Facades\Auth::user();

    $events = App\Models\Task::where('type', '=', EVENT)
    ->where('creator_id',  $user? $user->id: '')
    ->limit(5)
    ->orderBy('created_at', 'desc')
    ->get();
@endphp

<div class="vertical-menu">
    <div class="navbar-brand-box">
        <a href="{{url('/')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('imgs/logo.svg')}}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{asset('imgs/logo-light-blue.svg')}}" alt="" height="28" style="width: 135px;">
            </span>
        </a>
    </div>
    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
        <i class="bx bx-menu align-middle"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">

            <ul class="metismenu list-unstyled mt-3" id="side-menu">
                {{-- <li class="menu-title" data-key="t-menu">Dashboard</li> --}}
                {{--<li>
                   <a href="javascript: void(0);">
                       <i class="bx bx-home-alt icon nav-icon"></i>
                       <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                       <span class="badge rounded-pill bg-primary">2</span>
                   </a>
                   <ul class="sub-menu" aria-expanded="false">
                       <li><a href="index.html" data-key="t-ecommerce">Ecommerce</a></li>
                       <li><a href="dashboard-sales.html" data-key="t-sales">Sales</a></li>
                   </ul>
               </li>
                <li class="menu-title" data-key="t-applications">Applications</li>--}}


                <li class="{{request()->route()->getName() == 'cws.dashboard' ? 'mm-active' : ''}}">
                    <a href="{{route('cws.dashboard')}}"
                       class="{{request()->route()->getName() == 'cws.dashboard' ? 'active' : ''}}">
                        <svg class="icon nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                             fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
                            <path fill-rule="evenodd"
                                  d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3"/>
                        </svg>
                        <span class="menu-item" data-key="t-calendar">Dashboard</span>
                    </a>
                </li>
                @php
                    if (auth()->user()->role == ADMIN_ROLE) {
                @endphp
                <li class="{{$menuUser ? 'mm-active' : ''}}">
                    <a href="{{route('cws.users')}}" class="{{$menuUser ? 'active' : ''}}">
                        <i class="bx bx-calendar-event icon nav-icon"></i>
                        <span class="menu-item" data-key="t-calendar">Users</span>
                    </a>
                </li>
                @php
                    }
                @endphp
                <li class="{{$menuEvent ? 'mm-active' : ''}}">
                    <a href="{{route('cws.eventList')}}" class="{{$menuEvent ? 'active' : ''}}">
                        <i class="bx bx-check-square icon nav-icon"></i>
                        <span class="menu-item" data-key="t-todo">Events</span>
                        {{-- <span class="badge rounded-pill bg-success" data-key="t-new">New</span> --}}
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        @foreach($events as $event)
                            <li><a href="{{route('cws.eventPreview', [
                                                        'id' => $event->id,
                                                        'tab' => 0,
                                                        'preview' => 1
                                                    ])}}" data-key="t-read-email">{{$event->name}}</a></li>
                        @endforeach
                    </ul>
                </li>


                <!--    <li class="{{$travelGame ? 'mm-active' : ''}}">
                    <a href="{{route('cws.travelGames')}}" class="{{$travelGame ? 'active' : ''}}">
                        <i class="bx bx-check-square icon nav-icon"></i>
                        <span class="menu-item" data-key="t-todo">Travel Games</span>
                    </a>
                </li>
            -->
            </ul>

        </div>
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled mt-3" id="side-menu">
                <li>
                    <a href="{{route('cws.setting')}}">
                        <i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-2"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('cws.setting')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                             class="bi bi-gear  font-size-16 align-middle me-2" viewBox="0 0 16 16">
                            <path
                                d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path
                                d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg>
                        <span class="align-middle">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('cws.logout')}}">
                        <i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i>
                        <span class="align-middle">Sign out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    var _token = $('meta[name="csrf-token"]').attr('content');
</script>
