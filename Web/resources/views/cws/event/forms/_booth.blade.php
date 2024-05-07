@php
    $lists = [
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27
    ];
@endphp
@vite([
    'resources/js/connect_suit_booth.jsx'
])
<div id="tabwizard3" class="wizard-tab">
    <div class="text-center mb-4">
        <h5>Booths</h5>
    </div>
    <input type="hidden" name="booths[id]" id="booths[id]" value="{{$booths->id}}">
    <input type="hidden" name="booths[task_id]" id="booths[task_id]" value="{{$event->id}}">
    <div class="row">
        <div class="col-6">
        </div>
        <div class="col-6">
            <p>Generated Booth</p>
        </div>
    </div>
    <div class="row" style="height: auto; min-height: 400px">
        <div class="col-6" style="border-left: 1px;border-right: 1px solid;">
            <div class="row mb-3 col-12">
                <input type="text" class="form-control" value="{{$booths->name ?? 'booth'}}" placeholder="Name" id="booths[name]" name="booths[name]"/>
            </div>
            <div class="row mb-3 col-12">
                <div id="editor3"></div>
                <input type="hidden" class="form-control" id="booths-description" name="booths[description]" value="{{$booths->description ?? 'test booth'}}" />
            </div>
            <div class="row mb-3 col-12">
                <div class="listRowBooth" id="listRowBooth">

                </div>
            </div>
        </div>
        <div class="col-6 append-nft-booth-detail">

        </div>
    </div>
    <div class="row">
        <div class="col-6 d-flex flex-row-reverse" style="border-left: 1px;border-right: 1px solid;">
            <div class="p-2">
                <button id="btnAddItemBooth" type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add More</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 d-flex flex-row-reverse" style="border-left: 1px;border-right: 1px solid;">
            <div class="p-2">
                <div id="btnGenItemBooth">

                </div>
                {{--  <button id="btnGenItemBooth" type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2">Generate Booth</button>  --}}
            </div>
        </div>
    </div>
    <div>
        <div class="row mt-3">
            <style type="text/css">
                .bg-input {
                    background-color: #fff !important;
                }
            </style>
            @if ($isPreview)
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                    <tr>
                        <th colspan="11">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="col-form-label">Name</label>
                                    <input type="hidden" name="id" id="b-id">
                                    <input class="form-control bg-input" type="text" name="name" id="b-name">
                                    <p class="text-danger d-none" id="error-b-name">Please imput name</p>
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-form-label">Description</label>
                                    <input class="form-control bg-input" type="text" name="name" id="b-desc">
                                    <p class="text-danger d-none" id="error-b-desc">Please imput cescription</p>
                                </div>
                                {{--                                    <div class="col-sm-4">--}}
                                {{--                                        <label class="col-form-label">Link NFT</label>--}}
                                {{--                                        <input class="form-control bg-input" type="text" name="name" id="b-nft">--}}
                                {{--                                    </div>--}}
                                <div class="col-sm-2">
                                    <a
                                        class="btn btn-primary bbSave"
                                        style="margin-top: 38px;"
                                        data-url="{{route('api.upEventDetail')}}"
                                        data-url-reload="{{route('cws.eventPreview', ['id' => $event->id, 'tab' => 3, 'preview' => 1])}}">Save</a>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>QR Code</th>
                        <th>Total</th>
                        <th>Vip</th>
                        <th>Click</th>
                        <th>QR <span class="text-danger">(ON/OFF)</span></th>
                        <th>NFT</th>
                        <th>Sort</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody id="list-booth-id" data-b-ids="{{json_encode($booths->detail->pluck('id')->toArray())}}">
                    @foreach($booths->detail as $k => $booth)
                        @php
                            $qr = 'http://'.config('plats.event').'/events/code?type=event&id='.$booth->code;
                        @endphp
                        <tr>
                            <td width="5%">{{$k+1}}</td>
                            <td width="15%">
                                {{$booth->name}}
                            </td>
                            <td width="20%">{!!$booth->description!!}</td>
                            <td width="15%" data-url="{{$qr}}" class="text-center">
                                <p class="qr d-none" id="bo-{{$booth->id}}" data-bo-url="{{$qr}}"></p>
                                <div class="d-none2" style="width: 300px; height: 300px;" id="dbo-{{$booth->id}}" data-bo-url="{{$qr}}"></div>
                                <a class="bo-donw" data-id="{{$booth->id}}" data-num="{{$k+1}}" data-name="booth">Download</a>
                            </td>
                            <td width="10%">{{totalUserJob($booth->id)}}</td>
                            <td>
                                <input
                                    type="checkbox"
                                    id="b_vip_{{ $k+1 }}"
                                    switch="none"
                                    @if($booth->is_required) checked @endif
                                >
                                <label class="jobVip"
                                       {{-- data-id="{{$booth->is_required}}" --}}
                                       {{-- data-detail-id="{{$booths->id}}" --}}
                                       for="b_vip_{{ $k+1 }}"
                                       data-on-label="On"
                                       data-url="{{route('api.upEventVip', ['id' => $booth->id])}}"
                                       data-off-label="Off">
                                </label>
                            </td>
                            <td width="10%"><a href="{{$qr}}" target="_blank">link</a></td>
                            <td width="5%">
                                <input
                                    type="checkbox"
                                    id="booth_{{ $k+1 }}"
                                    switch="none"
                                    @if($booth->status) checked @endif
                                >
                                <label class="job"
                                       data-id="{{$booth->code}}"
                                       data-detail-id="{{$booths->id}}"
                                       for="booth_{{ $k+1 }}"
                                       data-on-label="On"
                                       data-off-label="Off">
                                </label>
                            </td>
                            <td width="5%">{{$booth->nft_link ? 'Yes' : 'No'}}</td>
                            <td width="10%">
                                <select
                                    name="sort"
                                    class="form-select sortUpdate"
                                    data-id="{{$booth->id}}"
                                    data-url="{{route('api.upEventSort', ['id' => $booth->id])}}">
                                    @foreach($lists as $k => $v)
                                        <option value="{{ $k }}" {{$k == $booth->sort ? 'selected' : ''}}>{{$v}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <a
                                    class="btn btn-danger btn-sm bbEdit"
                                    data-id="{{$booth->id}}"
                                    data-name="{{$booth->name}}"
                                    data-desc="{{$booth->description}}"
                                    data-nft="{{$booth->nft_link}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
