@extends('web.layouts.event_app')

@section('content')
    @include('web.layouts.event')

    <section class="home-top pb230">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="home-content">
                        <h3 class="pb-40">Join Our Community to grow Your Community!</h3>
                        <p class="text-danger">Join Our Community to grow Your Community !</p>
                        <a href="{{route('web.contact')}}" class="mt-50">Register now</a>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="about-thumb mb-80" >
                        <img src="{{url('events/chart.svg')}}" alt="Register now">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing">
        <div class="container container-pricing">
            <div class="row justify-content-between">
                <div class="col-12 col-lg-12 text-center">
                    <h3>Organize your event online !</h3>
                    <p>Start organizing your event online free up to 100 attendees per event.</p>
                    <p>When you are ready to upgrade – you just need to pay a fixed subscription fee, not a percentage of your sales.</p>
                    <p class="pb-20">Cancel subscription at any time you want.</p>
                </div>
            </div>

            <div class="row plan-pricing justify-content-between text-center">
                <div class="col-4 prm">
                    <h3>Starter Plan</h3>
                    <ul>
                        <li>Receive payments instantly</li>
                        <li>Unlimited QR Code setup</li>
                        <li>QR Code Scan - up to 100 attendees</li>
                        <li>Free Minigame - up to 100 attendees</li>
                    </ul>
                    <p class="price">FREE</p>
                    <a class="tbn-pricing" href="#">Start now</a>
                </div>
                <div class="col-4 prm">
                    <h3>Standard Plan</h3>
                    <ul>
                        <li>Sell ticket online (crypto payment)</li>
                        <li>Receive payments instantly</li>
                        <li>Unlimited QR Code setup</li>
                        <li>QR Code Scan - up to 1500 attendees</li>
                        <li>Minigame - up to 1000 attendees</li>
                    </ul>
                    <p class="price">168$/Month</p>
                    <a class="tbn-pricing" href="#">Start now</a>
                </div>
                <div class="col-4 prm">
                    <h3>Flexible Plan</h3>
                    <ul>
                        <li>Sell ticket online (crypto payment)</li>
                        <li>Sell ticket online (crypto payment)</li>
                        <li>Unlimited QR Code setup</li>
                        <li>QR Code Scan - up to 5000 attendees</li>
                        <li>Minigame - up to 2000 attendees</li>
                    </ul>
                    <p class="price">Custom Pricing</p>
                    <a class="tbn-pricing" href="#">Start now</a>
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
                        <img src="{{url('events/contact.svg')}}" alt="Hosting a large or complex event?">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.layouts.subscribe')
@endsection
