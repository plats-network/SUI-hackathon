    <div class="text-center mb-4">
        <h5>Crowd Sponsor</h5>
        <p class="card-title-desc text-danger">
            Sponsor package is a package that users can buy to become a sponsor of the event.
        </p>
    </div>
    <div>
        <input type="hidden" name="sponsor[id]" id="sponsor[id]" value="{{$sponsor->id}}">
        <input type="hidden" name="sponsor[task_id]" id="sponsor[task_id]" value="{{$event->id}}">
        <div class="row">
            <div class="col-lg-5">
                <div class="mb-3">
                    <label for="basicpill-pancard-input" class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{$sponsor->name}}" placeholder="Name" id="sponsor[name]" name="sponsor[name]">
                    <div class="valid-feedback"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="mb-3">
                    <label for="basicpill-vatno-input" class="form-label">Price Type</label>
                    <select class="form-control" name="sponsor[price_type]">

                        <option value="0">AZERO</option>

                        <option value="1">POLKADOT</option>

                        <option value="2">ACA </option>
                        @if(false)
                        <option value="0">USDT</option>
                        <option value="1">NEAR</option>
                        <option value="2">ADA</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="mb-3">
                    <label for="basicpill-vatno-input" class="form-label">Total Price</label>
                    <input
                        type="number"
                        class="form-control"
                        placeholder="0"
                        value="{{optional($sponsor)->price}}"
                        name="sponsor[price]"
                        min="0"
                        max="1000000000">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="mb-3">
                    <label for="basicpill-vatno-input" class="form-label">End At</label>
                    <input
                        type="text"
                        class="form-control date"
                        placeholder="{{ dateFormat($sponsor->end_at) ?? '2023-01-01 08:10' }}"
                        value="{{ dateFormat($sponsor->end_at) }}"
                        name="sponsor[end_at]">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-3">
                    <label for="basicpill-cstno-input" class="form-label">Description</label>
                    <textarea class="form-control" name="sponsor[description]" rows="5">{{$sponsor->description}}</textarea>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div id="listSponsor" style="padding-left: 150px;">
                @foreach($sponsor->sponsorDetails as $key => $detail)
                    <div class="mb-3 row itemSponsorDetail" id="itemSponsor{{$detail->id}}">
                        <hr>
                        <input
                            type="hidden"
                            name="sponsor[detail][{{$detail->id}}][id]"
                            value="{{$detail->id}}">
                        <input
                            type="hidden"
                            name="sponsor[detail][{{$detail->id}}][is_delete]"
                            id="sponsorFlagDelete{{$detail->id}}"
                            value="0">

                        <label class="col-sm-12 col-form-label">
                            Sponsor package {{$loop->index+1}} <span class="text-danger" style="font-size: 11px;"> (Note: Fields marked with * are required)</span>
                        </label>
                        <div class="row">
                            <div class="col-sm-8">
                                <label class="col-form-label">Name <span class="text-danger" style="font-size: 11px;">(*)</span></label>
                                <input
                                    type="text"
                                    placeholder="Name"
                                    class="form-control"
                                    id="sponsor[detail][{{$detail->id}}][name]"
                                    name="sponsor[detail][{{$detail->id}}][name]"
                                    value="{{$detail->name}}">
                            </div>

                            <div class="col-sm-4">
                                <label class="col-form-label">Price <span class="text-danger" style="font-size: 11px;">(*)</span></label>
                                <input
                                    type="number"
                                    placeholder=""
                                    class="form-control"
                                    id="sponsor[detail][{{$detail->id}}][price]"
                                    name="sponsor[detail][{{$detail->id}}][price]"
                                    min="0"
                                    max="1000000000000"
                                    value="{{$detail->price}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-{{$isPreview ? '12' : '11'}}">
                                <label class="col-form-label">Description <span class="text-danger" style="font-size: 11px;">(optional)</label>
                                <textarea class="form-control" name="sponsor[detail][{{$detail->id}}][description]">{{$detail->description}}</textarea>
                            </div>

                            @if (!$isPreview)
                                <div class="col-sm-1 text-right mt-5">
                                    <div class="col-auto">
                                        <button type="button" data-id="{{$detail->id}}" class="btn btn-danger mb-3 spRemove">Remove</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if (!$isPreview)
                <div class="row mt-3">
                    <div class="d-flex flex-row-reverse">
                        <div class="p-2">
                            <button
                                id="btnAddSponsor"
                                type="button"
                                class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                                <i class="mdi mdi-plus me-1"></i> Add More</button>
                        </div>
                    </div>
                    <hr>
                </div>
            @endif
        </div>
    </div>
