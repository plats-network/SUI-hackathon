@extends('web.layouts.event_app')
<head>
    <meta name='mnemonic_client' content="{{ env('MNEMONIC_CLIENT')}}">
    <meta name='package_id' content="{{ env('PACKAGE_ID')}}">
    <meta name='collection_id' content="{{ env('COLLECTION_ID')}}">
    <meta name='nft_hash_id' content="{{ $nft_hash_id ?? '' }}">
    <meta name='event_id' content="{{ env('EVENT_OBJECT_ID')}}">
    <meta name='type_network' content="{{ env('TYPE_NETWORK')}}">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>
@section('content')

    @php
        $userId = auth()->user()->id;
        $email = auth()->user()->email;
        $userCode = new App\Models\Event\UserCode();
    @endphp

    <style type="text/css">
        .timeline-container ul.tl li {
            list-style: none;
            margin: auto;
            min-height: 50px;
            border-left: 1px solid #86D6FF;
            padding:0 0 55px 70px;
            position: relative;
            display: flex;
            flex-direction: row;
        }

        #laravel-notify {
            z-index: 1000;
            position: absolute;
        }

        .fs-25 {
            font-size: 25px;
            color: #228b22;
        }

        .pp {
            padding-left: 20px;
            line-height: 20px !important;
            color: #000 !important;
        }

        .aaa {
            background-color: #fff8ea;
            padding: 7px 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #000;
            line-height: 20px;
            font-size: 15px;
            border: 2px solid #fab501;
        }

        #laravel-notify {
            position: absolute;
            z-index: 99999;
        }
    </style>

    <style type="text/css">
        .tab-job {
            justify-content: center;
            order-bottom: none;
        }

        .nav-link {
            border: 2px solid #177FE2;
        }

        .b1 {
            border-radius: 10px 0 0 10px;
        }

        .b2 {
            border-radius: 0px 10px 10px 0;
        }

        .active-job {
            background-color: #177FE2;
            color: #fff;
        }

        .ac-color {
            color: #258CC7 !important;
            font-weight: bold !important;
        }

        .desc-prize {
            background-color: #fff8ea;
            padding: 7px 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #000;
            line-height: 20px;
            font-size: 15px;
            border: 2px solid #fab501;
        }

        #laravel-notify {
            position: absolute;
            z-index: 99999;
        }
        .infor-data{
            padding: 30px 25px;
            border: 1px solid #5dbff6;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .tanger{
            width: 5px;
            background: #6bbbe8;
            position: absolute;
            height: 35px;
            left: 41px;
        }
        .size-submited {
            font-weight: bold;
            font-size: 20px;
            position: relative;
            left: 5px;
        }
        .line {
            height: 0px;
            border-width: 1px;
            border-color: #15ABFFFF;
            border-style: solid;
            transform: rotate(180deg);
            margin-top: 14px;
            margin-bottom: 10px;
        }
        .pd-25{
            padding: 25px;
        }
        .fs-title{
            font-size: 35px;
        }
        .fs-green{
            font-size: 20px;
            color: #00b4ff;
        }
        .link-primary{
            background:#fff !important;
            border: 1px solid #0fff0f !important;
            color: #0fff0f !important;
        }
        .txt-exploder-link{
            text-decoration: revert;
            color: #258cc7;
        }
        .timeline-container {
            width: 100% !important;
        }
        .f-bold{
            font-weight: bold;
        }
        .active-verry{
            background-image: url(`data:image/svg+xml;base64,PHN2ZyBjbGFzcz0idy02IGgtNiIgZmlsbD0iIzY4NjU4MyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZD0iTTEyIDJDNi40OCAyIDIgNi40OCAyIDEyczQuNDggMTAgMTAgMTBzMTAtNC40OCAxMC0xMFMxNy41MiAyIDEyIDJ6bS0yIDE1bC01LTVsMS40MS0xLjQxTDEwIDE0LjE3bDcuNTktNy41OUwxOSA4bC05IDl6Ii8+PC9zdmc+`) !important;
        }
        .verry-blue{
            width: 35px;
            height: 35px;
            fill: #5dbff6;
            position: relative;
            top: -8px;
            left: -8px;
            padding: 3px;
            border: 2px solid #5dbff6;
            border-radius: 50%;
            background: white;
        }
        .ml-10{
            margin-left: 10px;
        }
    </style>
    <section  id="flagU" class="confer-blog-details-area section-padding-100-0" data-flag="{{$flagU}}">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="travel-content">
                        {{--  sửa thông tin  --}}
                        {{--  <div class="info">
                            <table class="table">
                                <tr>
                                    <td>
                                        @if (Str::contains($email, 'guest'))
                                            <p class="text-danger">Please add email.</p>
                                        @else
                                            {{$email}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a id="editInfo" href="#"
                                           style="color: red;">{{Str::contains($email, 'guest') ? 'Add' : 'Edit'}}</a>
                                    </td>
                                </tr>
                            </table>
                        </div>  --}}

                        <div class="event-info">
                            {{--  <h3 style="padding-bottom: 0;">{{$event->name}}</h3>  --}}
                            {{-- <img src="{{$event->banner_url}}" alt="{{$event->name}}"> --}}
                            <div class="text-center">
                                <img style="width: 100%;" src="{{$event->banner_url}}" alt="{{$event->name}}">
                            </div>
                        </div>
        
                        <div class="pd-25 text-center">
                            
                           
                            
                            {{--  nếu user chưa đăng kí event thì không được claim session  --}}
                            @if(!$eventUserTicket)
                                <a class="w-100 bg-danger text-center btn mt-2 mb-2 btn  btn--order" href="https://{{ env('SUB_EVENT') }}.{{ env('APP_URL') }}/event/{{ $event->id }}">Please register event and checkin event first</a>
                            @endif
                           
                            {{--  nếu user này đã claim rồi thì hiển thị link claim của họ  --}}
                            @if(!empty($nftUserClaimSession) && $eventUserTicket)
                                <input id="user_claim" value="true" type="hidden">
                                <a class="link-primary w-100 text-center btn mt-2 mb-2 btn  btn--order" target="_blank" href="#">Claimed succesfully</a>
                                <br>
                                <a class="text-center btn mt-2 mb-2 txt-exploder-link" id="button-claim-link" target="_blank" href="https://suiscan.xyz/{{  env("TYPE_NETWORK");  }}/tx/{{ $nftUserClaimSession->digest }}">SUI Explorer Link</a>

                            @endif
                            
                            {{--  nếu user chưa claim event và checkin event rồi thì claim  --}}
                            @if(empty($nftUserClaimSession) && $eventUserTicket)
                                @vite('resources/js/claim-session.js')
                                <input id="address_organizer" value="{{ $nftSessionNotClaim->address_organizer ?? '' }}" type="hidden">
                                <input id="address_nft" value="{{ $nftSessionNotClaim->address_nft ?? '' }}" type="hidden">
                                <input id="seed" value="{{ $nftSessionNotClaim->seed ?? '' }}" type="hidden">
                                <input id="user_address" value="{{ auth()->user()->wallet_address }}" type="hidden">
                                <input id="nft_id" value="{{ $nftSessionNotClaim->task_event_detail_nft_mint_id ?? '' }}" type="hidden">
                                <input id="session_id" value="{{ $nftSessionNotClaim->task_event_detail_id ?? '' }}" type="hidden">
                                <input id="task_id" value="{{ $nftSessionNotClaim->task_id ?? '' }}" type="hidden">
                                <input id="booth_id" value="{{ $nftSessionNotClaim->booth_id ?? '' }}" type="hidden">
                                <input id="email_login" value="{{ auth()->user()->email ?? '' }}" type="hidden">
                                <input id="address_ticket_id" value="{{ $checkUserNft->address_nft ?? '' }}" type="hidden">
                                <input id="address_nft_min" value="{{ $nftSessionNotClaim->address_nft ?? '' }}" type="hidden">
                                <input id="contract_event_id" value="{{ $event->contract_event_id }}" type="hidden">
                                <input id="task_event_id" value="{{ $nftSessionNotClaim->task_event_id }}" type="hidden">
                                <input id="contract_task_events_details_id" value="{{ $nftSessionNotClaim->contract_task_events_details_id }}" type="hidden">
                                
                                {{--  <button id="button-claim" type="button" class="btn btn-primary btn--order">Claim</button>  --}}
                                {{--  <button id="button-claim-test" type="button" class="btn btn-primary btn--order">Claim test</button>  --}}
                                
                            @endif
        
                            {{--  <button id="claim">Claim</button>  --}}
        
                        </div>
                        <div class="infor-data">
                            <h2>
                                <span class="tanger"></span>
                                <b class="size-submited ml-10">{{ $sessions->name }}</b>
                                <div class="line">
                                </div>
                            </h2>
                            <div>
                                {!! $sessions->description !!}
                            </div>
                        </div>
                        {{--  <ul class="nav nav-tabs">
                            <li><a data-toggle="tab" href="#sesion">Sessions Game</a></li>
                        </ul>  --}}
        
                       
        
                        {{--  <div class="event-info" style="border-top: 0; margin-top: 15px;">
                            <div class="app text-center">
                                <a href="https://apps.apple.com/us/app/plats/id1672212885" style="padding-right: 20px;"
                                   target="_blank">
                                    <img style="width: 150px;" src="{{url('/')}}/events/apple-store.svg">
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=network.plats.action" target="_blank">
                                    <img style="width: 150px;" src="{{url('/')}}/events/ggplay.svg">
                                </a>
                            </div>
                        </div>  --}}

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tab-content mt-2 infor-data">
                        <div id="sesion" class="tab-pane in active">
                            <div class="item">
                                <h2 class="text-center">
                                    <b class="fs-title">How to participate in the travel game?</b>
                                </h2>
                                <br>
                                <p class="text-center">
                                    <b>
                                        Engage in sessions and scan QR codes to collect your lucky numbers. The more numbers you gather, the higher your chances of grabbing incredible rewards!
                                    </b>
                                </p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="tab-content mt-2 infor-data">
                        <div id="sesion" class="tab-pane in active">
                          
                                @php
                                    //$codes = $userCode->where('user_id', $userId)
                                        //->where('travel_game_id', $session->id)
                                        //->where('task_event_id', $session_id)
                                        //->where('type', 0)
                                        //->pluck('number_code')
                                        //->implode(',');
                                    //$sTests = [];
                                   
                                @endphp
                                <div class="item">
                                    <h2 class="text-center">
                                        <b class="size-submited">Travel Game</b>
                                        <div class="line">
                                        </div>
                                    </h2>
                                
                                    <p>
                                        <b class="size-submited">Lucky Code:</b>
                                        <span class="fs-green">{{ $listLuckyCode ? $listLuckyCode : '1'}}</span>
                                    </p>
    
                                    <p>
                                        <strong>
                                            <b class="size-submited">Joined:</b>
                                            <span style="color:#00b4ff">{{$totalCompleted}}</span> / {{  count($sessions['detail']) }}
                                            sessions
                                        </strong>
                                    </p>
                                    <p>
                                        <strong>
                                            <b class="size-submited">Agenda:</b>
                                        </strong>
                                    </p>
                                    {{--  @if(false)
                                        <p><strong>Prize drawing time:</strong> {{dateFormat($session->prize_at)}}</p>
                                        <p><strong>Position:</strong> Main Stage</p>
                                        <p><strong>Reward:</strong></p>
    
                                        <p style="padding-left: 15px; line-height: 20px;">
                                            @foreach($sTests as $item)
                                                @if($item)
                                                    {!! '➤ '.$item.'<br>' !!}
                                                @endif
                                            @endforeach
                                        </p>
                                    @endif  --}}
                                </div>
                                <div class="timeline-container">
                                   
                                    @foreach($groupSessions as  $itemDatas)
    
                                        {{--  <div id="day{{($loop->index+1)}}">&nbsp;</div>  --}}
                                        @if(false)
                                            <h3 class="step">{{$itemDatas && $itemDatas[0] ? $itemDatas[0]['travel_game_name'] : ''}}</h3>
                                        @endif
                                        <ul class="tl">
                                            @foreach($itemDatas as $item)
                                                <li class="tl-item {{ $item['flag'] ? '' : 'dashed'}}">
                                                    {{--  <div class="item-icon {{ $item['flag'] ? 'active-verry' : 'not__active'}}"></div>  --}}
                                                    <div class="item-icon {{ $item['flag'] ? 'active-verry' : 'not__active'}}">
                                                        @if($item['flag'])
                                                            <svg class="w-6 h-6 verry-blue" fill="#686583" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                        @endif
                                                    </div>
                                                    <div class="item-text">
                                                        <div class="item-title  {{$item['flag'] ? '' : 'not-active'}}">
                                                            <p class="f-bold">
                                                                {{--  {{Str::limit($item['name'], 50)}}  --}}
                                                                {{ $item['name'] }}
                                                            </p>
                                                        </div>
                                                        <br>
                                                        <div class="item-detail">
                                                            {{--  {{Str::limit($item['desc'], 20)}}  --}}
                                                            {{ $item['desc'] }}
                                                        </div>
                                                    </div>
                                                    {{--  @if ($item['date'])
                                                        <div class="item-timestamp">
                                                            {{$item['date']}}<br> {{$item['time']}}
                                                        </div>
                                                    @endif  --}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </section>

    @include('web.events._modal_nft', [
        'nft' => $nft,
        'url' => $url
    ])
  
    <script src="{{asset('js/sweetalert2@11.min.js')}}"></script>
  
    <script>
        //Check has param sucess_checkin
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 4500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
    </script>
    <div id="infoEditEmail" class="modal fade @if (Str::contains($email, 'guest')) show @endif" data-backdrop="static"
         data-keyboard="false">
        <style type="text/css">
            .text-danger, .error {
                color: red;
            }

            .btn--order {
                padding: 10px 30px;
                background: #3EA2FF;
                color: #fff;
                text-align: right;
            }
        </style>

        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size: 25px; text-align: center;">Register for Event Check-in</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="infoForm" method="POST" action="{{route('web.editEmail')}}">
                        @csrf
                        <input type="hidden" name="task_id" value="{{$event->id}}">
                        <div class="row" style="display: block;">
                            <div class="col-md-12">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="name"
                                    required>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="email"
                                    required>
                            </div>
                        </div>
                        <div class="text-center" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary btn--order">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script src="{{ url('js/index.umd.js') }}"></script>
   // <script src="https://auth.magic.link/sdk"></script>
    <script type="text/javascript" src="https://auth.magic.link/sdk/extension/solana"></script>
@endpush
