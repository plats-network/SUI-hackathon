@extends('web.layouts.event_app')

@section('content')
    <section class="home-top bg-contact pb-110">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="contact-content">
                        <h3 class="pb-40">Contact us for more support</h3>
                        <p>We’d love to help you get the most out of Plats Network. Submit the form below, and a member of our sales team will contact you shortly.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="about-thumb mb-80" >
                        <img src="{{url('events/icon/contact-icon.svg')}}" alt="Contact us for more support">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-schedule-area bg-white section-padding-100">
        <h3>Ready to get started with Plats Network?</h3>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="schedule-tab">
                        <ul class="nav nav-tabs" id="conferScheduleTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="monday-tab" data-toggle="tab" href="#step-one" role="tab" aria-controls="step-one" aria-expanded="true">Contact our sales team</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tuesday-tab" data-toggle="tab" href="#step-two" role="tab" aria-controls="step-two" aria-expanded="true">Contact our support team</a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content" id="conferScheduleTabContent">
                        <div class="tab-pane fade show active" id="step-one" role="tabpanel" aria-labelledby="monday-tab">
                            <div class="contact-form">
                                <form action="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Khanh Phuong" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your email <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="info@plats.network" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your phone number</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="+84 123 456 789">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Company</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Plats Network">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your role</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Marketing Manager">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">What kind of event are you going to organize?</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Your Number">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">How many attendees are you expected?</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="300">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your message <span class="text-danger">*</span></label>
                                                    <textarea name="message" class="form-control" id="message" cols="80" rows="15" placeholder="Message" required></textarea>
                                                    <p>Please read our <a href="#">Privacy Policy</a></p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="br-checkbox">
                                                  <input id="checkbox-2" name="checkbox-2" type="checkbox" aria-label="opção 2" checked="checked"/>
                                                  <label for="checkbox-2">Yes, I agree to the <a href="#">Privacy Policy</a></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <button type="submit">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="step-two" role="tabpanel" aria-labelledby="tuesday-tab">
                            <div class="contact-form">
                                <div class="row">
                                    <form action="">
                                        <div class="col-12">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your name (<span>*</span>)</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Khanh Phuong" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your email (<span>*</span>) </label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Plats Network" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Your phone number</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="+84 123 456 789">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Company</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Plats Network">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Inquiry Category</label>
                                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Account Setting">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="name">Description <span class="text-danger">*</span></label>
                                                    <textarea name="message" class="form-control" id="message" cols="80" rows="15" placeholder="I can not activate my account using code sent to my email" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <button type="submit">Send</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.layouts.subscribe')
@endsection
