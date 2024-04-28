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
     'resources/js/statusSession.jsx'
 ])
<div id="tabwizard2" class="wizard-tab">
    <div class="text-center mb-4">
        <h5>Sessions</h5>
        <p class="card-title-desc text-danger">
            - In the event you organize, if there is a session, please create a session
            <br>
            - If you do not have a session, click "Next" to skip the step of creating a session
        </p>
    </div>
    <div>
        <input type="hidden" name="sessions[id]" id="sessions[id]" value="{{$sessions->id}}">
        <input type="hidden" name="sessions[task_id]" id="sessions[task_id]" value="{{$event->id}}">
        <div class="row">
            <div class="col-lg-9">
                <div class="mb-3">
                    <label for="basicpill-pancard-input" class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{$sessions->name}}" placeholder="Name"
                           id="sessions[name]" name="sessions[name]">
                    <div class="valid-feedback"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="mb-3">
                    <label for="basicpill-vatno-input"
                           class="form-label">Max job</label>
                    <input
                        type="number"
                        class="form-control"
                        placeholder="2"
                        value="{{$sessions->max_job}}"
                        id="sessions[max_job]"
                        name="sessions[max_job]"
                        min="0"
                        max="100">
                    <div class="valid-feedback"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="basicpill-cstno-input" class="form-label">Description</label>
                    <div id="editor2"></div>
                    <input type="hidden"
                           class="form-control"
                           id="sessions-description"
                           name="sessions[description]"
                           value="{{$sessions->description}}"/>
                </div>
            </div>
        </div>

        @if ($isPreview)
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>QR</th>
                    <th>Total</th>
                    {{--                    <th>Quiz</th>--}}
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
                        {{--                        <td width="5%">{{$session->is_question ? 'Yes' : 'No'}}</td>--}}
                        <td width="5%"><a href="{{$qr}}" target="_blank">link</a></td>
                        <td width="10%">
                            <StatusSessionComponent
                                id="{{ $session->code }}"
                                detailId="{{ $sessions->id }}"
                            />
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
        @else
            <div class="row mt-3">
                <div class="listRowSession" id="listRowSession" style="padding-left: 150px;">
                    @foreach($sessions->detail as $a => $sessionDetail)
                        <div class="mb-3 row itemSessionDetail" id="itemSession{{$sessionDetail->id}}">
                            <hr>
                            <input
                                type="hidden"
                                name="sessions[detail][{{$sessionDetail->id}}][id]"
                                id="sessions[detail][{{$sessionDetail->id}}][id]"
                                value="{{$sessionDetail->id}}">
                            <input
                                type="hidden"
                                name="sessions[detail][{{$sessionDetail->id}}][is_delete]"
                                id="sessionsFlagDelete{{$sessionDetail->id}}"
                                value="0">

                            <label class="col-sm-12 col-form-label">
                                Session {{$loop->index+1}}<span class="text-danger" style="font-size: 11px;"> (Note: Fields marked with * are required)</span>
                            </label>
                            <div class="row">
                                <div class="col-lg-4">
                                    <label class="col-form-label">Chọn travel game</label>
                                    <select class="form-select"
                                            name="sessions[detail][{{$sessionDetail->id}}][travel_game_id]">
                                        @foreach($travelGames as $game)
                                            <option value="{{$game->id}}"
                                                    @if($sessionDetail->travel_game_id == $game->id) selected @endif>{{$game->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 d-flex align-items-end">
                                    <button
                                        type="button"
                                        class="btn btn-primary w-sm ms-auto mint_nft" value="2">
                                        Mint NFT
                                    </button>
                                </div>
                                <div class="mb-3 col-5">
                                    <a href="#" style="display: none" id="mint-sol" class="link-primary">Solana Explorer</a>
                                </div>
                            </div>
                            <div class="row nft-div-append-session">
                            </div>
                            <div class="col-sm-4">
                                <label class="col-form-label">Name <span class="text-danger" style="font-size: 11px;">(*)</span></label>
                                <input
                                    type="text"
                                    placeholder="Name"
                                    class="form-control"
                                    id="sessions[detail][{{$sessionDetail->id}}][name]"
                                    name="sessions[detail][{{$sessionDetail->id}}][name]"
                                    value="{{$sessionDetail->name}}">
                            </div>
                            <div class="col-sm-4">
                                <label class="col-form-label">Description <span class="text-danger"
                                                                                style="font-size: 11px;">(optional)</label>
                                <input
                                    type="text"
                                    placeholder="Description"
                                    class="form-control"
                                    id="sessions[detail][{{$sessionDetail->id}}][description]"
                                    name="sessions[detail][{{$sessionDetail->id}}][description]"
                                    value="{{$sessionDetail->description}}">
                            </div>
                            <div class="col-sm-4 mt-5 d-none">
                                <input type="hidden" name="sessions[detail][{{$sessionDetail->id}}][is_required]"
                                       value="{{$sessionDetail->is_required}}">
                                <input
                                    class="form-check-input sCheck"
                                    data-id="{{$sessionDetail->id}}"
                                    type="checkbox"
                                    value="1"
                                    name="sessions[detail][{{$sessionDetail->id}}][is_question]"
                                    @if ($sessionDetail->is_question) checked @endif
                                    id="q_{{$sessionDetail->id}}">
                                <label class="form-check-label" for="q_{{$sessionDetail->id}}">
                                    Quiz <span class="text-danger" style="font-size: 11px;">(Yes/No)</span>
                                </label>
                            </div>

                            <div id="s-{{$sessionDetail->id}}" class="{{$sessionDetail->is_question ? '' : 'd-none'}}">
                                <div class="row mt-1">
                                    <div class="col-sm-12">
                                        <label class="form-check-label">Question</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="sessions[detail][{{$sessionDetail->id}}][question]"
                                            name="sessions[detail][{{$sessionDetail->id}}][question]"
                                            placeholder="Câu hỏi"
                                            value="{{$sessionDetail->question}}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-4">
                                        <label class="form-check-label">Answer 1</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="sessions[detail][{{$sessionDetail->id}}][a1]"
                                            name="sessions[detail][{{$sessionDetail->id}}][a1]"
                                            placeholder="Answer"
                                            value="{{$sessionDetail->a1}}">
                                    </div>
                                    <div class="col-sm-2 mt-4">
                                        <input
                                            class="form-check-input checkQ"
                                            data-id="{{$sessionDetail->id}}"
                                            type="checkbox" value="1"
                                            name="sessions[detail][{{$sessionDetail->id}}][is_a1]"
                                            @if ($sessionDetail->is_a1) checked @endif
                                            id="is_a1_{{$sessionDetail->id}}">
                                        <label class="form-check-label" for="is_a1_{{$sessionDetail->id}}">
                                            Yes/No
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-check-label">Answer 2</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="sessions[detail][{{$sessionDetail->id}}][a2]"
                                            name="sessions[detail][{{$sessionDetail->id}}][a2]"
                                            placeholder="Answer"
                                            value="{{$sessionDetail->a2}}">
                                    </div>
                                    <div class="col-sm-2 mt-4">
                                        <input
                                            class="form-check-input checkQ"
                                            data-id="{{$sessionDetail->id}}"
                                            type="checkbox" value="1"
                                            name="sessions[detail][{{$sessionDetail->id}}][is_a2]"
                                            @if ($sessionDetail->is_a2) checked @endif
                                            id="is_a2_{{$sessionDetail->id}}">
                                        <label class="form-check-label" for="is_a2_{{$sessionDetail->id}}">
                                            Yes/No
                                        </label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-4">
                                        <label class="form-check-label">Answer 3</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="sessions[detail][{{$sessionDetail->id}}][a3]"
                                            placeholder="Answer"
                                            value="{{$sessionDetail->a3}}">
                                    </div>
                                    <div class="col-sm-2 mt-4">
                                        <input
                                            class="form-check-input checkQ"
                                            data-id="{{$sessionDetail->id}}"
                                            type="checkbox" value="1"
                                            name="sessions[detail][{{$sessionDetail->id}}][is_a3]"
                                            @if ($sessionDetail->is_a3) checked @endif
                                            id="is_a3_{{$sessionDetail->id}}">
                                        <label class="form-check-label" for="is_a3_{{$sessionDetail->id}}">
                                            Yes/No
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-check-label">Answer 4</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="sessions[detail][{{$sessionDetail->id}}][a4]"
                                            name="sessions[detail][{{$sessionDetail->id}}][a4]"
                                            placeholder="Nội dung"
                                            value="{{$sessionDetail->a4}}">
                                    </div>
                                    <div class="col-sm-2 mt-4">
                                        <input
                                            class="form-check-input checkQ"
                                            data-id="{{$sessionDetail->id}}"
                                            type="checkbox" value="1"
                                            name="sessions[detail][{{$sessionDetail->id}}][is_a4]"
                                            @if ($sessionDetail->is_a4) checked @endif
                                            id="is_a4_{{$sessionDetail->id}}">
                                        <label class="form-check-label" for="is_a4_{{$sessionDetail->id}}">
                                            Yes/No
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <div class="col-auto">
                                    <button type="button" data-id="{{$sessionDetail->id}}"
                                            class="btn btn-danger mb-3 sRemove">Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mt-3">
                    <div class="d-flex flex-row-reverse">
                        <div class="p-2">
                            <button
                                id="btnAddItemSession"
                                type="button"
                                class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                                <i class="mdi mdi-plus me-1"></i> Add More
                            </button>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        @endif
    </div>
</div>