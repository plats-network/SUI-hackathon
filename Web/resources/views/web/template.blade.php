@extends('web.layouts.event_app')

@section('content')
    @include('web.layouts.event')

    <section class="home-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="home-content">
                        <h3>Effortless Event Management with Customizable Templates</h3>
                        <p>Plats Network provides you best-in-class customizable templates to power your events with data insights, built-in intelligence, and next-generation payment tools.</p>
                        <a href="{{route('web.contact')}}" class="mt-50">Contact Sales</a>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="about-thumb mb-80" >
                        <img src="{{url('events/img-temp.svg')}}" alt="Effortless Event Management with Customizable Templates">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-top temp-color">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-12">
                    <div class="home-content pb-85">
                        <h3 class="h3-color">Template for Traditional Events</h3>
                        <p class="tmp-color">There are many types of traditional events such as conference, workshops, grand openingsEasily manage every step of your workflow with an all-in-one event management platform. We provide a seamless online experience for your attendees—from mobile ticketing via email or mobile application,  check-in management to post-event analytics and everything in between. Here are our suggestion </p>

                        <ul>
                            <li>
                                <img src="{{url('events/icon/icon-1.svg')}}">
                                (1) Register by email
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-2.svg')}}">
                                (2) Visit the website
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-3.svg')}}">
                                (3) Like, share, follow fanpage/ tag, comment on posts
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-4.svg')}}">
                                (4) Check-in at event’s location (using GPS)
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-5.svg')}}">
                                (5) Scan QR Codes of sessions or booths
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-6.svg')}}">
                                (6) Spin lucky number/ Receive lucky number
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-7.svg')}}">
                                (7) Join lucky draw minigames or Quiz Games 
                            </li>
                            <li>
                                <img src="{{url('events/icon/icon-8.svg')}}">
                                (8) Post-event reports/statistics
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-us-countdown-area section-padding-100-0 pd-m">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-12">
                    <div class="home-content pb-85">
                        <h3>Template for Crypto Events</h3>
                        <p>TPlats Network is specially built for blockchain events, where VCs, projects, builders network with each other and promote their products/services. Projects can use their own products/ tokens to reward their users. All the rewards are paid automatically and transparently using smart contract.</p>
                        <ul class="tmp-color">
                            <li><img src="{{url('events/icon/icon-9.svg')}}" alt="icon">(1) Join Telegram Group</li>
                            <li><img src="{{url('events/icon/icon-10.svg')}}" alt="icon">(2) Join Discord server</li>
                            <li><img src="{{url('events/icon/icon-11.svg')}}" alt="icon">(3) Like, retweet, tweet, hashtag, tag on Twitter</li>
                            <li><img src="{{url('events/icon/icon-12.svg')}}" alt="icon">(4) Like, share, follow fan page/ tag, comment on posts</li>
                            <li><img src="{{url('events/icon/icon-13.svg')}}" alt="icon">(5) Purchase/Book event ticket using token/coin</li>
                            <li><img src="{{url('events/icon/icon-14.svg')}}" alt="icon">(6) Check-in at event’s location (using GPS)</li>
                            <li><img src="{{url('events/icon/icon-15.svg')}}" alt="icon">(7) Scan QR Codes of sessions or booths</li>
                            <li><img src="{{url('events/icon/icon-16.svg')}}" alt="icon">(8) Spin lucky number/ Receive lucky number</li>
                            <li><img src="{{url('events/icon/icon-17.svg')}}" alt="icon">(9) Join built-in Lucky Draw Game or Quiz Game (only for eligible attendees)</li>
                            <li><img src="{{url('events/icon/icon-18.svg')}}" alt="icon">(10) Automatic reward payment to winner’s wallet address (token/coin)</li>
                            <li><img src="{{url('events/icon/icon-19.svg')}}" alt="icon">(11) Post-event reports</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-top bg-img bg-overlay pd-m" style="background-image: url('events/bg-temp.svg');">
        <div class="container pb-80">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="home-content">
                        <h3 class="h3-color">Hosting a large or complex event?</h3>
                        <p class="tmp-color">We offer tailored packages for you event. Get customized fee schedule, personalized support, and more individualized features by partnering with our sales team.</p>
                        <a href="{{route('web.contact')}}" class="mt-50">Contact Sales</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="about-thumb mb-80">
                        <img src="{{url('events/contact.svg')}}" alt="Contact Sales">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.layouts.subscribe')
@endsection
