@extends('web.layouts.event_app')

@section('content')
    <section class="confer-blog-details-area section-padding-100-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="pr-lg-4 mb-100">
                        <div class="post-details-content">
                            <div class="post-blog-thumbnail mb-30">
                                <img src="{{$event->banner_url}}" alt="">
                            </div>
                            <h4 class="post-title">{{$event->name}}</h4>
                            <div class="post-meta">
                                <a class="post-date" href="#">
                                    <i class="zmdi zmdi-alarm-check"></i> {{dateFormat($event->created_at)}}
                                </a>
                                <a class="post-author" href="#"><i class="zmdi zmdi-account"></i> {{optional($event->author)->name}}</a>
                                <a class="post-author" href="#"><i class="zmdi zmdi-favorite-outline"></i> {{rand(100,1000)}} Likes</a>
                            </div>
                            {!! $event->description !!}
                        </div>
                    </div>
                </div>
                <div id="fixed" class="col-lg-4">
                    <div class="confer-sidebar-area mb-100">
                        <div class="single-widget-area">
                            <div class="post-author-widget">
                                @if ($sponsor)
                                    <div class="sponsor">
                                        <h3 style="font-size: 30px;">Sponsor</h3>
                                        <h3 class="title" title="{{$sponsor->name}}">{{$sponsor->name}}</h3>
                                        <p class="descs"  title="{{$sponsor->description}}">{{$sponsor->description}}</p>
                                        <div class="note">
                                            <p class="price">Reward: ${{number_format($detail->price)}}</p>
                                            <p class="price">Bonus: $0</p>
                                            <hr style="margin: 10px 0;">
                                            <p>Total: ${{$detail->price}}</p>
                                        </div>
                                        <div class="buget" style="height: 70px;">
                                            <a class="guest btn-danger" style="background-color: darkcyan;" href="#">Connect Wallet</a>
                                            <button
                                                id="subSponsor"
                                                data-id="{{$event->id}}"
                                                data-sponsor-id="{{$sponsor->id}}"
                                                data-detail-id="{{$detail->id}}"
                                                data-amount="{{$detail->price}}"
                                                data-note="{{$detail->description}}"
                                                data-url="{{route('new.saveSponsor')}}"
                                                data-redir="{{route('web.events.show', ['id' => $event->id])}}"
                                                type="button">Submit</button>
                                        </div>

                                        <h3>Top events</h3>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection