@extends('web.layouts.event_app')
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>
@section('content')
    @include('web.layouts.event')
    @vite(['resources/js/profile-nft.js'])
    @vite('resources/js/zklogin.js')
   
    <section class="home-top section-padding-100-0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="about-content-text mb-80">
                        <h3>One-Stop Event Solution with Blockchain Technology</h3>
                        <p>More people, more engagement to your event!</p>
                        <a href="#" class="mt-50 pr-10">
                            <img src="{{url('events/icon/icon-ios.svg')}}" />
                        </a>
                        <a href="#" class="mt-50">
                            <img src="{{url('events/icon/icon-android.svg')}}" />
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="about-thumb mb-80">
                        <img src="{{url('events/home/home-banner.png')}}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="event-list pt-10">
        <div class="container">
            <div class="row">
                @if(!empty($eventsPendings))
                    <div class="col-12">

                        <!-- sự kiện bạn tham gia -->
                        @if(!empty(auth()->user()))
                            <div class="section-heading-2 mt-2">
                                <p class="color-white">Your events pending!</p>
                            </div>
                        @else
                            <div class="section-heading-2 mt-2">
                                <p class="color-white">The event is taking place!</p>
                            </div>
                        @endif

                        <div class="tab-content" id="conferScheduleTabContent">
                            <div class="tab-pane fade show active" id="step-one" role="tabpanel" aria-labelledby="monday-tab">
                                <div class="row">
                                    @foreach($eventsPendings as $eventsPending)
                                        <div class="col-12 col-md-6 col-xl-3 item-event">
                                            <div class="single-blog-area style-2">
                                                <div class="single-blog-thumb">
                                                    <a href="{{route('web.events.show', $eventsPending->id)}}">
                                                        <img src="{{$eventsPending->banner_url}}">
                                                    </a>
                                                </div>
                                                <div class="single-blog-text">
                                                    <a class="blog-title" href="{{route('web.events.show', $eventsPending->id)}}">{{$eventsPending->name}}</a>
                                                    <div class="post-meta">
                                                        <a class="post-date" href="{{route('web.events.show', $eventsPending->id)}}">
                                                            <i class="zmdi zmdi-alarm-check"></i> {{ dateFormat($eventsPending->created_at)}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="step-two" role="tabpanel" aria-labelledby="tuesday-tab">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-xl-3 item-event">
                                        <div class="single-blog-area style-2">
                                            <div class="single-blog-thumb">
                                                <a href="#"><img src="{{url('events/event/event-1.png')}}"></a>
                                            </div>
                                            <div class="single-blog-text">
                                                <a class="blog-title" href="#">Street Food Convention</a>
                                                <div class="post-meta">
                                                    <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i> 2023-04-20 21:00:00</a>
                                                </div>
                                                <p>International Curriculum Conference 2023: Vietnam ...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3 item-event">
                                        <div class="single-blog-area style-2">
                                            <div class="single-blog-thumb">
                                                <img src="{{url('events/event/event-2.png')}}">
                                            </div>
                                            <div class="single-blog-text">
                                                <a class="blog-title" href="#">Street Food Convention</a>
                                                <div class="post-meta">
                                                    <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i> 2023-04-20 21:00:00</a>
                                                </div>
                                                <p>International Curriculum Conference 2023: Vietnam ...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3 item-event">
                                        <div class="single-blog-area style-2">
                                            <div class="single-blog-thumb">
                                                <img src="{{url('events/event/event-3.png')}}">
                                            </div>
                                            <div class="single-blog-text">
                                                <a class="blog-title" href="#">Tedx Moscow Conference</a>
                                                <div class="post-meta">
                                                    <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i>2023-04-20 21:00:00</a>
                                                </div>
                                                <p>International Curriculum Conference 2023: Vietnam ...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-3 item-event">
                                        <div class="single-blog-area style-2">
                                            <div class="single-blog-thumb">
                                                <img src="{{url('events/event/event-4.png')}}">
                                            </div>
                                            <div class="single-blog-text">
                                                <a class="blog-title" href="#">Los Angeles Institute</a>
                                                <div class="post-meta">
                                                    <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i>2023-04-20 21:00:00</a>
                                                </div>
                                                <p>International Curriculum Conference 2023: Vietnam ...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <div class="section-heading-2 ">
                        <p class="color-white">Find your event!</p>
                    </div>
                    <div class="schedule-tab">
                        <div class="col-9">
                            <ul class="nav nav-tabs tab-m" id="conferScheduleTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="monday-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step-one" aria-expanded="true">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tuesday-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step-two" aria-expanded="true">Today</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="wednesday-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step-three" aria-expanded="true">This month</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="wednesday-tab" data-toggle="tab" href="#step4" role="tab" aria-controls="step-three" aria-expanded="true">Online</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="wednesday-tab" data-toggle="tab" href="#step5" role="tab" aria-controls="step-three" aria-expanded="true">Offline</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="wednesday-tab" data-toggle="tab" href="#step6" role="tab" aria-controls="step-three" aria-expanded="true">Free events</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content" id="conferScheduleTabContent">
                        <div class="tab-pane fade show active" id="step-one" role="tabpanel" aria-labelledby="monday-tab">
                            <div class="row">
                                @foreach($events as $event)
                                    <div class="col-12 col-md-6 col-xl-3 item-event">
                                        <div class="single-blog-area style-2">
                                            <div class="single-blog-thumb">
                                                <a href="{{route('web.events.show', $event->id)}}"><img src="{{$event->banner_url}}"></a>
                                            </div>
                                            <div class="single-blog-text">
                                                <a class="blog-title" href="{{route('web.events.show', $event->id)}}">{{$event->name}}</a>
                                                <div class="post-meta">
                                                    <a class="post-date" href="{{route('web.events.show', $event->id)}}">
                                                        <i class="zmdi zmdi-alarm-check"></i> {{ dateFormat($event->created_at)}}
                                                    </a>
                                                </div>
                                                {{-- <p>{!! Str::limit($event->description, 50) !!}</p> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="more-schedule-btn text-center mb-80" style="visibility: visible; animation-delay: 300ms; animation-name: fadeInUp;">
                                        <a href="{{route('web.events')}}" class="btn confer-gb-btn">See more</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="step-two" role="tabpanel" aria-labelledby="tuesday-tab">
                            <div class="row">
                                <div class="col-12 col-md-6 col-xl-3 item-event">
                                    <div class="single-blog-area style-2">
                                        <div class="single-blog-thumb">
                                            <a href="#"><img src="{{url('events/event/event-1.png')}}"></a>
                                        </div>
                                        <div class="single-blog-text">
                                            <a class="blog-title" href="#">Street Food Convention</a>
                                            <div class="post-meta">
                                                <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i> 2023-04-20 21:00:00</a>
                                            </div>
                                            <p>International Curriculum Conference 2023: Vietnam ...</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 item-event">
                                    <div class="single-blog-area style-2">
                                        <div class="single-blog-thumb">
                                            <img src="{{url('events/event/event-2.png')}}">
                                        </div>
                                        <div class="single-blog-text">
                                            <a class="blog-title" href="#">Street Food Convention</a>
                                            <div class="post-meta">
                                                <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i> 2023-04-20 21:00:00</a>
                                            </div>
                                            <p>International Curriculum Conference 2023: Vietnam ...</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 item-event">
                                    <div class="single-blog-area style-2">
                                        <div class="single-blog-thumb">
                                            <img src="{{url('events/event/event-3.png')}}">
                                        </div>
                                        <div class="single-blog-text">
                                            <a class="blog-title" href="#">Tedx Moscow Conference</a>
                                            <div class="post-meta">
                                                <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i>2023-04-20 21:00:00</a>
                                            </div>
                                            <p>International Curriculum Conference 2023: Vietnam ...</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3 item-event">
                                    <div class="single-blog-area style-2">
                                        <div class="single-blog-thumb">
                                            <img src="{{url('events/event/event-4.png')}}">
                                        </div>
                                        <div class="single-blog-text">
                                            <a class="blog-title" href="#">Los Angeles Institute</a>
                                            <div class="post-meta">
                                                <a class="post-date" href="#"><i class="zmdi zmdi-alarm-check"></i>2023-04-20 21:00:00</a>
                                            </div>
                                            <p>International Curriculum Conference 2023: Vietnam ...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-speaker-area bg-img section-padding-100-60 bg-our" style="background-image: url({{url('events/bg-event.svg')}});">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="#">Free</a>
                    <h3>Support Program for <br>10 Businesses</h3>
                    <img src="{{url('events/event/text-event.svg')}}">
                    <br>
                    <a class="regis" href="{{route('web.contact')}}">Register now</a>
                </div>
            </div>

        </div>
    </section>

    <section class="our-client-area section-padding-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading-3">
                        <p class="color-black fs-30">What Our Client Are Saying</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="client-area owl-carousel">
                        <div class="single-client-content bg-boxshadow">
                            <div class="single-client-text">
                                <div class="author text-center">
                                    <img src="{{url('events/clients/cl-01.svg')}}">
                                    <h3>Mr. Huong Pham</h3>
                                    <p>Founder of GFI Ventures</p>
                                </div>
                                <p class="text-center">“Our partnership with Plats Network was very smooth and effortless. We were very pleased with the end result. The software made our registration process easier, more accurate and we were able to gather more reliable data about our event. We also can engage more with our community. All-in-one platform saves us time and man-power to organize an events, even large or complex events.</p>
                            </div>
                        </div>
                        <div class="single-client-content bg-boxshadow">
                            <div class="single-client-text">
                                <div class="author text-center">
                                    <img src="{{url('events/clients/cl-02.svg')}}">
                                    <h3>Ms. Nagomi Mai</h3>
                                    <p>Sales Director of Vaix Vietnam</p>
                                </div>
                                <p class="text-center">We are outsourcing-service company based in Hanoi. We have organized many annual company events. Plats Network really helps us a lot in preparing and organizing these events.</p>
                            </div>
                        </div>
                        <div class="single-client-content bg-boxshadow">
                            <div class="single-client-text">
                                <div class="author text-center">
                                    <img src="{{url('events/clients/cl-03.svg')}}">
                                    <h3>Ms. Ha Ngoc</h3>
                                    <p>Head of Media - V2B Labs</p>
                                </div>
                                <p class="text-center">We have many nation-wide and global technology partners and really need a solution to organize our events smoothly and professionally. Plats Network Event Solution is an effective platform to save us time and reduce manpower for event organization. Plats Network is especially effective for crypto events.</p>
                            </div>
                        </div>
                        <div class="single-client-content bg-boxshadow">
                            <div class="single-client-text">
                                <div class="author text-center">
                                    <img src="{{url('events/clients/cl-04.svg')}}">
                                    <h3>Mr. Hoang Ngoc Gia Long</h3>
                                    <p>President of Keller Williams Vietnam</p>
                                </div>
                                <p class="text-center">I was introduced to Plats Network Event Solution by a friend. We have received great support from the team. The platform is very easy to use for non-tech people like us. Our event went well and our guests were having really exciting time, especially the minigame. I am very satisfied with the service and definitely want to use Plats Network for our future events.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-sponsor-client-area section-padding-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading-2">
                        <p>Follow the Organizers</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="our-sponsor-area d-flex flex-wrap">
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-01.svg')}}">
                            <h5>GFI Ventures</h5>
                            <p>16,734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-02.svg')}}">
                            <h5>Vaix Vietnam</h5>
                            <p>16,734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-03.svg')}}">
                            <h5>V2B Labs</h5>
                            <p>16734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-04.svg')}}">
                            <h5>Keller Williams Vietnam</h5>
                            <p>16734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-05.svg')}}">
                            <h5>Coin98</h5>
                            <p>16734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-06.svg')}}">
                            <h5>Kyber Network</h5>
                            <p>16734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-07.svg')}}">
                            <h5>BlockX Network</h5>
                            <p>16734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                        <div class="single-sponsor">
                            <img src="{{url('events/clients/client-08.svg')}}">
                            <h5>K300 Ventures</h5>
                            <p>16734 followers</p>
                            <a href="#">Follow</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input value="{{auth()->user() != null ? auth()->user()->email : ''}}" hidden id="email_nft">

    @include('web.layouts.subscribe')
@endsection
@push('custom-scripts')
    <script src="{{ url('js/index.umd.js') }}"></script>
    <script src="https://auth.magic.link/sdk"></script>
    <script type="text/javascript" src="https://auth.magic.link/sdk/extension/solana"></script>
@endpush
