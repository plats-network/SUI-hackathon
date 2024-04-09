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



                <li class="{{$menuUser ? 'mm-active' : ''}}">
                    <a href="{{route('cws.users')}}" class="{{$menuUser ? 'active' : ''}}">
                        <i class="bx bx-calendar-event icon nav-icon"></i>
                        <span class="menu-item" data-key="t-calendar">Users</span>
                    </a>
                </li>

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


                <li class="{{$travelGame ? 'mm-active' : ''}}">
                    <a href="{{route('cws.travelGames')}}" class="{{$travelGame ? 'active' : ''}}">
                        <i class="bx bx-check-square icon nav-icon"></i>
                        <span class="menu-item" data-key="t-todo">Travel Games</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    var _token = $('meta[name="csrf-token"]').attr('content');
</script>
