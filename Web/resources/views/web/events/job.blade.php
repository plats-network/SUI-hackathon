@extends('web.layouts.event_app')

@section('content')
    @php
        $type = request()->get('type');
    @endphp
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
            color: #258CC7!important;
            font-weight: bold!important;
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
    </style>

    <section class="our-schedule-area bg-white section-padding-100">
        {{-- <h3>Ready to get started with Plats Network?</h3> --}}
        <div class="container" style="margin-top: 65px;">
            <div class="row">
                <div class="col-12">
                    <div class="schedule-tab list-job">
                        <p class="text-center" style="padding-bottom: 10px;">
                            <a class="back" href="{{route('job.getTravelGame', ['task_id' => $task_id, 'type' => $type])}}">{{'< Back to Event!'}}</a>
                        </p>
                        <p class="desc-prize">
                            <strong style="color: red;">➤ Không nên dùng Zalo để quét QR code, nên quét từ Camera</strong><br>
                            @if ($type == 0)
                                ➤ Quét QR code sau mỗi phiên thảo luận tại hội trường chính và hội trường builder, quét 2 phiên thảo luận nhận được 1 Mã số quay thưởng (MSQT).<br>
                                ➤ Hãy thu thập càng nhiều MSQT cơ hội trúng thưởng càng cao. <br>
                                (Scan 2 Session QR Code to receive 1 Lucky Code).
                            @else
                                ➤ Quét QR Code tại các gian hàng (booths), quét 5 booths nhận được 1 Mã số quay thưởng (MSQT). <br>
                                ➤ Riêng với Booth VIP NEAR, GFI, VBI thì chỉ cần quét 1 booth nhận được 1 MSQT. <br>
                                ➤ Hãy thu thập càng nhiều MSQT cơ hội trúng thưởng càng cao. <br>
                                (Scan 5 Booth QR Code to receive 1 Lucky Code).
                            @endif
                        </p>

                        <ul class="nav tab-job">
                            <li class="nav-item b1">
                                <a class="nav-link {{$type == 0 ? 'active-job' : ''}}" href="{{route('web.jobEvent', ['id' => $id, 'type' => 0])}}">Sessions Game</a>
                            </li>
                            <li class="nav-item b2">
                                <a class="nav-link {{$type == 1 ? 'active-job' : ''}}" href="{{route('web.jobEvent', ['id' => $id, 'type' => 1])}}">Travel Game</a>
                            </li>
                        </ul>

                        <style type="text/css">
                            .desc {
                                line-height: 20px;
                                background: antiquewhite;
                                color: #000;
                                border-radius: 10px;
                                padding: 10px;
                                margin-top: 20px;
                            }
                            .swal2-title {
                                margin: -32px 13px;
                            }

                            .back {
                                display: inline-block;
                                margin: 5px 0;
                                padding: 6px 35px;
                                background: aqua;
                                border-radius: 15px;
                                color: #000;
                            }

                            .tab {
                                padding: 10px 30px!important;
                            }
                        </style>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane {{$type == '0' ? 'fade show in active' : ''}}">
                            <div class="timeline-container">
                                @foreach($groupSessions as  $itemDatas)
                                    <div id="day{{($loop->index+1)}}">&nbsp;</div>
                                    <hr style="margin-bottom: 20px;">
                                    <h3 class="step">{{$itemDatas && $itemDatas[0] ? $itemDatas[0]['travel_game_name'] : ''}}</h3>
                                    <ul class="tl">
                                        @foreach($itemDatas as $item)
                                            <li class="tl-item {{ $item['flag'] ? '' : 'dashed'}}">
                                                <div class="item-icon {{ $item['flag'] ? '' : 'not__active'}}"></div>
                                                <div class="item-text">
                                                    <div class="item-title {{$item['flag'] ? '' : 'not-active'}}">
                                                        <p class="{{$item['flag'] ? 'ac-color' : ''}}">
                                                            {{Str::limit($item['name'], 50)}}
                                                        </p>
                                                    </div>
                                                    {{-- <div class="item-detail {{$item['flag'] ? 'ac-color' : ''}}">{{Str::limit($item['desc'], 20)}}</div> --}}
                                                </div>
                                                @if ($item['date'])
                                                    <div class="item-timestamp">
                                                        {{$item['date']}}<br> {{$item['time']}}
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane {{$type == '1' ? 'fade show in active' : ''}}" id="step2" role="tabpanel" aria-labelledby="booth-tab">
                            <div class="timeline-container">
                                @foreach($groupBooths as $itemDatas)
                                    <hr style="margin-bottom: 20px;">
                                    <h3 class="step d-none" style="display: none;">
                                        {{$itemDatas && $itemDatas[0] ? $itemDatas[0]['travel_game_name'] : ''}}
                                    </h3>
                                    <ul class="tl">
                                        @foreach($itemDatas as $item)
                                            <li class="tl-item {{ $item['flag'] ? '' : 'dashed'}}">
                                                <div class="item-icon {{ $item['flag'] ? '' : 'not__active'}}"></div>
                                                <div class="item-text">
                                                    <div class="item-title {{$item['flag'] ? '' : 'not-active'}}">
                                                        <p class="{{$item['flag'] ? 'ac-color' : ''}}">
                                                            {{Str::limit($item['name'], 50)}}
                                                        </p>
                                                    </div>
                                                    {{-- <div class="item-detail {{$item['flag'] ? 'ac-color' : ''}}">{{Str::limit($item['desc'], 20)}}</div> --}}
                                                </div>
                                                @if ($item['date'])
                                                    <div class="item-timestamp">
                                                        {{$item['date']}}<br> {{$item['time']}}
                                                    </div>
                                                @endif
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
@endsection
