@extends('cws.layouts.app')

@section('style')
    @uploadFileCSS
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{--Editor--}}
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css"/>
    <link rel="stylesheet" href="https://uicdn.toast.com/editor/latest/toastui-editor-viewer.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/styles/metro/notify-metro.min.css"/>
@endsection

@section('name_page')
    @viteReactRefresh
    @vite([
        'resources/js/mint.js'
    ])

    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">
            @if($isPreview)
                Preview
            @elseif($event->id)
                Edit
            @else
                Create
            @endif
            Event
        </h4>
    </div>
@endsection

@section('content')
    <style type="text/css">
        .qr {
            margin: 0 auto;
            display: block;
            width: 50px;
            height: 50px;
        }

        .se-donw, .bo-donw {
            cursor: pointer;
        }
        .user-event{
            padding: 35px;
            border: 1px solid #b4cae5;
            border-radius: 10px;
        }
        .user-event p {
            font-size: 15px;
            color: black;
            font-weight: bold;
        }

        .img-preview {
            width: 150px;
            min-height: 150px;
            max-height: auto;
            float: left;
            margin: 3px;
            padding: 3px;
            user-select: none;
            cursor: pointer;
            transition: all 1s;
        }

        .img-preview:hover{
            color: #18ac1c;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mt-25 {
            margin-top: 35px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            @if($event->id)
                <input id="eventIdHidden" value="{{ $event->id }}" type="hidden">
            @endif
            @if($isPreview)
                <div class="col-md-10">
                    @if(true)
                        <a class="btn btn-danger btn-sm mb-2 mr-5" style="margin-right: 10px;"
                           href="{{ route('cws.event.overview', ['id' => $event->id]) }}">Overview</a>

                        <a class="btn btn-danger btn-sm mb-2 mr-5 none" style="margin-right: 10px;"
                           href="{{ route('cws.event.miniGame', ['id' => $event->id]) }}">Mini Game</a>

                        <a class="ml-5 btn btn-primary btn-sm mb-2"
                           href="{{route('cws.event.users', ['id' => $event->id])}}">List User</a>
                    @endif
                    <a class="btn btn-info btn-sm mb-2 mr-5 none" target="_blank" style="margin-right: 10px;"
                       href="https://platsevent.web.app/reward-nft?id={{$event->id}}">Reward</a>


                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('cws.eventEdit', ['id' => $event->id]) }}"
                       class="btn btn-sm mb-2 btn-primary d-inline-flex align-items-center me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="icon icon-xs me-2 mt-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>

                        Edit Event</a>
                </div>
            @elseif ($event && $event->id)
                <div class="col-md-12">
                    <a class="btn btn-danger btn-sm mb-2 mr-5" style="margin-right: 10px;"
                       href="{{ route('cws.eventPreview', ['id' => $event->id, 'preview' => 1]) }}">Back</a>
                </div>
            @endif
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header plats-step">
                        <h4 class="card-title mb-0">Forms Steps</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                              id="post_form"
                              action="{{$is_update ? route('cws.eventUpdate', ['id' => $event->id]) : route('cws.eventStore')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $event->id }}">

                            {{-- Step --}}
                            @if(true)
                                @include('cws.event._step')
                            @endif

                            @if(true)
                                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navItemTab active" id="navItemTab0" data-step="0"
                                           aria-current="page" href="#">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link navItemTab " id="navItemTab1" data-step="1">NFT Ticket</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link navItemTab " id="navItemTab2" data-step="2">Session & Booth</a>
                                    </li>
                                    {{--  <li class="nav-item">
                                        <a class="nav-link navItemTab " id="navItemTab3" data-step="3">Booth</a>
                                    </li>  --}}
                                    @if($is_update)
                                        <li class="nav-item">
                                            <a class="nav-link navItemTab" id="navItemTab4" data-step="4" href="#">Check-in</a>
                                        </li>
                                    @endif
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a class="nav-link navItemTab " id="navItemTab{{$is_update? "4": "3"}}"--}}
                                    {{--                                           data-step="{{$is_update? "4": "3"}}">NFT</a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                    <li class="nav-item d-none">--}}
                                    {{--                                        <a class="nav-link navItemTab" id="navItemTab{{$is_update? "5": "4"}}"--}}
                                    {{--                                           data-step="{{$is_update? "5": "4"}}" href="#">CrowdSponsor</a>--}}
                                    {{--                                    </li>--}}
                                    @if($is_update)
                                        <li class="nav-item">
                                            <a class="nav-link navItemTab" id="navItemTab5" data-step="5" href="#">Users
                                                List</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link navItemTab" id="navItemTab6" data-step="6" href="#">Dashboard</a>
                                        </li>
                                    @endif

                                </ul>

                                <p></p>

                            @endif
                            <!-- wizard-nav -->
                            <div id="tabwizard0" class="wizard-tab">
                                <div class="text-center mb-4">
                                    <h5>Overview</h5>
                                    <p class="card-title-desc text-danger">(Please enter all marked cells *)</p>
                                </div>
                                <div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3 field-name">
                                                        {{-- <x-label>Name</x-label> --}}
                                                        <label for="basicpill-firstname-input" class="form-label">Name
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $event->name }}" required
                                                               class="form-control"
                                                               placeholder="[TECH-ED PROGRAM] Cross-chain: Sidechain Solutions & BAS Case Study"
                                                               id="name" name="name">
                                                        <div class="valid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3 field-address">
                                                        <label for="basicpill-firstname-input" class="form-label">Address
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" value="{{ $event->address }}"
                                                               class="form-control"
                                                               placeholder="30 Đường Văn Cao Hà Nội, Hà Nội"
                                                               id="address" name="address">
                                                        <div class="valid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none">
                                                <div class="col-lg-4">
                                                    <div class="mb-3 field-lat">
                                                        <input type="text" class="form-control" value="1"
                                                               id="lat-address" name="lat">
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-lg-4">
                                                    <div class="mb-3 field-lng">
                                                        <input type="text" class="form-control" value="1"
                                                               id="lng-address" name="lng">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3 field-order">
                                                        <input type="text" class="form-control" value="10" id="order">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 field-start_at">
                                                        <label for="basicpill-phoneno-input"
                                                               class="form-label">Start At <span
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            value="{{ dateFormat($event->start_at) }}"
                                                            placeholder="{{ dateFormat($event->start_at) ?? '2023-06-19 17:30' }}"
                                                            id="{{$isPreview ? '' : 'start_at'}}"
                                                            name="start_at">
                                                        <div class="valid-feedback"></div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-lg-6">
                                                    <div class="mb-3 field-end_at">
                                                        <label for="basicpill-email-input"
                                                               class="form-label">End At <span
                                                                class="text-danger">*</span></label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            value="{{ dateFormat($event->end_at) }}"
                                                            placeholder="{{ dateFormat($event->end_at) ?? '2023-06-19 17:30' }}"
                                                            id="{{$isPreview ? '' : 'end_at'}}"
                                                            name="end_at">
                                                        <div class="valid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--                                            <div class="row">--}}
                                            {{--                                                <div class="col-lg-6">--}}
                                            {{--                                                    <div class="mb-3 field-end_at">--}}
                                            {{--                                                        <label for="basicpill-email-input"--}}
                                            {{--                                                               class="form-label">Ticket Class <span--}}
                                            {{--                                                                class="text-danger">*</span></label>--}}
                                            {{--                                                        <input--}}
                                            {{--                                                            type="text"--}}
                                            {{--                                                            class="form-control ticket_class"--}}
                                            {{--                                                            value="Free"--}}
                                            {{--                                                            placeholder="The Ticket that what you want!!!"--}}
                                            {{--                                                            name="ticket_class">--}}
                                            {{--                                                        <div class="valid-feedback"></div>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group field-article-thumbnail">
                                                        <label for="article-thumbnail"
                                                               class="mb-3"><b>Banner</b></label>
                                                        <div>
                                                            <input type="hidden" id="article-thumbnail"
                                                                   class="empty-value"
                                                                   name="thumbnail">
                                                            <input type="file" id="w0"
                                                                   accept="image/png, image/gif, image/jpeg"
                                                                   name="_fileinput_w0"></div>
                                                        <div class="invalid-feedback">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <div class="row align-items-center">
                                                <div class="mb-3 col-1">
                                                    <input class="mint_number form-control" type="number" name="quantity" min="1" max="1000" style="display: none">
                                                </div>
                                                {{--                                                <div class="mb-3 col-5">--}}
                                                {{--                                                    <input type="file" id="w0" class="mint-image"--}}
                                                {{--                                                           accept="image/png, image/gif, image/jpeg"--}}
                                                {{--                                                           name="_fileinput_nft" style="display: none"/>--}}
                                                {{--                                                </div>--}}
                                                {{--                                                <div class="mb-3 col-2 d-flex justify-content-end">--}}
                                                {{--                                                    <button--}}
                                                {{--                                                        type="button"--}}
                                                {{--                                                        class="btn btn-primary w-sm ms-auto connect_wallet">Connect Wallet--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                    <button--}}
                                                {{--                                                        type="button"--}}
                                                {{--                                                        class="btn btn-primary w-sm ms-auto mint_nft" value="1" style="display: none">Mint NFT--}}
                                                {{--                                                    </button>--}}
                                                {{--                                                </div>--}}
                                                {{--                                                <div class="mb-3 col-5">--}}
                                                {{--                                                    <a href="#" style="display: none" id="mint-sol" class="link-primary">Solana Explorer</a>--}}
                                                {{--                                                </div>--}}
                                            </div>
                                            <div class="row nft-div-append">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label for="event_description" class="form-label">Description
                                                            <span class="text-danger">*</span></label>

                                                        <div id="editor"></div>
                                                        <input type="hidden" id="description" name="description"
                                                               value="{{ $event->description }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabwizard1" class="wizard-tab"  style="display: none;">
                                <div class="text-center mb-4">
                                    <h5>NFT Ticket</h5>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                    </div>
                                    <div class="col-6">
                                        <p>Generated NFT Ticket</p>
                                    </div>
                                </div>
                                <div class="row" style="height: auto; min-height: 400px">
                                    <div class="col-6 append-nft-ticket" style="border-left: 1px;border-right: 1px solid;">
                                        {{--                                        <div class="row mb-3 nft-ticket-div">--}}
                                        {{--                                            <div class="col-4">--}}
                                        {{--                                                <input type="file"--}}
                                        {{--                                                       accept="image/x-png, image/jpeg"--}}
                                        {{--                                                       style="display: none"--}}
                                        {{--                                                       class="image-file"--}}
                                        {{--                                                       id="image-file"--}}
                                        {{--                                                       name="file-image-nft"--}}
                                        {{--                                                />--}}
                                        {{--                                                <label for="image-file">--}}
                                        {{--                                                    <img class="image-label img-preview" src="https://static.vecteezy.com/system/resources/previews/007/567/154/original/select-image-icon-vector.jpg">--}}
                                        {{--                                                </label>--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <div class="col-4">--}}
                                        {{--                                                <div class="col-10 mt-20">--}}
                                        {{--                                                    <input type="text" required--}}
                                        {{--                                                           class="form-control nft_symbol"--}}
                                        {{--                                                           value="Standard">--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="col-10 mt-20">--}}
                                        {{--                                                    <input type="text" required--}}
                                        {{--                                                           class="form-control nft_name"--}}
                                        {{--                                                           placeholder="NFT Title"--}}
                                        {{--                                                           name="nft_name">--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <div class="col-2" style="margin-top: 50px">--}}
                                        {{--                                                <input type="number" required--}}
                                        {{--                                                       class="form-control nft_amount"--}}
                                        {{--                                                       value="1"--}}
                                        {{--                                                       name="nft_amount">--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <div class="col-2" style="margin-top: 50px">--}}
                                        {{--                                                <button type="button" class="btn-delete-nft-ticket btn btn-danger">Delete</button>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                    <div class="col-6 append-nft-detail">
                                        @if ($countNFT)
                                            @foreach($countNFT as $name)
                                                @php
                                                    $countNFTData = App\Models\NFT\NFTMint::where([
                                                        'task_id' => $eventId,
                                                        'type' => 1,
                                                        'nft_symbol' => $name
                                                    ])->get();
                                                    $digest = json_decode($countNFTData[0]->nft_res, true)['digest'];
                                                @endphp
                                                @if ($countNFTData)
                                                    <div class="row mb-3">
                                                        <div class="col-4">
                                                            <label for="image-file">
                                                                <img class="img-preview img-preview-nft" src="{{ !empty($countNFTData[0]->nft_uri) ? $countNFTData[0]->nft_uri : asset('/imgs/no-image.png') }}">
                                                            </label>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="col-10 mt-25">
                                                                <p class="class-ticket">{{ $countNFTData[0]->nft_symbol }}</p>
                                                            </div>
                                                            <div class="col-10 mt-20">
                                                                <p class="class-ticket">{{ $countNFTData[0]->nft_title }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="col-10 mt-25">
                                                                <p class="class-ticket">{{ $countNFTData[0]->nft_category }}</p>
                                                            </div>
                                                            <div class="col-10 mt-20">
                                                                <p class="class-ticket">{{ count($countNFTData) }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-2" style="margin-top: 50px">
                                                            <p class="class-ticket"><a href="https://suiscan.xyz/devnet/tx/{{$digest}}">TxHash</a></p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                {{--                                <div class="row">--}}
                                {{--                                    <div class="col-6 d-flex flex-row-reverse" style="border-left: 1px;border-right: 1px solid;">--}}
                                {{--                                        <div class="p-2">--}}
                                {{--                                            <button id="btnAddItemNft" type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add More</button>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="row">--}}
                                {{--                                    <div class="col-6 d-flex flex-row-reverse" style="border-left: 1px;border-right: 1px solid;">--}}
                                {{--                                        <div class="p-2">--}}
                                {{--                                            <button id="btnGenItemNft" type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2">Generate NFT Ticket</button>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                            <!-- Sessiom -->
                            @include('cws.event.forms._session-preview', [
                                'sessions' => $sessions,
                                'isPreview' => $isPreview,
                                'event' => $event,
                                'countNFTSession'=>$countNFTSession
                            ])

                            <!-- Booth -->
                            @include('cws.event.forms._booth-preview', [
                                'booths' => $booths,
                                'isPreview' => $isPreview,
                                'event' => $event
                            ])
                            {{--                            NFT--}}

                            {{--CheckIn--}}
                            @if($is_update)
                                <div id="tabwizard4" class="wizard-tab" style="display: none;">
                                    <div>
                                        <div class="text-center mb-4">
                                            <h5>Checkin</h5>
                                            <p class="card-title-desc text-success">
                                                - QR code register and Check-in
                                            </p>
                                            <div class="d-flex justify-content-center">
                                                <div class="text-center pl-10">
                                                    <div class="visible-print text-center algin-center">
                                                        <div class="row">
                                                            <div class="col-4"></div>
                                                            <div class="col-5">
                                                                <img
                                                                    src="data:image/png;base64, {!! $qrCode !!}"
                                                                    alt="QR Code" style="max-width: 400px;">
                                                            </div>
                                                        </div>

                                                        <p class="card-title-desc text-success">Scan QR code (Scan Tiket of Users participating in the event)</p>
                                                        <p class="card-title-desc text-success">URL Checkin Event: <a
                                                                target="_blank"
                                                                href="{{$urlAnswers}}">{{$urlAnswers}}</a></p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div>

                                        </div>

                                    </div>
                                </div>
                            @endif

                            {{--NFT--}}
                            {{--                            <div id="tabwizard{{$is_update ? '4':'3'}}" class="wizard-tab" style="display: none;">--}}
                            {{--                                <div>--}}
                            {{--                                    <div class="text-center mb-4">--}}
                            {{--                                        <h5>NFT Setting</h5>--}}
                            {{--                                        <p class="card-title-desc text-success">--}}
                            {{--                                            - Create NFT--}}
                            {{--                                        </p>--}}

                            {{--                                    </div>--}}

                            {{--                                </div>--}}
                            {{--                                <div class="row">--}}
                            {{--                                    <div class="col-lg-8">--}}
                            {{--                                        <div class="mb-3 field-name">--}}

                            {{--                                            <label for="nft[name]" class="form-label">Collection Name <span--}}
                            {{--                                                    class="text-danger">*</span></label>--}}
                            {{--                                            <input type="text" value="{{$nftItem->name}}" sponsor class="form-control"--}}
                            {{--                                                   placeholder="" id="nft[name]" name="nft[name]" aria-invalid="false">--}}
                            {{--                                            <div class="valid-feedback"></div>--}}
                            {{--                                        </div>--}}
                            {{--                                        Collection Description--}}

                            {{--                                        <div class="mb-3">--}}
                            {{--                                            <label for="nft[description]" class="form-label">Collection--}}
                            {{--                                                Description</label>--}}
                            {{--                                            <textarea class="form-control" id="nft[description]" name="nft[description]"--}}
                            {{--                                                      rows="3">{{$nftItem->description}}</textarea>--}}
                            {{--                                        </div>--}}

                            {{--                                        <div class="mb-3 field-name">--}}

                            {{--                                            <label for="nft[size]" class="form-label">Collection Size <span--}}
                            {{--                                                    class="text-danger">*</span></label>--}}
                            {{--                                            <input type="text" value="{{$nftItem->size}}" class="form-control"--}}
                            {{--                                                   placeholder="" id="nft[size]" name="nft[size]"--}}
                            {{--                                                   data-listener-added_7f51dd21="true" aria-invalid="false">--}}
                            {{--                                            <div class="valid-feedback"></div>--}}
                            {{--                                        </div>--}}

                            {{--                                        <div class="mb-3 field-name">--}}

                            {{--                                            <label for="nft[blockchain]" class="form-label">Blockchain <span--}}
                            {{--                                                    class="text-danger">*</span></label>--}}
                            {{--                                            <select class="form-select" id="nft[blockchain]" name="nft[blockchain]"--}}
                            {{--                                                    aria-label="Default select example">--}}
                            {{--                                                @foreach ($allNetwork as $key => $item)--}}
                            {{--                                                    <option--}}
                            {{--                                                        value="{{ $key }}" {{ ($nftItem->blockchain == $key) ? 'selected' : '' }}>{{ $item }}</option>--}}
                            {{--                                                @endforeach--}}
                            {{--                                            </select>--}}
                            {{--                                            <div class="valid-feedback"></div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="mb-3">--}}
                            {{--                                            <div class="form-group field-article-thumbnail">--}}
                            {{--                                                <label for="article-thumbnail" class="mb-3"><b>Image</b></label>--}}
                            {{--                                                <div>--}}
                            {{--                                                    <input type="hidden" id="article-thumbnail2" class="empty-value"--}}
                            {{--                                                           name="thumbnail_nft">--}}
                            {{--                                                    <input type="file" id="w2" accept="image/png, image/gif, image/jpeg"--}}
                            {{--                                                           name="_fileinput_w2"></div>--}}
                            {{--                                                <div class="invalid-feedback">--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-lg-4">--}}
                            {{--                                        Payment Button--}}
                            {{--                                        @if($is_update)--}}
                            {{--                                            <a target="_blank"--}}
                            {{--                                               href="{{route('payment-link', ['nft_id' => $nftItem->id, 'event_id' => $event->id])}}"--}}
                            {{--                                               class="btn btn-info">Send Create NFT</a>--}}
                            {{--                                        @endif--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                            {{--                            <div id="tabwizard{{$is_update ? '5':'4'}}" class="wizard-tab" style="display: none">--}}
                            {{--                                @include('cws.event.forms._sponsor', [--}}
                            {{--                                   'sessions' => $sessions,--}}
                            {{--                                   'isPreview' => $isPreview,--}}
                            {{--                                   'event' => $event--}}
                            {{--                               ])--}}
                            {{--                            </div>--}}
                            @if($is_update)

                                {{--User List--}}
                                <div id="tabwizard5" class="wizard-tab" style="display: none;">
                                    <div>
                                        <div class="text-center mb-4">
                                            <h5>User List</h5>
                                            <p class="card-title-desc text-success">
                                                - User Check-in
                                            </p>
                                        </div>
                                        <div>
                                            <div class="listUser" id="listUser">
                                                <table class="table table-bordered mb-0">
                                                    <thead class="table-light">
                                                    <tr>
                                                        <th>Avatar</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Task Done</th>
                                                        <th>Set Vip</th>
                                                        <th>Created</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($userCheckIn as $userItem)
                                                        @php
                                                            $userInfo = eventInfo($userItem->id, $eventId);
                                                        @endphp
                                                        <tr>
                                                            <td style="width: 5%;">
                                                                <div class="avatar">
                                                                    <div
                                                                        class="product-img avatar-title img-thumbnail bg-soft-primary border-0">
                                                                        <img src="{{imgAvatar($userItem->avatar_path)}}"
                                                                             class="img-fluid"
                                                                             alt="{{$userItem->name}}">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="fw-semibold"
                                                                style="width: 20%;">{{$userItem->name}}</td>
                                                            <td class="fw-semibold" style="width: 10%;">
                                                                <p class="text-success"
                                                                   style="font-size: 11px">{{$userItem->email}}</p>
                                                            </td>
                                                            <td class="fw-semibold" style="width: 10%;">
                                                                <p class="text-success"
                                                                   style="font-size: 11px">{{$userItem->phone}}</p>
                                                            </td>
                                                            <td class="fw-semibold" style="width: 10%;">
                                                                <p class="text-success"
                                                                   style="font-size: 11px">{{$userItem->taskDoneEvent($userItem->id, $select_session_id)}}</p>
                                                            </td>
                                                            <td>
                                                                
                                                                <input
                                                                    type="checkbox"
                                                                    id="v_{{$eventId}}"
                                                                    switch="none"
                                                                    @if($userInfo->is_vip) checked @endif>
                                                                <label
                                                                    class="userVip"
                                                                    data-id="{{$userInfo->id}}"
                                                                    for="v_{{$userInfo->id}}"
                                                                    data-on-label="On"
                                                                    data-off-label="Off"
                                                                    data-url="{{route('cws.setTicketVip', ['id' => $userInfo->id])}}"></label>
                                                            </td>
                                                            <td>
                                                                {{dateFormat($userItem->created_at)}}
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>


                                        </div>

                                    </div>
                                </div>

                                {{--Dashboard--}}
                                <div id="tabwizard6" class="wizard-tab" style="display: none;">
                                    <div>

                                        <div class="text-center mb-4">
                                            <h5>Dashboard</h5>
                                            <p class="card-title-desc text-success">
                                                - Statistical facts
                                            </p>
                                        </div>
                                        <div class="row">

                                            {{-- số người đăng kí --}}
                                            <div class="col-12">
                                                <h2>User</h2>
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="user-event">
                                                            <p>Number of participants in the event</p>
                                                            <div class="text-right">
                                                                <h2>{{ $countUser['userJoinEvent'] }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <div class="user-event">
                                                            <p>Number of event registrations</p>
                                                            <div class="text-right">

                                                                <h2>{{ $countUser['userRegisterEvent'] }}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- biểu đồ --}}
                                            <div class="col-12 mt-4">
                                                <div class="row">
                                                    <div class="col-6 col-md-6">
                                                        @if(!empty($booths['detail']))
                                                            <h2>Booth</h2>
                                                            <canvas class="my-4 w-100" id="boothChart" width="900"
                                                                    height="380">
                                                            </canvas>
                                                        @endif
                                                    </div>
                                                    <div class="col-6 col-md-6">
                                                        @if(!empty($sessions['detail']))
                                                            <h2>Session</h2>
                                                            <canvas class="my-4 w-100" id="sessionChart" width="900"
                                                                    height="380">
                                                            </canvas>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- danh sách user --}}
                                            <div class="col-12 mt-4">
                                                <h2>User Reward</h2>
                                                <div class="row">
                                                    <div class="table-responsive small col-lg-12 col-md-12 ms-sm-auto col-lg-12">
                                                        <table class="table table-bordered mb-0">
                                                            <thead class="table-light">
                                                            <tr>
                                                                <th>Avatar</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Checkin</th>
                                                                <th>Set Vip</th>
                                                                <th>Created</th>
                                                                <th>Updated</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($userCheckIn as $userItem)
                                                                @php
                                                                    $userInfo = eventInfo($userItem->id, $eventId);
                                                                @endphp
                                                                <tr>
                                                                    <td style="width: 5%;">
                                                                        <div class="avatar">
                                                                            <div
                                                                                class="product-img avatar-title img-thumbnail bg-soft-primary border-0">
                                                                                <img
                                                                                    src="{{imgAvatar($userItem->avatar_path)}}"
                                                                                    class="img-fluid"
                                                                                    alt="{{$userItem->name}}">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="fw-semibold"
                                                                        style="width: 10%;">{{$userItem->name}}</td>
                                                                    <td class="fw-semibold" style="width: 10%;">
                                                                        <p class="text-success"
                                                                           style="font-size: 11px">{{$userItem->email}}</p>
                                                                    </td>
                                                                    <td>
                                                                        {{$userInfo->is_checkin ? 'Checkin' : 'Not checkin'}}
                                                                    </td>
                                                                    <td>
                                                                        <input
                                                                            type="checkbox"
                                                                            id="v_{{$eventId}}"
                                                                            switch="none"
                                                                            @if($userInfo->is_vip) checked @endif>
                                                                        <label
                                                                            class="userVip"
                                                                            data-id="{{$userInfo->id}}"
                                                                            for="v_{{$userInfo->id}}"
                                                                            data-on-label="On"
                                                                            data-off-label="Off"
                                                                            data-url="{{route('cws.setTicketVip', ['id' => $userInfo->id])}}"></label>
                                                                    </td>
                                                                    <td>
                                                                        {{dateFormat($userItem->created_at)}}
                                                                    </td>
                                                                    <td>
                                                                        {{dateFormat($userItem->updated_at)}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif


                            {{-- Social --}}
                            {{--                            @include('cws.event.forms._social', [--}}
                            {{--                                'taskEventSocials' => $taskEventSocials,--}}
                            {{--                                'taskEventDiscords' => $taskEventDiscords,--}}
                            {{--                                'event' => $event--}}
                            {{--                            ])--}}

                            {{-- Quiz --}}
                            {{--                            @include('cws.event.forms._quiz', [--}}
                            {{--                                'quiz' => $quiz,--}}
                            {{--                                'isPreview' => $isPreview--}}
                            {{--                            ])--}}

                            <div class="d-flex align-items-start gap-3 mt-4">
                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-primary w-sm"
                                        id="prevBtn" onclick="nextPrev(-1)">
                                        Previous
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-primary w-sm ms-auto"
                                        id="nextBtn" onclick="nextPrev(1)">Next
                                    </button>
                                </div>


                                @if($isPreview == false)
                                    <div id="subForm" class="w-sm ms-auto d-none">
                                        <a class="btn btn-secondary w-sm ms-auto" href="{{route('cws.eventList')}}">Cancel</a>
                                        @if($event->id)
                                            <button type="button" class="min-edit-btn btn btn-primary w-sm ms-auto">Save</button>
                                        @else
                                            <button type="button" class="submit-btn btn btn-primary w-sm ms-auto">Mint</button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="currentTab" value="{{$activeTab}}">

    {{--Modal Loading--}}
    <div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modal-default"
         aria-hidden="true">
        <div class="modal-dialog modal-primary modal-dialog-centered" role="document">
            <div class="modal-content bg-gradient-secondary">
                <div class="modal-body">
                    <div class="d-flex align-items-center">
                        <strong class="text-white">Uploading...</strong>
                        <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    {{--End Modal Loading--}}
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
    <script src="dashboard.js"></script>

    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places"></script>
    <script>
        $(document).ready(function () {
            $("#lat-address").addClass("d-none");
            $("#lng-address").addClass("d-none");
        });
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#lat-address').val(place.geometry['location'].lat());
                $('#lng-address').val(place.geometry['location'].lng());
                //Console log lat and lng
                console.log(place.geometry['location'].lat());
                console.log(place.geometry['location'].lng());

                //$("#latitudeArea").removeClass("d-none");
                //$("#longtitudeArea").removeClass("d-none");
            });
        }
    </script>

    @if($isPreview == false)
        <script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
    @else
        <script src="https://uicdn.toast.com/editor/latest/toastui-editor-viewer.js"></script>
    @endif
    <script src="https://uicdn.toast.com/editor/latest/i18n/ko-kr.js"></script>
    <script src="https://uicdn.toast.com/editor/latest/i18n/ja-jp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        var _token = $('meta[name="csrf-token"]').attr('content');
        var spinText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
        var fileAvatarInit = null;
        var flag_check = 1;
        var modalLoading = $('#modalLoading');
        var initial_form_state, last_form_state;
        var contentEditor = document.getElementById('description').value;
        var contentEditor2 = document.getElementById('sessions-description').value;
        var contentEditor3 = document.getElementById('booths-description').value;

        const editor = new toastui.Editor({
            el: document.querySelector('#editor'),
            previewStyle: 'vertical',
            language: 'en-US',
            initialEditType: 'wysiwyg', //markdown, wysiwyg
            height: '300px',

            initialValue: contentEditor,
            hooks: {
                addImageBlobHook(imgeBlob, callback) {
                    var fd = new FormData();
                    fd.append('file', imgeBlob);
                    modalLoading.modal("show");

                    $.ajax({
                        url: "{{route('uploadEditor')}}",
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        cache: false,

                        success: function (data) {
                            urlImage = data.url_full;
                            callback(urlImage, data.name)

                            modalLoading.modal("hide");
                        },
                        error: function () {
                            //alert("not so boa!");
                        }
                    });
                }
            }
        });

        const editor2 = new toastui.Editor({
            el: document.querySelector('#editor2'),
            previewStyle: 'vertical',
            language: 'en-US',
            initialEditType: 'wysiwyg', //markdown, wysiwyg
            height: '380px',
            initialValue: contentEditor2,
            hooks: {
                addImageBlobHook(imgeBlob, callback) {
                    var fd = new FormData();
                    fd.append('file', imgeBlob);
                    modalLoading.modal("show");
                    $.ajax({
                        url: "{{route('uploadEditor')}}",
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        cache: false,

                        success: function (data) {
                            urlImage = data.url_full;
                            callback(urlImage, data.name)

                            modalLoading.modal("hide");
                        },
                        error: function () {
                            //alert("not so boa!");
                        }
                    });
                    // ...
                }
            }
        });

        const editor3 = new toastui.Editor({
            el: document.querySelector('#editor3'),
            previewStyle: 'vertical',
            language: 'en-US',
            initialEditType: 'wysiwyg', //markdown, wysiwyg
            height: '380px',
            initialValue: contentEditor3,
            hooks: {
                addImageBlobHook(imgeBlob, callback) {
                    // write your code
                    //console.log(imgeBlob)
                    var fd = new FormData();
                    fd.append('file', imgeBlob);
                    //Show loading
                    modalLoading.modal("show");

                    $.ajax({
                        url: "{{route('uploadEditor')}}",
                        type: 'POST',
                        data: fd,
                        contentType: false,
                        processData: false,
                        cache: false,

                        success: function (data) {
                            urlImage = data.url_full;
                            callback(urlImage, data.name)

                            modalLoading.modal("hide");
                        },
                        error: function () {
                            //alert("not so boa!");
                        }
                    });
                    // ...
                }
            }
        });

    </script>

    <script>
        /*File Upload*/
        jQuery(document).ready(function ($) {
            var uploadUrl = '{{route('upload-storage-single', ['_token' => csrf_token()])}}';
            $('#flexCheckPaid').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#row_paid').show();
                } else {
                    $('#row_paid').hide();
                }
            });
            //start_at datepicker
            var option = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: 'en'
            };

            if ($('#start_at').length > 0) {
                $('#start_at').flatpickr(option);
            }

            //End_at datepicker
            if ($('#end_at').length > 0) {
                $('#end_at').flatpickr(option);
            }

            if ($('.date').length > 0) {
                $('.date').flatpickr(option);
            }

            var fileAvatarInit = null;
            var fileAvatarInit10 = null;
            // var fileSlideInit = null;
            @if($event->banner_url)
                fileAvatarInit = [{
                "path": "{{$event->banner_url}}",
                "base_url": "",
                "type": null,
                "size": null,
                "name": null,
                "order": null
            }];

            {{--fileAvatarInit10 = [{--}}
            {{--    "path": "{{$nftItem->image_url}}",--}}
            {{--    "base_url": "",--}}
            {{--    "type": null,--}}
            {{--    "size": null,--}}
            {{--    "name": null,--}}
            {{--    "order": null--}}
            {{--}];--}}
            @endif

            // fileSlideInit = [
            //     @foreach($task_galleries as $key=> $fileItem)
            //     {
            //         "path": "{{$fileItem['url']}}",
            //         "base_url": "",
            //         "type": null,
            //         "size": null,
            //         "name": null,
            //         "order": null
            //     } @if($key < $total_file-1), @endif
            //     @endforeach
            // ];

            //Update init image
            jQuery('#w0').yiiUploadKit({
                "url": uploadUrl,
                "multiple": false,
                "sortable": false,
                "maxNumberOfFiles": 1,
                "maxFileSize": 5000000,
                "minFileSize": null,
                "acceptFileTypes": /(\.|\/)(gif|jpe?g|png|webp)$/i,
                "files": fileAvatarInit,
                "previewImage": true,
                "showPreviewFilename": true,
                "errorHandler": "popover",
                "pathAttribute": "path",
                "baseUrlAttribute": "base_url",
                "pathAttributeName": "path",
                "baseUrlAttributeName": "base_url",
                "messages": {
                    "maxNumberOfFiles": "Số lượng tối đa của tệp vượt quá",
                    "acceptFileTypes": "Loại tệp không được phép",
                    "maxFileSize": "Tập tin quá lớn",
                    "minFileSize": "Tập tin quá nhỏ"
                },
                "start": function (e, data) {
                    console.log('Upload Start')
                },
                "done": function (e, data) {
                    console.log('Upload Done')
                },
                "fail": function (e, data) {
                    console.log('Upload Fail')
                },
                "always": function (e, data) {
                    console.log('Upload Alway')
                },
                "name": "thumbnail"
            });

            jQuery('#w2').yiiUploadKit({
                "url": uploadUrl,
                "multiple": false,
                "sortable": false,
                "maxNumberOfFiles": 1,
                "maxFileSize": 5000000,
                "minFileSize": null,
                "acceptFileTypes": /(\.|\/)(gif|jpe?g|png|webp)$/i,
                "files": fileAvatarInit10,
                "previewImage": true,
                "showPreviewFilename": true,
                "errorHandler": "popover",
                "pathAttribute": "path",
                "baseUrlAttribute": "base_url",
                "pathAttributeName": "path",
                "baseUrlAttributeName": "base_url",
                "messages": {
                    "maxNumberOfFiles": "Số lượng tối đa của tệp vượt quá",
                    "acceptFileTypes": "Loại tệp không được phép",
                    "maxFileSize": "Tập tin quá lớn",
                    "minFileSize": "Tập tin quá nhỏ"
                },
                "start": function (e, data) {
                    console.log('Upload Start')
                },
                "done": function (e, data) {
                    console.log('Upload Done')
                },
                "fail": function (e, data) {
                    console.log('Upload Fail')
                },
                "always": function (e, data) {
                    console.log('Upload Alway')
                },
                "name": "thumbnail_nft"
            });

            // jQuery('#w1').yiiUploadKit({
            //     "url": uploadUrl,
            //     "multiple": true,
            //     "sortable": false,
            //     "maxNumberOfFiles": 5,
            //     "maxFileSize": 10000000,
            //     "minFileSize": null,
            //     "acceptFileTypes": /(\.|\/)(gif|jpe?g|png|webp)$/i,
            //     "files": fileSlideInit,
            //     "previewImage": true,
            //     "showPreviewFilename": false,
            //     "errorHandler": "popover",
            //     "pathAttribute": "path",
            //     "baseUrlAttribute": "base_url",
            //     "pathAttributeName": "path",
            //     "baseUrlAttributeName": "base_url",
            //     "messages": {
            //         "maxNumberOfFiles": "Số lượng tối đa của tệp vượt quá",
            //         "acceptFileTypes": "Loại tệp không được phép",
            //         "maxFileSize": "Tập tin quá lớn",
            //         "minFileSize": "Tập tin quá nhỏ"
            //     },
            //     "start": function (e, data) {
            //         console.log('Upload Start')
            //     },
            //     "done": function (e, data) {
            //         console.log('Upload Done')
            //     },
            //     "fail": function (e, data) {
            //         console.log('Upload Fail')
            //     },
            //     "always": function (e, data) {
            //         console.log('Upload Alway')
            //     },
            //     "name": "Article[attachments]"
            // });
        });

        if ($('#list-booth-id').length > 0) {
            var boothIds = $('#list-booth-id').data('b-ids');

            if (boothIds.length > 0) {
                boothIds.forEach(function (item) {
                    var url = $("#bo-" + item).data('bo-url');
                    new QRCode(document.getElementById("bo-" + item), url);
                    new QRCode("dbo-" + item, {
                        text: url,
                        width: 300,
                        height: 300
                    });
                });
            }
            $('.bo-donw').on('click', function (e) {
                var id = $(this).data('id')
                num = $(this).data('num'),
                    name = $(this).data('name');
                let dataUrl = document.querySelector('#dbo-' + id).querySelector('img').src;
                downloadURI(dataUrl, name + '-' + num + '.png');
            });
        }

        if ($('#list-session-id').length > 0) {
            var sessionIds = $('#list-session-id').data('s-ids');
            if (sessionIds.length > 0) {
                sessionIds.forEach(function (item) {
                    var url = $("#se-" + item).data('se-url');
                    new QRCode(document.getElementById("se-" + item), url);
                    new QRCode("dse-" + item, {
                        text: url,
                        width: 300,
                        height: 300
                    });
                });
            }

            $('.se-donw').on('click', function (e) {
                var id = $(this).data('id')
                num = $(this).data('num'),
                    name = $(this).data('name');
                let dataUrl = document.querySelector('#dse-' + id).querySelector('img').src;
                downloadURI(dataUrl, name + '-' + num + '.png');
            })
        }

        function downloadURI(uri, name) {
            var link = document.createElement("a");
            link.download = name;
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            delete link;
        };
    </script>

    <script>
        jQuery(document).ready(function ($) {
            var modalDelete = $('#modalDelete');
            var uploadUrl = '{{config('admin.upload_url')}}';
            var flag_check = 1;

            $('#is_tweet').on('change', function (event) {
                if (event.currentTarget.checked) {
                    $('#tweetId').removeClass('d-none');
                } else {
                    $('#tweetId').addClass('d-none');
                }
            });

            $(document).on("submit", "#post_form", function (event) {
                //Get Editor content
                var content = editor.getHTML();//editor.getHTML(); getMarkdown
                var content2 = editor2.getHTML();//editor.getHTML();
                var content3 = editor3.getHTML();//editor.getHTML();
                $('[name=description]').attr('value', editor.getHTML());
                $('[id=sessions-description]').attr('value', editor2.getHTML());
                $('[id=booths-description]').attr('value', editor3.getHTML());
                $(window).off('beforeunload');
            });

            //Btn click submit post form
            $(document).on('click', '.btnSubmit', function (event) {
                $('#post_form').submit();
            });


            var isPreview = {{$isPreview}};
            //Check is preview page will disable all input
            if (isPreview) {
                //Disable all input
                $('input').attr('disabled', 'disabled');
                $('.mint_nft').attr('disabled', 'disabled');
                $('.connect_wallet').attr('disabled', 'disabled');
                $('textarea').attr('disabled', 'disabled');
                $('select').attr('disabled', 'disabled');
                $('input[type=checkbox]').removeAttr('disabled');

                if ($('#b-id').length > 0) {
                    $('#b-name').attr('disabled', false);
                    $('#b-desc').attr('disabled', false);
                    $('#b-nft').attr('disabled', false);
                    $('.sortUpdate').attr('disabled', false);
                }
            }

            //btnAddItemSession onclick call ajax
            $(document).on('click', '#btnAddItemSession', function (event) {
                var rowCount = $('.itemSessionDetail').length;
                if (rowCount >= 100) {
                    alert('{{__('Maximum number of Item is')}} 100');
                    return false;
                }
                //initImageWidge(1);
                flag_check = Math.floor(Math.random() * 10000);

                /*Ajax call get template*/
                $.ajax({
                    url: '{{route('cws.eventTemplate')}}' + '?from_reform=1&flag_check=' + flag_check + '&inc=' + rowCount,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        _token: _token,
                        id: flag_check
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            $('#listRowSession').append(data.html);
                            $('#q_' + (rowCount + 1)).addClass('sCheck');
                            flag_check++;
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            /*Delete Image Row*/
            $(document).on('click', '.sRemove', function (event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                //Swal confirm
                Swal.fire({
                    title: '{{__('Are you sure?')}}',
                    text: '{{__('You will not be able to recover this imaginary file!')}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('Yes, delete it!')}}',
                    cancelButtonText: '{{__('Cancel')}}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#itemSession' + id).hide();
                        $('#sessionsFlagDelete' + id).val(1);
                    }
                });

            });

            $(document).on('change', '.sCheck', function (event) {
                var id = $(this).data('id');

                if (event.currentTarget.checked) {
                    $('#s-' + id).removeClass('d-none');
                    // $('#s-'+id).val(true);
                } else {
                    $('#s-' + id).addClass('d-none');
                    // $('#s-'+id).val(false);
                }
            });
            // End Session


            $(document).on('change', '.sortUpdate', function (e) {
                var id = $(this).data('id'),
                    url = $(this).data('url'),
                    val = $(this).val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        _token: _token,
                        sort: val
                    },
                    success: function (data) {
                        // window.location.reload();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            $(document).on('click', '.bbEdit', function (e) {
                var id = $(this).data('id'),
                    name = $(this).data('name'),
                    desc = $(this).data('desc'),
                    nft = $(this).data('nft');
                $('#b-name').focus();
                $('#b-name').val(name);
                $('#b-id').val(id);
                $('#b-desc').val(desc);
                $('#b-nft').val(nft);
            });

            $(document).on('click', '.bbSave', function (e) {
                var id = $('#b-id').val(),
                    name = $('#b-name').val(),
                    desc = $('#b-desc').val(),
                    nft = $('#b-nft').val(),
                    url = $(this).data('url'),
                    url_reload = $(this).data('url-reload');

                if (name == '' || desc == '') {
                    if (name == '') {
                        $('#error-b-name').removeClass('d-none');
                    } else {
                        $('#error-b-name').addClass('d-none');
                    }

                    if (desc == '') {
                        $('#error-b-desc').removeClass('d-none');
                    } else {
                        $('#error-b-desc').addClass('d-none');
                    }
                } else {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            _token: _token,
                            id: id,
                            name: name,
                            desc: desc,
                            nft: nft
                        },
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }

            });


            $(document).on('click', '.jobVip', function (e) {
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            /*Start Booth*/
            $(document).on('click', '#btnAddItemBooth', function (event) {
                var rowCount = $('.itemBoothDetail').length;
                if (rowCount >= 100) {
                    alert('{{__('Maximum number of Item is')}} 100');
                    return false;
                }
                //initImageWidge(1);
                flag_check = Math.floor(Math.random() * 10000);
                /*Ajax call get template*/
                $.ajax({
                    url: '{{route('cws.eventTemplate')}}' + '?type=2&flag_check=' + flag_check + '&inc=' + rowCount,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        _token: _token,
                        id: flag_check
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            $('#listRowBooth').append(data.html);
                            $('#b_' + (rowCount + 1)).addClass('bCheck');
                            flag_check++;
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            /*Delete Image Row*/
            $(document).on('click', '.bRemove', function (event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                //Swal confirm
                Swal.fire({
                    title: '{{__('Are you sure?')}}',
                    text: '{{__('You will not be able to recover this imaginary file!')}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('Yes, delete it!')}}',
                    cancelButtonText: '{{__('Cancel')}}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#itemBooth' + id).hide();
                        $('#boothFlagDelete' + id).val(1);
                    }
                });

            });

            $(document).on('change', '.bCheck', function (event) {
                var id = $(this).data('id');

                if (event.currentTarget.checked) {
                    $('#b-' + id).removeClass('d-none');
                } else {
                    $('#b-' + id).addClass('d-none');
                }
            });
            // End Booth

            // Sponsor
            $(document).on('click', '#btnAddSponsor', function (event) {
                var flag_check = Math.floor(Math.random() * 10000);
                var rowCount = $('.itemSponsorDetail').length;
                $.ajax({
                    url: '{{route('cws.eventTemplate')}}' + '?type=4&flag_check=' + flag_check + '&inc=' + rowCount,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        _token: _token,
                        id: flag_check
                    },
                    success: function (data) {
                        console.log(data.html);
                        if (data.status == 200) {
                            $('#listSponsor').append(data.html);
                            flag_check++;
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            /*Delete Image Row*/
            $(document).on('click', '.spRemove', function (event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                //Swal confirm
                Swal.fire({
                    title: '{{__('Are you sure?')}}',
                    text: '{{__('You will not be able to recover this imaginary file!')}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('Yes, delete it!')}}',
                    cancelButtonText: '{{__('Cancel')}}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#itemSponsor' + id).hide();
                        $('#sponsorFlagDelete' + id).val(1);
                    }
                });

            });
            // End sponsor

            /*Quiz*/
            $(document).on('click', '#btnAddItemQuiz', function (event) {
                var rowCount = $('.itemQuizDetail').length;
                if (rowCount >= 20) {
                    alert('{{__('Maximum number of Item is')}} 20');
                    return false;
                }
                //initImageWidge(1);
                flag_check = Math.floor(Math.random() * 10000);
                /*Ajax call get template*/
                $.ajax({
                    url: '{{route('cws.eventTemplate')}}' + '?type=3&flag_check=' + flag_check + '&inc=' + rowCount,
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        _token: _token,
                        id: flag_check
                    },
                    success: function (data) {
                        if (data.status == 200) {
                            $('#listRowQuiz').append(data.html);
                            flag_check++;
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            /*Delete Item Quiz*/
            $(document).on('click', '.btnDeleteImageQuiz', function (event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                //Swal confirm
                Swal.fire({
                    title: '{{__('Are you sure?')}}',
                    text: '{{__('You will not be able to recover this imaginary file!')}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{__('Yes, delete it!')}}',
                    cancelButtonText: '{{__('Cancel')}}',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#itemQuiz' + id).hide();
                        //Find all input chrildren and remove required

                        //Remover required input
                        //Input quiz[8325][name]
                        $('#quiz[' + id + '][name]').removeAttr('required');
                        $('#quiz[' + id + '][time_quiz]').removeAttr('name');
                        $('#quiz[' + id + '][order]').removeAttr('name');
                        $('#quizFlagDelete' + id).val(1);
                    }
                });
            });
        });

        {{--var currentTab = {{$activeTab}}; // Current tab is set to be the first tab (0)--}}
        var currentTab = Number($('#currentTab').val());
        //arr index tab
        var arrTab = [0, 10, 20, 30, 40, 50];
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("wizard-tab");
            console.log(n);
            console.log(x);
            x[n].style.display = "block";

            //navItemTab  add class active
            $('.navItemTab').removeClass('active');
            $('#navItemTab' + n).addClass('active');

            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
                $('#nextBtn').addClass('d-none');
                $('#subForm').removeClass('d-none');
                //Type submit
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
                $('#nextBtn').removeClass('d-none');
                $('#subForm').removeClass('d-none');
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            //Todo check validate then next
            //Check if n = 1 and validate form before next
            if (validateForm(n) == false) {
                return false;
            } else {
                var x = document.getElementsByClassName("wizard-tab");
                let currentTab = Number($('#currentTab').val());
                x[currentTab].style.display = "none";
                currentTab = currentTab + n;
                $('#currentTab').val(currentTab);
                if (currentTab >= x.length) {
                    currentTab = currentTab - n;
                    x[currentTab].style.display = "block";
                }
                showTab(currentTab)
            }


        }

        //validateForm step
        function validateForm(step) {
            let isValid = true
            if (step == 1) {
                //Check name input is empty
                //Check empty input, name, lat, lng, address

                if ($('#name').val() == '' ||
                    $('#lat').val() == '' ||
                    $('#lng').val() == '' ||
                    $('#address').val() == '') {
                    //alert('Please input name');
                    //Trigger validation

                    $('#post_form').data('yiiActiveForm').submitting = true;
                    $('#post_form').yiiActiveForm('validate');


                    //Force focus input
                    $('#name').focus();
                    //Scroll to top
                    $('html, body').animate({
                        scrollTop: $("#name").offset().top - 250
                    });
                    isValid = false;
                }

                if ($('#address').val() == '') {
                }

            }

            return isValid;
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("list-item");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
            //navItemTab  add class active
            $('.navItemTab').removeClass('active');
            $('#navItemTab' + n).addClass('active');
        }

        //Check if edit not alow click
        @if($isPreview || $event->id)
        $(document).on('click', '.step-icon', function (event) {
            event.preventDefault();
            var id = $(this).attr('data-step');
            // alert(id);
            showTab(id);
            $('#tabwizard0').css('display', 'none');
            $('#tabwizard' + id).css('display', 'block');
            //navItemTab  add class active
            $('.navItemTab').removeClass('active');
            $('#navItemTab' + id).addClass('active');

            for (var i = 1; i <= 50; i++) {
                if (i != id) {
                    $('#tabwizard' + i).css('display', 'none');
                }
            }
        });
        @endif

        //Class navItemTab click active tab
        $(document).on('click', '.navItemTab', function (event) {
            event.preventDefault();
            //This add class active
            $('.navItemTab').removeClass('active');
            $(this).addClass('active');
            var id = $(this).attr('data-step');
            $('#currentTab').val(id);
            //alert(id)
            // alert(id);
            showTab(id);
            $('#tabwizard0').css('display', 'none');

            $('#tabwizard' + id).css('display', 'block');

            for (var i = 1; i <= 100; i++) {
                if (i != id) {
                    $('#tabwizard' + i).css('display', 'none');
                }
            }
        });

    </script>

    <script>

        // boothChart
        const ctxBooth = document.getElementById('boothChart')

        // eslint-disable-next-line no-unused-vars
        const dataBooth = {!! json_encode($booths['detail']) !!};

        const boothNames = [];
        const totalBoothUserJobs = [];

        // Lặp qua mỗi phần tử trong mảng JSON và đẩy tên của booth vào mảng boothNames và giá trị của totalUserJob vào mảng totalUserJobs
        dataBooth.forEach(function(booth) {
            let words = booth.name.split(' ');
            let truncatedName = words.length > 2 ? words.slice(0, 2).join(' ') + ' ...' : booth.name;
            boothNames.push(truncatedName);
            totalBoothUserJobs.push(booth.totalUserJob);
        });

        const boothChart = new Chart(ctxBooth, {
            type: 'bar',
            data: {
                labels: boothNames,
                datasets: [{
                    data: totalBoothUserJobs,
                    lineTension: 0,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: '#007bff',
                    borderWidth: 4,
                    pointBackgroundColor: '#007bff'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        boxPadding: 1
                    }
                }
            }
        });

        // sessionChart
        const ctxSession = document.getElementById('sessionChart')

        // eslint-disable-next-line no-unused-vars
        const dataSession = {!! json_encode($sessions['detail']) !!};

        const sessionNames = [];
        const totalSessionUserJobs = [];

        // Lặp qua mỗi phần tử trong mảng JSON và đẩy tên của session vào mảng sessionNames và giá trị của totalUserJob vào mảng totalUserJobs
        dataSession.forEach(function(session) {
            let words = session.name.split(' ');
            let truncatedName = words.length > 2 ? words.slice(0, 2).join(' ') + ' ...' : session.name;
            sessionNames.push(truncatedName);
            totalSessionUserJobs.push(session.totalUserJob);
        });

        const sessionChart = new Chart(ctxSession, {
            type: 'bar',
            data: {
                labels: sessionNames,
                datasets: [{
                    data: totalSessionUserJobs,
                    lineTension: 0,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: '#007bff',
                    borderWidth: 4,
                    pointBackgroundColor: '#007bff'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        boxPadding: 3
                    }
                },
                scales: {

                }
            }
        });
    </script>

    {{--validate--}}
    <script>
        //https://yii2-cookbook-test.readthedocs.io/forms-activeform-js/
        jQuery(function ($) {
            jQuery('#post_form').yiiActiveForm([
                {
                    "id": "name",
                    "name": "name",
                    "container": ".field-name",
                    "input": "#name",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                    }
                },
                /*address*/
                {
                    "id": "address",
                    "name": "address",
                    "container": ".field-address",
                    "input": "#address",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                    }
                },
                {
                    "id": "category_name",
                    "name": "category_name",
                    "container": ".field-category_name",
                    "input": "#category_name",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                    }
                },
                /*lat*/
                {
                    "id": "lat",
                    "name": "lat",
                    "container": ".field-lat",
                    "input": "#lat",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                        //Validate lat
                        if (value && (value < -90 || value > 90)) {
                            yii.validation.required(value, messages, {"message": "{{__('validation-inline.latitude') }}"});
                        }
                    }
                },
                //lng
                {
                    "id": "lng",
                    "name": "lng",
                    "container": ".field-lng",
                    "input": "#lng",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                        //Validate long
                        if (value && (value < -180 || value > 180)) {
                            yii.validation.required(value, messages, {"message": "{{__('validation-inline.longitude') }}"});
                        }
                    }
                },

                //start_date
                {
                    "id": "start_at",
                    "name": "start_at",
                    "container": ".field-start_at",
                    "input": "#start_at",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                        //Validate date check less than end_date
                        if (value && $('#end_at').val()) {
                            if (value > $('#end_at').val()) {
                                yii.validation.addMessage(messages, "{{__('validation-inline.start_date_less_than_end_date') }}");
                            }
                        }
                    }
                },
                //end_date
                {
                    "id": "end_at",
                    "name": "end_at",
                    "container": ".field-end_at",
                    "input": "#end_at",
                    "error": ".valid-feedback",
                    "validate": function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {"message": "{{__('validation-inline.required') }}"});
                        //Validate date check grater than end_date
                        if (value && $('#start_at').val()) {
                            if (value < $('#start_at').val()) {
                                yii.validation.addMessage(messages, "{{__('validation-inline.end_date_greater_than_start_date') }}");
                            }
                        }
                    }
                },

            ], []);
        });
    </script>
    <style>
        body #__sc__outer_modal {
            top: 100px
        }
    </style>
@endsection
@push('custom-scripts')
    <script src="{{ url('js/index.umd.js') }}"></script>
@endpush
