<footer class="footer-area bg-img bg-overlay-2 pt-50">
    <div class="main-footer-area">
        <div class="container">
            <div class="row">
                <div class="col-12 mt-5">
                    <a href="{{url('/')}}" class="footer-logo">
                        <img src="{{url('events/logo-plats.svg')}}" alt="Event Plats">
                    </a>
                </div>
            </div>
            <div class="row">
                @if($version !=2)
                <div class="col-12 col-sm-6 col-lg-3 menu-item">
                    <div class="single-footer-widget mb-60">
                        <h5 class="widget-title">Organize Events</h5>
                        <ul class="footer-nav">
                            <li><a href="https://{{config('plats.cws')}}" target="_blank">Create events</a></li>
                            <li><a href="{{route('web.pricing')}}">Pricing</a></li>
                            <li><a href="{{route('web.template')}}">Templates</a></li>
                            <li><a href="{{route('web.resource')}}">Resources</a></li>
                            <li><a href="{{route('web.contact')}}">Content Standards</a></li>
                            <li><a href="#">Guidelines</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 menu-item">
                    <div class="single-footer-widget mb-60">
                        <h5 class="widget-title">Solutions</h5>
                        <ul class="footer-nav">
                            <li><a href="{{route('web.solution')}}">Sell you tickets online</a></li>
                            <li><a href="{{route('web.solution')}}">Crypto events</a></li>
                            <li><a href="{{route('web.solution')}}">Crypto payment</a></li>
                            <li><a href="{{route('web.solution')}}">QR Code Setup</a></li>
                            <li><a href="{{route('web.solution')}}">Download App</a></li>
                            <li><a href="{{route('web.solution')}}">Loyal Membership Card</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 menu-item">
                    <div class="single-footer-widget mb-60">
                        <h5 class="widget-title">Search Events</h5>
                        <ul class="footer-nav">
                            <li><a href="{{url('/')}}">All events</a></li>
                            <li><a href="{{url('/')}}">Free events</a></li>
                            <li><a href="{{url('/')}}">Offline events</a></li>
                            <li><a href="{{url('/')}}">Online events</a></li>
                            <li><a href="{{url('/')}}">Upcoming events</a></li>
                            <li><a href="{{url('/')}}">Search by location</a></li>

                        </ul>
                    </div>
                </div>
                @endif

                <div class="col-12 col-sm-6 col-lg-3 menu-item">
                    <div class="single-footer-widget mb-60">
                        <h5 class="widget-title">Connect us</h5>
                        <ul class="footer-nav">
                            <li><a href="https://www.facebook.com/platsnetwork">Facebook</a></li>
                            <li><a href="https://www.linkedin.com/in/plats-network/">Linkedin</a></li>
                            <li><a href="https://twitter.com/plats_network">Twitter</a></li>
                            <li><a href="https://t.me/platsnetworkofficial">Telegram</a></li>
                            <li><a href="{{route('web.contact')}}">Contact Support</a></li>
                            <li><a href="{{route('web.contact')}}">Contact Sales</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="copywrite-content">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="copywrite-text">
                        <p class="text-center">Copyrights @Plats Network 2023 | All right reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</footer>
