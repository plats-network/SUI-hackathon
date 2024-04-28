@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Dashboard</h4>
    </div>
@endsection
<style>
    .total-event-new{
        background: linear-gradient(135deg, rgb(199, 112, 220) 0%, rgb(104, 83, 210) 100%) 0px 0px / 370.882px 256px;
    }
    .total-event{
       background: linear-gradient(135deg, rgb(238, 203, 75) 0%, rgb(233, 179, 32) 100%) 0px 0px / 370.882px 256px;
    }
    .border-radius-bottom{
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .icon-total-event{
        z-index: 9;
        fill: #cec2da;
        width: 30px;
        height: 30px;
        text-align: center;
        position: relative;
        right: -4px;
        top: 4px;
    }
    .bg-icon-total-event{
        width: 50px;
        padding: 5px;
        height: 50px;
        background: #fffffff5;
        border-radius: 25px;
        text-align: center;
    }
    .tittle-event{
        font-size: 25px !important;
    }
    .number{
        font-size: 35px;
        font-weight: bold;
    }
    .create-event{
        height: 35px;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h2 class="mb-0">Dashboard</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="_0r0u9w" width="951.428852761674" height="653.3944202804546" style="zoom: 65%;overflow: visible; width: 547.072px; height: 375.702px; transform: translate(-0.575px, 0px);"><g style="transform: scale(0.575);"><g><g transform="translate(87.51561889648437, 0)"><g><g style="cursor: pointer; outline: none;"><g><rect x="268.23661693259476" y="12" width="328.44" height="44.44" rx="4" fill="rgba(13, 18, 22, 0)" style="transition: fill 0.15s ease-in-out 0s;"></rect><rect x="276.23661693259476" y="20" width="28.439999999999998" height="28.439999999999998" rx="1" fill="#67b7f3" opacity="1" style="transition: opacity 0.15s ease-in-out 0s;"></rect><text class="tVQpqQ" text-anchor="start" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" y="40.72" x="316.67661693259475" opacity="1" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="transition: opacity 0.15s ease-in-out 0s;">Total event participants</text></g></g></g></g><g transform="translate(87.51561889648437, 68.44)"><clipPath id=":r0:"><rect width="952.428852761674" height="596.161527061641" x="-87.51561889648437" y="-10.5"></rect></clipPath><g clip-path="url(#:r0:)"><g><g><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(-25.515618896484376, 497.9188684276469) rotate(0)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">0</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(-47.51561889648437, 400.0350947421175) rotate(0)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">2000</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(-48.51561889648437, 302.15132105658813) rotate(0)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">4000</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(-47.51561889648437, 204.26754737105875) rotate(0)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">6000</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(-47.51561889648437, 106.38377368552938) rotate(0)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">8000</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(-53.51561889648437, 8.5) rotate(0)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">10000</text></g><g><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(25.87920906041876, 544.6493337528213) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 1</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(110.41575505336846, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 2</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(197.78072817106437, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 3</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(284.43859450757384, 548.184867658754) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 4</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(372.51067440645625, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 5</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(459.87564752415216, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 6</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(547.2406206418482, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 7</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(634.605593759544, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 7</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(721.9705668772401, 547.4777608775676) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 9</text><text class="tVQpqQ" text-anchor="middle" dominant-baseline="alphabetic" fill="#000000" text-rendering="geometricPrecision" font-weight="400" transform="translate(805.8000060890033, 551.0132947835002) rotate(-45)" font-family="&quot;YAFdJvSyp_k 2&quot;" font-size="23.7" font-style="normal" style="color: rgb(0, 0, 0);">Event 10</text></g><g><g opacity="0.6"><line x1="0" x2="864.9132338651896" y1="489.4188684276469" y2="489.4188684276469" stroke="#000000" stroke-width="1"></line></g><g opacity="0.25"><line x1="0" x2="864.9132338651896" y1="391.5350947421175" y2="391.5350947421175" stroke="#000000" stroke-width="1"></line></g><g opacity="0.25"><line x1="0" x2="864.9132338651896" y1="293.65132105658813" y2="293.65132105658813" stroke="#000000" stroke-width="1"></line></g><g opacity="0.25"><line x1="0" x2="864.9132338651896" y1="195.76754737105875" y2="195.76754737105875" stroke="#000000" stroke-width="1"></line></g><g opacity="0.25"><line x1="0" x2="864.9132338651896" y1="97.88377368552938" y2="97.88377368552938" stroke="#000000" stroke-width="1"></line></g><g opacity="0.25"><line x1="0" x2="864.9132338651896" y1="0" y2="0" stroke="#000000" stroke-width="1"></line></g></g><g></g></g></g><g><g><g><clipPath id=":r1:"><rect x="0" y="244.70943421382344" width="78.62847580592633" height="244.70943421382344" opacity="1"></rect></clipPath><rect x="0" y="244.70943421382344" width="78.62847580592633" height="250.99971227829752" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r1:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r2:"><rect x="87.36497311769592" y="293.65132105658813" width="78.62847580592633" height="195.76754737105875" opacity="1"></rect></clipPath><rect x="87.36497311769592" y="293.65132105658813" width="78.62847580592633" height="202.05782543553283" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r2:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r3:"><rect x="174.72994623539185" y="342.5932078993528" width="78.62847580592633" height="146.82566052829407" opacity="1"></rect></clipPath><rect x="174.72994623539185" y="342.5932078993528" width="78.62847580592633" height="153.11593859276815" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r3:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r4:"><rect x="262.0949193530878" y="48.94188684276469" width="78.62847580592633" height="440.4769815848822" opacity="1"></rect></clipPath><rect x="262.0949193530878" y="48.94188684276469" width="78.62847580592633" height="446.7672596493563" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r4:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r5:"><rect x="349.4598924707837" y="103.2673812382335" width="78.62847580592633" height="386.1514871894134" opacity="1"></rect></clipPath><rect x="349.4598924707837" y="103.2673812382335" width="78.62847580592633" height="392.44176525388747" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r5:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r6:"><rect x="436.8248655884796" y="44.48817514007311" width="78.62847580592633" height="444.9306932875738" opacity="1"></rect></clipPath><rect x="436.8248655884796" y="44.48817514007311" width="78.62847580592633" height="451.22097135204785" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r6:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r7:"><rect x="524.1898387061756" y="320.47147504642317" width="78.62847580592633" height="168.94739338122372" opacity="1"></rect></clipPath><rect x="524.1898387061756" y="320.47147504642317" width="78.62847580592633" height="175.2376714456978" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r7:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r8:"><rect x="611.5548118238714" y="342.05484714408243" width="78.62847580592633" height="147.36402128356445" opacity="1"></rect></clipPath><rect x="611.5548118238714" y="342.05484714408243" width="78.62847580592633" height="153.65429934803853" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r8:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":r9:"><rect x="698.9197849415674" y="426.6264276083798" width="78.62847580592633" height="62.792440819267085" opacity="1"></rect></clipPath><rect x="698.9197849415674" y="426.6264276083798" width="78.62847580592633" height="69.08271888374117" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:r9:)" opacity="1" pointer-events="auto"></rect></g><g><clipPath id=":ra:"><rect x="786.2847580592634" y="271.2359368826019" width="78.62847580592633" height="218.182931545045" opacity="1"></rect></clipPath><rect x="786.2847580592634" y="271.2359368826019" width="78.62847580592633" height="224.47320960951907" fill="#67b7f3" rx="6.290278064474107" ry="6.290278064474107" clip-path="url(#:ra:)" opacity="1" pointer-events="auto"></rect></g></g></g></g></g></g></svg>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white">
                                <div class="card-header total-event-new d-flex justify-content-between">
                                    <p class="card-title fw-bold tittle-event">Total New Events</p>
                                    <div class="bg-icon-total-event">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-plus icon-total-event" viewBox="0 0 16 16">
                                            <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7"/>
                                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body total-event-new border-radius-bottom" >
                                    <div class="number-wrapper">
                                        <span class="number">10000</span>
                                    </div>
                                    <div class="change d-flex justify-content-between">
                                        <span class="label">% Change (Apr.)</span>
                                        <span class="value">0.00%</span>
                                    </div>
                                    <div class="previous d-flex justify-content-between">
                                        <span class="label">Previous (Mar.)</span>
                                        <span class="value">G</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card text-white">
                                <div class="card-header total-event d-flex justify-content-between">
                                    <p class="card-title fw-bold tittle-event">Total Events</p>
                                    <div class="bg-icon-total-event">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi icon-total-event bi-megaphone icon-total-event" viewBox="0 0 16 16">
                                            <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 75 75 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0m-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233q.27.015.537.036c2.568.189 5.093.744 7.463 1.993zm-9 6.215v-4.13a95 95 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A61 61 0 0 1 4 10.065m-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68 68 0 0 0-1.722-.082z"/>
                                          </svg>
                                    </div>
                                </div>
                                <div class="card-body total-event border-radius-bottom">
                                    <div class="number-wrapper">
                                        <span class="number">10000</span>
                                    </div>
                                    <div class="change d-flex justify-content-between">
                                        <span class="label">% Change (Apr.)</span>
                                        <span class="value">0.00%</span>
                                    </div>
                                    <div class="previous d-flex justify-content-between">
                                        <span class="label">Previous (Mar.)</span>
                                        <span class="value">G</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h3 class="mb-4 mt-4">Statistics</h3>
                        {{--  <a href="{{ route('cws.eventCreate') }}" class="btn btn-primary float-end create-event">Create Event</a>
                          --}}
                        <a href="{{ route('cws.eventList') }}" class="btn btn-primary float-end create-event">List Event</a>

                    </div>

                    <table class="table table-sm">
                        <thead>
                          <tr>
                            <th scope="col"><b>NO</b></th>
                            <th scope="col"><b>NAME</b></th>
                            <th scope="col"><b>Event registrants</b></th>
                            <th scope="col"><b>Time</b></th>
                            <th scope="col"><b>Status</b></th>
                          </tr>
                        </thead>
                        <tbody class="border border-2 border-secondary">
                            @if($events->count() > 0)
                                @foreach($events as $i => $event )
                                    <tr>
                                        <th scope="row"><a href="{{ route('cws.eventPreview', [
                                                'id' => $event->id,
                                                'tab' => 0,
                                                'preview' => 1
                                            ]) }}" class="event-link">{{ $i+1 }}</a></th>
                                        <td>
                                            <a href="{{ route('cws.eventPreview', [
                                                'id' => $event->id,
                                                'tab' => 0,
                                                'preview' => 1
                                            ]) }}" class="event-link">{{$event->name}}</a>

                                        </td>
                                        <td>
                                            <a href="{{ route('cws.eventPreview', [
                                                'id' => $event->id,
                                                'tab' => 0,
                                                'preview' => 1
                                            ]) }}" class="event-link">{{rand(100,1000)}}</a></td>
                                        <td>
                                            <a href="{{ route('cws.eventPreview', [
                                                'id' => $event->id,
                                                'tab' => 0,
                                                'preview' => 1
                                            ]) }}" class="event-link">{{ dateFormat($event->start_at) }} - {{ dateFormat($event->end_at) }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('cws.eventPreview', [
                                                'id' => $event->id,
                                                'tab' => 0,
                                                'preview' => 1
                                            ]) }}" class="event-link">
                                                
                                                    @if($event->status) 
                                                        <span class="badge badge-soft-success font-size-12">
                                                            public
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-primary font-size-12">
                                                            draft
                                                        </span>
                                                    @endif
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{ $events->links() }}
                    <p class="text-center">Uh, There's nothing here</p>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
     
    </script>
@endsection
