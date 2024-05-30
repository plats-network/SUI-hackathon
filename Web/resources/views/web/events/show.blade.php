@extends('web.layouts.event_app')
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="package_id" content="{{ env('PACKAGE_ID') }}"/>
</head>
@section('content')
    <style>
        .disabled {
            pointer-events: none;
            cursor: default;
        }
        .post-author-area{
            border: 2px solid #21aeff6e;
            border-radius: 5px;
            padding: 24px 20px;
            box-shadow: none !important;
        }
        .tanger{
            width: 5px;
            background: #6bbbe8;
            position: absolute;
            height: 45px;
            left: 20px;
            top: 25px;
        }
        
        .size-submited{
            font-weight: bold;
            font-size: 30px;
            left: 15px;
            position: relative;
        }
        .line {
            height: 0px;
            border-width: 1px;
            border-color: #15ABFFFF;
            border-style: solid;
            transform: rotate(180deg);
            margin-top: 10px;
        }
        .verry-blue {
            width: 25px;
            fill: #3ea2ff;
          }
        .btn-follow{
            padding: 8px;
            /* background: red; */
            /* height: 20px; */
            width: 120px;
            border: 1px solid #3ea2ff;
            border-radius: 5px;
        }
        .avatar-submited{
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .flex-submited{
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 20px;
        }
        .mr-15{
            margin-right: 10px;
        }
        .sort-avatar-0{
            background: #f5d2d2;
        }
        .sort-avatar-1{
            right: 15px;
            position: relative;
            background: #59f2fd;
        }
        .sort-avatar-2{
            right: 30px;
            position: relative;
            background: #fd597a;
        }
        .sort-avatar-3{
            right: 45px;
            position: relative;
            background: #fffe16;
        }
        .register-by{
            display: flex;
            align-content: flex-start;
            flex-direction: column;
        }
        .all-member{
            position: relative;
            right: 60px;
            height: 40px;
            background: #898989;
            padding: 10px;
            border-radius: 50%;
            color: white;
            font-weight: bold;
        }
        .list-name-member{
            margin-top: 10px;
        }
        .btn-register-event,.btn-get-ticket{
            padding: 10px 50px;
            background-color: #187fe2;
            color: #fff;
            margin-top: 20px;
        }
        .infor-event{
            padding: 20px;
            border: 1px solid #11b2ff;
            border-radius: 5px;
        }
        .location{
            color:#11b2ff;
        }
        .icon-address{
            width: 40px;
            text-align: center;
            height: 40px;
            border-radius: 5px;
            background: #bee8ff;
            border: 1px solid #c2c2c2;
        }
        .svg-icon-address{
            margin-top: 10px;
        }
        .text-address{
            margin-left: 10px;
        }
        .btn-claim-id{
            background: #3ea2ff;
            color: white;
        }
        .post-details-content img {
               padding: 0 !important;
        }
        .mt-25{
            margin-top: 25px;
        }
        .align-content-center{
            align-items: center;
        }
        .btn-register-event,.btn-get-ticket{
            background: #2c75c0;
            color: white;
            font-weight: bold;
        }
        .btn-register-event:hover{
            background: #2c75c0;
            color: white;
            font-weight: bold;
        }
        .btn-get-ticket:hover{
            background: #2c75c0;
            color: white;
            font-weight: bold;
        }
        .ticket--sp{
            display: none !important;
        }
        .btn-danger{
            background: red;
        }
        .btn-danger:hover{
            background: red;
        }
        .register-event{
            border: none;
            background: none;
            color: blue;
            margin-top: 20px;
            text-align: center;
            display: block;
            text-decoration: revert;
        }
    </style>
    @if(!$link_check_in)
        @vite('resources/js/claim.js')
    @endif

    @php
        if (auth()->user() !== null){
            $userId = auth()->user()->id;
            $email = auth()->user()->email;
            $userCode = new App\Models\Event\UserCode();
        }
    @endphp
    <section class="confer-blog-details-area section-padding-100-0">
        <div class="container">
            <div class="row justify-content-center mt-25">
                <div class="col-lg-6">
                    <div class="pr-lg-4 mb-100">
                        <div class="post-details-content">
                            
                            <div class="post-blog-thumbnail mb-30">
                                <img src="{{$event->banner_url ?? ''}}" alt="">
                            </div>
                            {{--  <h4 class="post-title">{{$event->name ?? ''}}</h4>
                            <div class="post-meta">
                                <a class="post-date" href="#">
                                    <i class="zmdi zmdi-alarm-check"></i> {{dateFormat($event->created_at)}}
                                </a>
                                <a class="post-author" href="#"><i
                                        class="zmdi zmdi-account"></i> {{optional($event->author)->name}}</a>
                                <a class="post-author" href="#"><i class="zmdi zmdi-favorite-outline"></i> 8 Likes</a>
                            </div>
                            {!! $event->description !!}  --}}
                        </div>
                        @if (request()->hasAny('check-in'))
                            @if($session)
                                <div class="post-details-content">
                                    <h4 class="post-title">Session: {{$session->name}}</h4>
                                    {!! $session->description !!}
                                </div>
                            @endif
                            @if($booth)
                                <div class="post-details-content">
                                    <h4 class="post-title">Session: {{$booth->name}}</h4>
                                    {!! $booth->description !!}
                                </div>
                            @endif
                        @endif
                        
                        {{--  share  --}}
                        {{--  <div class="post-tags-social-area mt-30 pb-5 d-flex flex-wrap align-items-center">
                            <div class="popular-tags d-flex align-items-center">
                                <p><i class="zmdi zmdi-label"></i></p>
                                <ul class="nav">
                                    <li><a href="#">Event</a></li>
                                </ul>
                            </div>
                            <div class="author-social-info">
                                {!!
                                    Share::page(route('web.events.show', $event->id), $event->name)
                                        ->facebook()
                                        ->twitter()
                                        ->linkedin($event->name)
                                        ->whatsapp()
                                        ->telegram()
                                !!}
                            </div>
                        </div>  --}}

                        <div class="post-author-area  align-items-center my-5">
                            <h2>
                                <span  class="tanger"></span> <b class="size-submited">Submited by</b>
                            </h2>
                            
                            <div class="line">

                            </div>
                            <div class="flex-submited mt-3">
                                <img class="avatar-submited mr-15" src="{{  $userSubmited->avatar_path  }}">
                                <div class="mr-15">
                                    <b> {{Str::limit($userSubmited->name, 26)}}</b>
                                </div>
                                <div class="mr-15">
                                    <svg data-v-2c3852b6="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="verry-blue"><path data-v-2c3852b6="" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg>
                                </div class="mr-15">
                                <button class="btn-follow">
                                    Follow
                                </button>
                            </div>
                            {{--  <div class="author-avatar">
                                <img src="{{imgAvatar(optional($event->author)->avatar_path)}}"
                                     alt="{{optional($event->author)->name}}">
                            </div>
                            <div class="author-content">
                                <h5>{{optional($event->author)->name}}</h5>
                                <span>Client Service</span>
                            </div>  --}}
                        </div>
                        <div class="post-author-area  align-items-center my-5">
                            <h2>
                                <span  class="tanger"></span> <b class="size-submited">Registered by</b>
                            </h2>
                            
                            <div class="line">

                            </div>
                            <div class="register-by mt-3 ">
                                <div class="d-flex align-items-center">
                                    <img class="avatar-submited sort-avatar-0" src="{{ asset('/imgs/users/A1.jpg') }}">
                                    <img class="avatar-submited sort-avatar-1"  src="{{ asset('/imgs/users/B1.jpg') }}">
                                    <img class="avatar-submited sort-avatar-2"  src="{{ asset('/imgs/users/C1.jpg') }}">
                                    <img class="avatar-submited sort-avatar-3"  src="{{ asset('/imgs/users/D1.jpg') }}">
                                    <div class="all-member">
                                        +{{ count($eventUserTicket) }}
                                    </div>
                                </div>
                                <div class="list-name-member">
                                    
                                    <h3>By: {{ $displayString }}</h3>
                                </div>
                            </div>
                            {{--  <div class="author-avatar">
                                <img src="{{imgAvatar(optional($event->author)->avatar_path)}}"
                                     alt="{{optional($event->author)->name}}">
                            </div>
                            <div class="author-content">
                                <h5>{{optional($event->author)->name}}</h5>
                                <span>Client Service</span>
                            </div>  --}}
                        </div>
                    </div>
                </div>
                <input type="hidden" id="mnemonic_client" value="{{ env('MNEMONIC_CLIENT')}}">
                <input type="hidden" id="package_id" value="{{ env('PACKAGE_ID')}}">
                <input type="hidden" id="contract_event_id" value="{{ $event->contract_event_id }}">
                <input type="hidden" id="collection_id" value="{{ env('COLLECTION_ID')}}">
                <input type="hidden" id="ticket_id" value="{{ !empty($nft) ? $nft->address_nft : ''}}">

                <div id="fixed" class="col-lg-6">
                    <div class="confer-sidebar-area mb-100">
                        <div class="single-widget-area">
                            <div class="infor-event">
                                <h1 class="text-center size-submited">{{$event->name ?? ''}}</h1>
                                <div class="view text-right">
                                    <svg width="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg> 
                                    {{  rand(100,1000); }}
                                </div>
                                <div class="start-time d-flex align-content-center">
                                    <div class="icon-address">
                                        <svg width="15" class="svg-icon-address" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M96 32V64H48C21.5 64 0 85.5 0 112v48H448V112c0-26.5-21.5-48-48-48H352V32c0-17.7-14.3-32-32-32s-32 14.3-32 32V64H160V32c0-17.7-14.3-32-32-32S96 14.3 96 32zM448 192H0V464c0 26.5 21.5 48 48 48H400c26.5 0 48-21.5 48-48V192z"/></svg>
                                    </div>
                                    <div class="text-address w-100">
                                        <b>{{ $event->start_at }} - {{ $event->end_at }}</b>
                                        <br>
                                        {{--  <span class="location">Asia/Vietnam</span>  --}}
                                    </div>
                                </div>
                                <br>
                                <div class="start-time d-flex align-content-center">
                                    <div class="icon-address">
                                        <svg width="15" class="svg-icon-address" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>
                                    </div>
                                    <div class="text-address w-100">
                                        <b>{{ $event->address }}</b>
                                        <br>
                                        {{--  <span class="location">Vietnam</span>  --}}
                                    </div>
                                </div>
                                <br>
                                <div class="line"></div>
                                <br>

                                {{-- sự kiện đã kết thúc mà user chưa đăng kí event thì không thế đăng kí nữa --}}
                                @if($eventExpired)

                                    <a  class="w-100 btn-get-ticket btn btn-danger btn-info" href="#">The event has ended</a> 
                                @endif

                                {{--  user đang ở link checkin và claim vé đó rồi  --}}
                                @if($eventUserClaimTicket && $link_check_in && !$eventExpired)

                                    <a  class="w-100 btn-get-ticket btn btn-primary  btn-info" href="https://suiscan.xyz/{{ env('TYPE_NETWORK') }}/tx/{{$eventUserClaimTicket->txt_hash ?? ''}}" target="_blank">Explorer checkin</a>
                                    {{--  <button id="check-in-nft" class="w-100 btn-get-ticket get-exploder-checkin btn btn-primary  btn-info">Explorer checkin</button>   --}}
                                @endif

                                {{--  usser đang ở link checkin và chưa claim vé đó  --}}
                                @if(!$eventUserClaimTicket && $link_check_in && !$eventExpired)

                                    {{--  nếu user chưa đăng kí vé thì hiển thị bắt đăng kí vé  --}}
                                    @if(!$checkMint)

                                        <a  class="w-100 btn-get-ticket btn btn-danger btn-info" href="https://{{ env('SUB_EVENT') }}.{{ env('APP_URL') }}/event/{{ $event->id }}">Please register event</a> 
                                    
                                    @else

                                        @vite('resources/js/userCheckin.js')
                                        <input type="hidden" id="task_id" value="{{ $event->id }}">
                                        <input type="hidden" id="contract_event_id" value="{{ $checkMint->contract_event_id }}">
                                        <input type="hidden" id="user_claim_address_nft" value="{{ $checkMint->address_nft }}">
                                        <button id="check-in-nft" class="w-100 btn-get-ticket get-exploder-checkin btn btn-primary  btn-info">Checkin event</button>
                                    
                                    @endif
                                    
                                @endif

                                @if ($checkMint && !$link_check_in && !$eventExpired)
                                    <a  class="btn btn-info" style="display: block;margin-bottom: 20px;color: #0fff0f;border: 1px solid #0fff0f;" href="#">Claim already</a>
                                    <a  class="link-primary register-event" target="_blank" href="https://suiscan.xyz/{{ env('TYPE_NETWORK') }}/tx/{{$checkMint->digest ?? ''}}">
                                        Suiet Explorer Link
                                    </a>
                                @endif

                                @if ($nft && !$link_check_in && !$eventExpired)
                                    <div class="text-center">
                                        <input id="address_organizer" value="{{ $nft->address_organizer }}" type="hidden">
                                        <input id="digest_nft" value="{{ isset($nft->nft_res) ? json_decode($nft->nft_res, true)['digest'] : ''}}" type="hidden">
                                        <input id="address_nft" value="{{ $nft->address_nft }}" type="hidden">
                                        <input id="seed" value="{{ $nft->seed }}" type="hidden">
                                        <input id="task_id" value="{{ $nft->task_id }}" type="hidden">
                                        <input id="user_address" value="{{ auth()->user() ? auth()->user()->wallet_address : '' }}" type="hidden">
                                        <input id="nft_id" value="{{ $nft->id_ticket_nft_mint }}" type="hidden">
                                        <input id="email_login" value="{{ auth()->user() ? auth()->user()->email : '' }}" type="hidden">
                                        <a style="display:none; margin-bottom: 20px;color: #0fff0f;border: 1px solid #0fff0f;" class="btn btn-info claim-success" href="#">Claim already</a>
                                        <a style="border: none;display:none;background: none;color: blue;text-align: center;    text-decoration: revert;" class="link-primary sol-link" target="_blank" href="https://suiscan.xyz/devnet/{{ env('TYPE_NETWORK') }}/{{ !empty($nft) && isset($nft->nft_res) ? json_decode($nft->nft_res, true)['digest'] : '' }}">
                                            Suiet Explorer Link
                                        </a>
                                    </div>
                                @endif

                                @if(!$checkMint && !$link_check_in && !$eventExpired)
                                    <a class="w-100 btn-register-event btn btn-primary  btn-info {{auth()->user() != null ? 'btn-claim-id' : 'zklogin'}} {{ !$nft ? 'disabled' : '' }}" href="#" data-url="{{route('web.formLogin')}}">Register event</a>
                                @endif
                               
                                <br>
                                {{--  <button id="click-event">ok</button>  --}}
                            </div>
                            <div class="post-author-area  align-items-center my-5">
                                <h2>
                                    <span class="tanger"></span> <b class="size-submited">About the event</b>
                                </h2>
                                <div class="line">
                                </div>
                                <div class="mt-3">
                                    {!! $event->description !!}
                                </div>
                            </div>
                            {{--  <div class="post-author-widget">
                                
                            </div>  --}}
                            {{--                            <div class="post-author-widget">--}}
                            {{--                                <a id="showModal" class="btn btn-info" href="#">Get Ticket</a>--}}
                            {{--                                <hr>--}}

                            {{--                                @if ($sponsor)--}}
                            {{--                                    <div class="sponsor d-none">--}}
                            {{--                                        <h3 style="font-size: 30px;">Sponsor</h3>--}}
                            {{--                                        <h3 class="title" title="{{$sponsor->name}}">{{$sponsor->name}}</h3>--}}
                            {{--                                        <p class="descs" title="{{$sponsor->description}}">{{$sponsor->description}}</p>--}}
                            {{--                                        <div class="note">--}}
                            {{--                                            <p class="price">Total Price: {{number_format($sponsor->price)}} ACA</p>--}}
                            {{--                                            <p>Backers: 10+</p>--}}
                            {{--                                            <p>Est delivery: {{dateFormat($sponsor->end_at)}}</p>--}}
                            {{--                                        </div>--}}

                            {{--                                        <h3>Support</h3>--}}
                            {{--                                        --}}{{--Form--}}
                            {{--                                        <form action="{{ route('web.createCrowdSponsor',['task_id' =>$event->id] ) }}"--}}
                            {{--                                              method="post">--}}
                            {{--                                            @csrf--}}
                            {{--                                            @method('POST')--}}
                            {{--                                            <div class="buget">--}}
                            {{--                                                <h3>Make a pledge without a reward</h3>--}}
                            {{--                                                <div class="row text-left">--}}
                            {{--                                                    <div class="col-12 text-left">--}}
                            {{--                                                        <label class="text-left" style="width: 100%; font-size: 12px;">Pledge--}}
                            {{--                                                            amount</label>--}}
                            {{--                                                    </div>--}}
                            {{--                                                </div>--}}
                            {{--                                                <div class="row">--}}
                            {{--                                                    <div class="col-md-5" style="padding-top: 8px; padding-right: 0px;">--}}
                            {{--                                                        ACA--}}
                            {{--                                                    </div>--}}
                            {{--                                                    <div class="col-md-7" style="padding-left: 2px">--}}
                            {{--                                                        <input id="amount" class="form-control" type="text"--}}
                            {{--                                                               name="price">--}}
                            {{--                                                        <input id="sponsorId" type="hidden">--}}
                            {{--                                                    </div>--}}
                            {{--                                                </div>--}}
                            {{--                                                @if (auth()->guest())--}}
                            {{--                                                    <a class="guest"--}}
                            {{--                                                       href="{{route('web.formLogin', ['type' => 'sponsor', 'id' => $event->id])}}">Continue</a>--}}
                            {{--                                                @else--}}
                            {{--                                                    <button--}}
                            {{--                                                        type="submit"--}}
                            {{--                                                        id="cSponsor2"--}}
                            {{--                                                        data-type="sponsor2"--}}
                            {{--                                                        data-id="{{$event->id}}"--}}
                            {{--                                                        data-url="{{route('new.sponsor')}}">Continue--}}
                            {{--                                                    </button>--}}
                            {{--                                                @endif--}}
                            {{--                                            </div>--}}
                            {{--                                        </form>--}}

                            {{--                                        <h3>Package</h3>--}}
                            {{--                                        <div class="package-item">--}}
                            {{--                                            @foreach($sponsor->sponsorDetails as $item)--}}
                            {{--                                                <div class="item price-package"--}}
                            {{--                                                     data-price="{{number_format($item->price)}}"--}}
                            {{--                                                     data-id="{{$item->id}}">--}}
                            {{--                                                    <p>{{$item->name}} <span class="price">{{$item->price}}</span> ACA--}}
                            {{--                                                    </p>--}}
                            {{--                                                    <p class="desc">{{$item->description}}</p>--}}
                            {{--                                                    <hr>--}}
                            {{--                                                </div>--}}
                            {{--                                            @endforeach--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                @endif--}}
                            {{--                                @if(auth()->user() !== null)--}}
                            {{--                                    <div class="box_gift" style="margin-top: 20px">--}}
                            {{--                                        @if($travelSessions->isNotEmpty())--}}
                            {{--                                            <div class="session" style="margin-bottom: 20px">--}}
                            {{--                                                <div class="d-flex justify-content-between align-items-center mb-3"--}}
                            {{--                                                     style="margin-bottom: 20px">--}}
                            {{--                                                    <strong>Session</strong>--}}
                            {{--                                                    <a class="p-0" href="{{route('job.getTravelGame', $task_id)}}">See--}}
                            {{--                                                        more</a>--}}
                            {{--                                                </div>--}}
                            {{--                                                @foreach($travelSessions as $k => $session)--}}
                            {{--                                                    @php--}}
                            {{--                                                        $codes = $userCode->where('user_id', $userId)--}}
                            {{--                                                            ->where('travel_game_id', $session->id)--}}
                            {{--                                                            ->where('task_event_id', $session_id)--}}
                            {{--                                                            ->where('type', 0)--}}
                            {{--                                                            ->pluck('number_code')--}}
                            {{--                                                            ->implode(',');--}}
                            {{--                                                    @endphp--}}
                            {{--                                                    <div class="item">--}}
                            {{--                                                        <p>Joined: <span style="color:green">{{$totalCompleted}}</span>--}}
                            {{--                                                            / {{$countEventDetail}}--}}
                            {{--                                                            sessions</p>--}}
                            {{--                                                        <p>Lucky Code: <span--}}
                            {{--                                                                class="fs-25">{{$codes ? $codes : '---'}}</span></p>--}}
                            {{--                                                    </div>--}}
                            {{--                                                @endforeach--}}
                            {{--                                            </div>--}}
                            {{--                                        @endif--}}
                            {{--                                        @if($travelBooths->isNotEmpty())--}}
                            {{--                                            <hr>--}}
                            {{--                                            <div class="booth" style="margin: 20px 0">--}}
                            {{--                                                <div class="d-flex justify-content-between align-items-center mb-3"--}}
                            {{--                                                     style="margin-bottom: 20px">--}}
                            {{--                                                    <strong>Booth</strong>--}}
                            {{--                                                    <a class="p-0" href="{{route('job.getTravelGame', $task_id)}}">See--}}
                            {{--                                                        more</a>--}}
                            {{--                                                </div>--}}
                            {{--                                                @foreach($travelBooths as $k => $booth)--}}
                            {{--                                                    @php--}}
                            {{--                                                        $codesBooth = $userCode->where('user_id', $userId)--}}
                            {{--                                                            ->where('travel_game_id', $booth->id)--}}
                            {{--                                                            ->where('task_event_id', $booth_id)--}}
                            {{--                                                            ->where('type', 1)--}}
                            {{--                                                            ->pluck('number_code')--}}
                            {{--                                                            ->implode(',');--}}
                            {{--                                                    @endphp--}}
                            {{--                                                    <div class="item">--}}
                            {{--                                                        <p>Joined: <span--}}
                            {{--                                                                style="color:green">{{$totalCompletedBooth}}</span>--}}
                            {{--                                                            / {{$countEventDetailBooth}}--}}
                            {{--                                                            booth</p>--}}
                            {{--                                                        <p>Lucky Code: <span--}}
                            {{--                                                                class="fs-25">{{$codesBooth ? $codesBooth : '---'}}</span>--}}
                            {{--                                                        </p>--}}
                            {{--                                                    </div>--}}
                            {{--                                                @endforeach--}}
                            {{--                                            </div>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                @endif--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false">
        <style type="text/css">
            .text-danger {
                color: red;
            }

            .btn--order {
                padding: 10px 30px;
                background: #3EA2FF;
                color: #fff;
                text-align: right;
            }
        </style>

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size: 30px; text-align: center;">Contact information</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="tickit_form" method="POST" action="{{route('order.ticket')}}">
                        @csrf
                        @method('POST')
                        <p class="text-danger text-center" style="padding-bottom: 20px;">Please enter correct email or
                            phone number information to receive attractive gifts from the event.</p>

                        <input type="hidden" name="task_id" value="{{$event->id}}">

                        <div class="row my-3">
                            <div class="col-md-6 field-first">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="first" id="first" required>
                                <div class="valid-feedback"></div>
                            </div>
                            <div class="col-md-6 field-last">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="last" id="last" required>
                                <div class="valid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 field-email">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input
                                    type="email"
                                    class="form-control"
                                    name="email"
                                    id="email"
                                    value="{{$user ? $user->email : ''}}"
                                    @if ($user) disabled @endif
                                    required>
                                <div class="valid-feedback"></div>
                            </div>
                            <div class="col-md-6 field-phone">
                                <label class="form-label">Phone <span class="text-danger">(optional)</span></label>
                                <input type="text" class="form-control" max="15" min="10"
                                       value="{{$user ? $user->phone : ''}}" id="phone" name="phone">
                                <div class="valid-feedback"></div>
                            </div>
                        </div>
                        <div class="text-center" style="margin-top: 20px;">
                            <button type="button" class="claim-btn btn btn-primary btn--order">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Model success--}}
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <i class="bx bx-check-circle"></i>
                    <h3>Success Checkin</h3>
                    <p>Thank you for your interest in our event. We will contact you as soon as possible.</p>
                    <button type="button" class="btn" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('web.layouts.subscribe')
    <a href="#" class="btn btn-primary ticket--sp">Get ticket</a>

@endsection

@section('scripts')
    @uploadFileJS
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{asset('plugins/yii2-assets/yii.js')}}"></script>
    <script src="{{asset('plugins/yii2-assets/yii.activeForm.js')}}"></script>
    <script src="{{asset('plugins/yii2-assets/yii.validation.js')}}"></script>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 --}}
    <script src="{{asset('js/sweetalert2@11.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.js"
            integrity="sha384-eI7PSr3L1XLISH8JdDII5YN/njoSsxfbrkCTnJrzXt+ENP5MOVBxD+l6sEG4zoLp"
            crossorigin="anonymous"></script>
{{--    <script src="dashboard.js"></script>--}}
    
    <script>
       
        //Check has param sucess_checkin
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        @if($link_check_in && $checkMint)
            var url = window.location.href;
            if (url.indexOf('sucess_checkin') != -1) {


                setTimeout(function(e) {
                    //Show toast
                    Toast.fire({
                        icon: 'success',
                        title: 'Checkin success 😀',
                    })
                }, 1500);
            }
        @endif
        
        $("#click-event").click(function(){
            
            let data = {
                first:"first",
                last:"last",
                task_id:"9c0daab2-13f1-45b0-bf61-b30556a9a12a",
                email:"cifow69607@bsomek.com",
                phone:"admin1234567"
            }
          
            $.ajax({
                type: "POST",
                url: "{{ route('order.ticket') }}",
                data: data,
            }).then(function(data){
                console.log(data)
            });
        })
    </script>

    <script>
        var _token = $('meta[name="csrf-token"]').attr('content');
        var spinText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
        var fileAvatarInit = null;
        var flag_check = 1;
        //Download ticket,
        var download_ticket = {{$download_ticket}}
        @if($download_ticket)
        window.open("{{$url_download_ticket}}");
        @endif
    </script>
    {{--  @if ($checkMint)
        <script>
            alert('Claim is success.')
        </script>
    @endif  --}}
    {{--validate--}}
    <script>
        //https://yii2-cookbook-test.readthedocs.io/forms-activeform-js/
        jQuery(function ($) {
            jQuery('#tickit_form').yiiActiveForm([
                {
                    "id": "first",
                    "name": "first",
                    "container": ".field-first",
                    "input": "#first",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                    }
                },
                /*address*/
                {
                    "id": "last",
                    "name": "last",
                    "container": ".field-last",
                    "input": "#last",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                    }
                },
                {
                    "id": "email",
                    "name": "email",
                    "container": ".field-email",
                    "input": "#email",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                        yii.validation.email(value, messages, {
                            "pattern": /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/,
                            "fullPattern": /^[^@]*<[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/,
                            "allowName": false,
                            "message": "Email không phải là địa chỉ email hợp lệ.",
                            "enableIDN": false,
                            "skipOnEmpty": 1
                        });
                    }
                },
                {
                    "id": "phone",
                    "name": "phone",
                    "container": ".field-phone",
                    "input": "#phone",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.string(value, messages, {
                            "message": "Phone phải là chuỗi.",
                            "min": 10,
                            "tooShort": "Phone phải chứa ít nhất 10 ký tự.",
                            "skipOnEmpty": 1
                        });
                        yii.validation.string(value, messages, {
                            "message": "Phone phải là chuỗi.",
                            "max": 15,
                            "tooLong": "Phone phải chứa nhiều nhất 15 ký tự.",
                            "skipOnEmpty": 1
                        });
                    }
                },


            ], []);
        });

        if ($('.showModal').length > 0) {
            $('.showModal').on('click', function() {
                $('#myModal').modal();
                $('.modal-backdrop').css('z-index', '1');
            });

            $('.ticket--sp').on('click', function() {
                $('#myModal').modal();
                $('#successModal').modal();
            });
        }
    </script>
@endsection
@push('custom-scripts')
    <script src="{{ url('js/index.umd.js') }}"></script>
    {{--  <script src="https://auth.magic.link/sdk"></script>
    <script type="text/javascript" src="https://auth.magic.link/sdk/extension/solana"></script>  --}}
@endpush
