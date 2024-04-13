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
        23 => 23
    ];
@endphp

@vite([
    'resources/js/session.jsx',
    //{{--  'resources/js/formNft.jsx',
    //'resources/js/mintNftSession.jsx',  --}}
    'resources/js/connect_suit_session.jsx'
])
<div id="tabwizard2" class="wizard-tab">
    <div class="text-center mb-4">
        <h5>Sessions</h5>
    </div>
    <input type="hidden" name="sessions[id]" id="sessions[id]" value="{{$sessions->id}}">
    <input type="hidden" name="sessions[task_id]" id="sessions[task_id]" value="{{$event->id}}">

    <div class="row">
        <div class="col-6">
        </div>
        <div class="col-6">
            <p>Generated Session</p>
        </div>
    </div>
    <div class="row" style="height: auto; min-height: 400px">
        <div class="col-6" style="border-left: 1px;border-right: 1px solid;">
            <div class="row mb-3 col-12">
                <input type="text" class="form-control" value="{{$sessions->name}}" placeholder="Name"
                       id="sessions[name]" name="sessions[name]">
            </div>
            <div class="row mb-3 col-12">
                <div id="editor2"></div>
                <input type="hidden"
                       class="form-control"
                       id="sessions-description"
                       name="sessions[description]"
                       value="{{$sessions->description}}"/>
            </div>
            <div class="row mb-3 col-12">
                <div class="listRowSession" id="listRowSession">

                </div>
            </div>
        </div>
        <div class="col-6 append-nft-session-detail">

        </div>
    </div>
    <div class="row">
        <div class="col-6 d-flex flex-row-reverse" style="border-left: 1px;border-right: 1px solid;">
            <div class="p-2">
                <div id="list-nft-data">

                </div>
                {{--  <button id="btnAddItemSessionReact" type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add More</button>  --}}
                <button id="btnAddItemSession" type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add More</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 d-flex flex-row-reverse" style="border-left: 1px;border-right: 1px solid;">
            <div class="p-2">
                <div class="text-end">
                    <div id="nft_get" style="display: inline-block"></div>
                </div>
                <div id="GenItemSession">
                    
                </div>
                {{--  <button id="btnGenItemSession" type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2 me-2">Generate Session</button>  --}}
            </div>
        </div>
    </div>
    <div>
        @if ($isPreview)
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>QR</th>
                    <th>Total</th>
                    <th>Quiz</th>
                    <th>Click</th>
                    <th>QR <span class="text-danger">(ON/OFF)</span></th>
                    <th>Sort</th>
                </tr>
                </thead>
                <tbody id="list-session-id" data-s-ids="{{json_encode($sessions->detail->pluck('id')->toArray())}}">
                @foreach($sessions->detail as $k => $session)
                    @php
                        $qr = $session->getQrUrlAttribute();

                    @endphp
                    <tr>
                        <td width="5%">{{$k+1}}</td>
                        <td width="20%">{{$session->name}}</td>
                        <td width="20%">{!!$session->description!!}</td>
                        <td width="20%" class="text-center" data-url="{{$qr}}">
                            <p class="qr d-none" id="se-{{$session->id}}" data-se-url="{{$qr}}"></p>
                            <div class="d-none2 mb-3" style="width: 300px; height: 300px;" id="dse-{{$session->id}}"
                                 data-se-url="{{$qr}}"></div>
                            <a class="se-donw mt-3" data-id="{{$session->id}}" data-num="{{$k+1}}" data-name="session">Download</a>
                        </td>
                        <td width="5%">{{totalUserJob($session->id)}}</td>
                        <td width="5%">{{$session->is_question ? 'Yes' : 'No'}}</td>
                        <td width="5%"><a href="{{$qr}}" target="_blank">link</a></td>
                        <td width="10%">
                            <input
                                type="checkbox"
                                id="session_{{ $k+1 }}"
                                switch="none"
                                @if($session->status) checked @endif
                            >
                            <label class="job"
                                   data-id="{{$session->code}}"
                                   data-detail-id="{{$sessions->id}}"
                                   for="session_{{ $k+1 }}"
                                   data-on-label="On"
                                   data-off-label="Off">
                            </label>
                        </td>
                        <td width="20%">
                            <select
                                name="sort"
                                class="form-select sortUpdate"
                                data-id="{{$session->id}}"
                                data-url="{{route('api.upEventSort', ['id' => $session->id])}}">
                                @foreach($lists as $k => $v)
                                    <option value="{{ $k }}" {{$k == $session->sort ? 'selected' : ''}}>{{$v}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
