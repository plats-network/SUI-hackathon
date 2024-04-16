@extends('web.layouts.event_app')

@section('content')
    @include('web.layouts.event')

    <section class="welcome-area">
        <div class="welcome-slides owl-carousel">
            <div class="single-welcome-slide bg-img bg-overlay jarallax" style="background-image: url(events/solutions/solution.svg);">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text">
                                <h2>Power Your Events with state-of-the-art Technologies</h2>
                                <h6>Explore all the built-in tools you need to create, setup and evaluate your business with events.</h6>
                                <div class="hero-btn-group">
                                    <a href="{{route('web.contact')}}" class="btn confer-btn">Contact Sales <i class="zmdi zmdi-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="icon-scroll" id="scrollDown"></div> --}}
    </section>

    <section class="confer-blog-details-area section-padding-100-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-12 col-xl-12">
                    <div class="pr-lg-4 mb-100">
                        <div class="post-details-content">
                            <h4 class="post-title">Auto Invitation Card Creator</h4>
                            <p>Instead of taking many times and efforts to design your event’s poster or invitation card for each VIP guest or speaker,  Plats Network offer you a wide range of templates available for you to choose.</p>
                            <!-- img -->
                            <table class="w-100">
                                <tr>
                                    <td>
                                        <img src="{{url('events/solutions/sol-1.svg')}}">
                                    </td>
                                    <td rowspan="2">
                                        <img class="img-01" src="{{url('events/solutions/sol-3.svg')}}">
                                    </td>
                                    <td><img src="{{url('events/solutions/sol-4.svg')}}"></td>
                                </tr>
                                <tr>
                                    <td><img src="{{url('events/solutions/sol-2.svg')}}"></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6">
                                                <img src="{{url('events/solutions/sol-5.svg')}}">
                                            </div>
                                            <div class="col-6">
                                                <img src="{{url('events/solutions/sol-6.svg')}}">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p>All you have to do is to upload your guest list data with clear information, the system will generate the invitation card for each guest and automatically send the invitation email with the attachment to them. You can save a lot of time and expenses for this work.</p>

                            <h4 class="post-title">QR Code Setup for each Session/Booth</h4>
                            <p>There are different discussing sessions in an events. Event organizers always want to keep the audience focused on the content of the discussion. Speakers do not want to see that only few number of audience are interested in their session. Plats Network Event Solution is the perfect choice for you.</p>
                            <img src="{{url('events/solutions/solution-01.svg')}}" alt="QR Code Setup for each Session/Booth">
                            <!-- img -->
                            <p>You can generate the QR Code for each session or each booth in the event and then just attach these codes to your presentation or print them out to use for your booths. Also, you can set up the conditions of audience to be eligible to join your minigames. For example, you may request audience to scan at least 6/8 QR Codes in order to receive their lucky number. The system will generate a random number and send it to users’ mobile application.</p>
                            <p>All the setups are customizable. It is very easy to use and event organizer does not have to be tech-savvy to master the system. Instead of taking up many of your human resources, you now only need one staff to do these works.</p>
                            <h4 class="post-title">Built-in Minigames</h4>
                            <p>At the end of the events, all eligible users will receive a random number. All the numbers are automatically inputted into the system to carry out the minigames. You do not need to use any other application to do the minigame. It is all set in Plats Network Event Solution. Our minigames such as Lucky Draw Game or Quiz will bring your audience a wonderful experiences and save you a lot of effort.</p>
                            <img src="{{url('events/solutions/solution-02.svg')}}" alt="Built-in Minigames">
                            <h4 class="post-title">Post-event Data</h4>
                            <p>After the event, you can extract the data/statistics about your audiences. It will help you evaluate the effectiveness of your event, acknowledge your audience’s thoughts, hobbies, habits  to build your future strategies.</p>
                            <img src="{{url('events/solutions/solution-03.svg')}}" alt="Built-in Minigames">
                            <h4 class="post-title">Automatic Ticketing and Rewarding for Crypto Events</h4>
                            <p>We have powerful solution for crypto event including ticketing and rewarding system using coin/token as payment method. Organizer need to deposit token/coin to the smart contract, set the number of winners, conditions and others. After the minigame, the the token/coin will be sent automatically to the winner’s wallet address. With blockchain technology, everything will be carried out automatically and transparently.</p>
                            <p>The system also give organizers many available tools to sell their event tickets by token/coin.</p>
                            <img src="{{url('events/solutions/solution-04.svg')}}" alt="Automatic Ticketing and Rewarding for Crypto Events">
                            <!-- img -->
                            <h4 class="post-title">Mobile Application for Users</h4>
                            <p>User can download our mobile application from Google Play or App Store easily. With excellent UI/UX design, the mobile app will help you check-in the event location, perform the QR Code scan or do other required tasks easily. If you are eligible, a random number will be send to your app. If you are lucky, there will be notification that you are the winner of the minigame. It is very convenient for you and event organizers.</p>
                            <img src="{{url('events/solutions/solution-05.svg')}}" alt="Mobile Application for Users">
                            <h4 class="post-title">Loyalty Membership Card</h4>
                            <p>Event organizers can use their own membership cards as the ticket to their events. The owners of our Loyalty Membership Card will have exclusive rights in the events for example the right to join the minigames.</p>
                            <img src="{{url('events/solutions/solution-06.svg')}}" alt="Loyalty Membership Card">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.layouts.subscribe')
@endsection
