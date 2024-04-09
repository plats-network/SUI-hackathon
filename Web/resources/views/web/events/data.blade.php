{{-- sự kiện của user đã đăng nhập --}}
@if(!empty($eventsPendings))
    <div class="section-heading-2 mt-2">
        <p>Your events pending!</p>
    </div>
    <div class="row" id="boxEvent">
        @foreach($eventsPendings as $eventPending)
            <div class="col-12 col-md-6 col-xl-3 item-event">
                <div class="single-blog-area style-2">
                    <div class="single-blog-thumb">
                        <a class="title" href="{{route('web.events.show', $eventPending->id)}}"><img src="{{$eventPending->banner_url}}" alt=""></a>
                    </div>
                    <div class="single-blog-text">
                        <a class="blog-title" href="{{route('web.events.show', $eventPending->id)}}">{{$eventPending->name}}</a>
                        <div class="post-meta">
                            <a class="post-date" href="{{route('web.events.show', $eventPending->id)}}">
                                <i class="zmdi zmdi-alarm-check"></i> {{ dateFormat($eventPending->created_at)}}
                            </a>
                        </div>
                        <p class="desc">
                            {{-- {{Str::limit($event->description, 100)}} --}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- tất cả sự kiện --}}
@if(!empty($events))
    <div class="row" id="boxEvent">
        <div class="section-heading-2 mt-2 col-12">
            <p >The event is taking place!</p>
        </div>
        @foreach($events as $event)
            <div class="col-12 col-md-6 col-xl-3 item-event">
                <div class="single-blog-area style-2">
                    <div class="single-blog-thumb">
                        <a class="title" href="{{route('web.events.show', $event->id)}}"><img src="{{$event->banner_url}}" alt=""></a>
                    </div>
                    <div class="single-blog-text">
                        <a class="blog-title" href="{{route('web.events.show', $event->id)}}">{{$event->name}}</a>
                        <div class="post-meta">
                            <a class="post-date" href="{{route('web.events.show', $event->id)}}">
                                <i class="zmdi zmdi-alarm-check"></i> {{ dateFormat($event->created_at)}}
                            </a>
                        </div>
                        <p class="desc">
                            {{-- {{Str::limit($event->description, 100)}} --}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif