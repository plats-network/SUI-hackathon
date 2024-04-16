@extends('web.layouts.event_app')

@section('content')
    <section class="event-list ev-list pt-70">
        <div class="container">
            
            @include('web.events.data', ['events' => $events,'eventsPendings'=>$eventsPending])

            <div class="row">
                <div class="col-12">
                    <div
                        class="more-schedule-btn text-center mb-80"
                        data-page="2"
                        data-link="{{route('web.events')}}?page="
                        data-div="#boxEvent" onclick="loadMoreData('{{route('web.events')}}', 2);">
                        <a href="#" class="btn confer-gb-btn">See more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
