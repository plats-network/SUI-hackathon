@extends('web.layouts.event_app')

@section('content')
    @include('web.layouts.event')

    <section class="home-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="home-content">
                        <h3>Become a professional event-hosting entrepreneur</h3>
                        <p>Plats gives you all the built-in tools to easily create and manage your event in a simple and intuitive dashboard.</p>
                        <a href="{{route('web.contact')}}" class="mt-50">Try it now</a>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="about-thumb mb-80" >
                        <img src="{{url('events/bg-resource.svg')}}" alt="Become a professional event-hosting entrepreneur">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-top resource">
        <div class="container pb-80">
            <div class="row align-items-center">
                <div class="col-12">
                    <a href="">Tools and Features</a>
                    <h3>In-depth solution to boost your event engagement</h3>
                    <p>Plats Network provides in-depth solution to help organizers increase the participation level of the audience throughout the event. We incorporate a mix of activities to help maintain strong levels of engagement, especially if your event is fairly lengthy.</p>
                    <p>We have mobile app for users which no event platform has. It will be incredibly helpful for improving and measuring attendee engagement.</p>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{url('events/resources/resource-1.svg')}}" alt="Tools and Features">
                </div>
                <div class="col-md-6">
                    <img src="{{url('events/resources/resource-2.svg')}}" alt="Tools and Features">
                </div>
            </div>
        </div>
    </section>

    <section class="home-top resource bg-white">
        <div class="container pb-80">
            <div class="row align-items-center">
                <div class="col-12">
                    <a href="#">Event Planning</a>
                    <h3 class="h3">Effortless event preparation.</h3>
                    <p class="p">Our all-in-one event solution will help you reduce the time, cost and effort to plan and prepare your event. You just need to register to get the full access to our Client Web Service (CWS) where you can customize and set up you event easily. We also have technical support team to support you anytime.</p>
                    <img src="{{url('events/resources/resource-3.svg')}}" class="img-4" alt="Event Planning">
                </div>
            </div>
        </div>
    </section>

    <section class="home-top resource">
        <div class="container pb-80">
            <div class="row align-items-center">
                <div class="col-12">
                    <a href="">Crypto Payment</a>
                    <h3>Why crypto payment for your event?</h3>
                    <p>Set up a cashless payment system to get access to a new revenue stream. Handle payments and let your customers pay with crypto. Here are reason why you should accept crypto payment:</p>
                    <p class="text-center">
                        <img src="{{url('events/resources/resource-4.svg')}}" class="img-step" alt="Crypto Payment">
                    </p>
                    <p class="text-center pt-40">
                        <a class="text-center" href="{{route('web.contact')}}">Contact Us</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('web.layouts.subscribe')
@endsection
