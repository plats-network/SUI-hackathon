@extends('web.layouts.event_app')

@section('content')
    <style>
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
    @vite('resources/js/claim.js')
{{--    @vite('resources/js/zklogin.js')--}}

    @php
        if (auth()->user() !== null){
            $userId = auth()->user()->id;
            $email = auth()->user()->email;
            $userCode = new App\Models\Event\UserCode();
        }
    @endphp
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
                                <a class="post-author" href="#"><i
                                        class="zmdi zmdi-account"></i> {{optional($event->author)->name}}</a>
                                <a class="post-author" href="#"><i class="zmdi zmdi-favorite-outline"></i> 8 Likes</a>
                            </div>
                            {!! $event->description !!}
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

                        <div class="post-tags-social-area mt-30 pb-5 d-flex flex-wrap align-items-center">
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
                        </div>
                        <div class="post-author-area d-flex align-items-center my-5">
                            <div class="author-avatar">
                                <img src="{{imgAvatar(optional($event->author)->avatar_path)}}"
                                     alt="{{optional($event->author)->name}}">
                            </div>
                            <div class="author-content">
                                <h5>{{optional($event->author)->name}}</h5>
                                <span>Client Service</span>
                                {{-- <p>OK</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div id="fixed" class="col-lg-4">
                    <div class="confer-sidebar-area mb-100">
                        <div class="single-widget-area">
                            <div class="post-author-widget">
                                @if ($checkMint)
                                    <a id="showModal" class="btn btn-info" href="#">Claim already</a>
                                    <a style="
    border: none;
    background: none;
    color: blue;
" class="link-primary" href="https://suiscan.xyz/testnet/tx/{{ !empty($nft) ? json_decode($nft->nft_res, true)['digest'] : '' }}">
                                        Solana Explorer Link
                                    </a>
                                @else
                                    @if ($nft)
{{--                                        <input id="address_organizer" value="{{ $nft->address_organizer }}" type="hidden">--}}
                                        <input id="digest_nft" value="{{ json_decode($nft->nft_res, true)['digest'] }}" type="hidden">
{{--                                        <input id="address_nft" value="{{ $nft->address_nft }}" type="hidden">--}}
{{--                                        <input id="seed" value="{{ $nft->seed }}" type="hidden">--}}
{{--                                        <input id="user_address" value="{{ auth()->user() ? auth()->user()->wallet_address : '' }}" type="hidden">--}}
                                        <input id="nft_id" value="{{ $nft->id }}" type="hidden">
{{--                                        <input id="email_login" value="{{ auth()->user() ? auth()->user()->email : '' }}" type="hidden">--}}
                                        <a style="display:none;" class="btn btn-info claim-success" href="#">Claim already</a>
                                        <a style="
border: none;
display:none;
background: none;
color: blue;
" class="link-primary sol-link" href="https://suiscan.xyz/testnet/tx/{{ !empty($nft) ? json_decode($nft->nft_res, true)['digest'] : '' }}">
                                            Suiet Explorer Link
                                        </a>
                                    @endif
{{--                                @php--}}
{{--                                dd(auth()->user());--}}
{{--                                @endphp--}}
                                    <a class="btn btn-info {{auth()->user() != null ? 'btn-claim-id' : 'showModal'}} {{ !$nft ? 'disabled' : '' }}" href="#" >Register event</a>
{{--                                    <a class="btn btn-info Google" href="#" >Google</a>--}}

                                @endif
                                <hr>
                            </div>
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.js"
            integrity="sha384-eI7PSr3L1XLISH8JdDII5YN/njoSsxfbrkCTnJrzXt+ENP5MOVBxD+l6sEG4zoLp"
            crossorigin="anonymous"></script>
    <script src="dashboard.js"></script></body>

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
    @if ($checkMint)
        <script>
            alert('Claim is success.')
        </script>
    @endif
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
    <script src="https://auth.magic.link/sdk"></script>
    <script type="text/javascript" src="https://auth.magic.link/sdk/extension/solana"></script>
@endpush
