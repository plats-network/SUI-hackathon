<div class="mb-3 row itemBoothDetail itemBoothDetailMint" id="itemBooth{{$indexImageItem}}">
    <div class="col-4">
        <input type="file"
               accept="image/x-png, image/jpeg"
               style="display: none"
               class="image-file"
               id="image-file{{$indexImageItem}}"
               name="file-image-nft"
        />
        <label for="image-file{{$indexImageItem}}">
            <img class="image-label img-preview" src="{{ asset('/imgs/no-image.png') }}">
        </label>
    </div>
    <div class="col-6">
        <div class="col-10 mt-20">
            <input
                type="text"
                class="form-control name_booth"
                id="booths[detail][{{$indexImageItem}}][name]"
                name="booths[detail][{{$indexImageItem}}][name]"
                placeholder="Name {{$getInc}}"
                value="">
        </div>
        <div class="col-10 mt-20">
            <input
                type="text"
                class="form-control description_booth"
                id="booths[detail][{{$indexImageItem}}][description]"
                name="booths[detail][{{$indexImageItem}}][description]"
                placeholder="Describe {{$getInc}}"
                value="">
        </div>
        <div class="col-10 mt-20" hidden>
            <input
                type="hidden"
                class="form-control nft_address_booth"
                id="booths[detail][{{$indexImageItem}}][nft-address]"
                name="booths[detail][{{$indexImageItem}}][nft-address]"
                placeholder="nft-address"
                value="">
        </div>
        <div class="col-10 mt-20" hidden>
            <input
                type="hidden"
                class="form-control nft_uri_booth"
                id="booths[detail][{{$indexImageItem}}][nft-uri]"
                name="booths[detail][{{$indexImageItem}}][nft-uri]"
                placeholder="nft-uri"
                value="">
        </div>
        <div class="col-10 mt-20" hidden>
            <input
                type="hidden"
                class="form-control nft_res_booth"
                id="booths[detail][{{$indexImageItem}}][nft-res]"
                name="booths[detail][{{$indexImageItem}}][nft-res]"
                placeholder="nft-res"
                value="">
        </div>

    </div>
    <div class="col-2" style="margin-top: 50px">
        <button type="button" class="btn-delete-nft-ticket-booth btn btn-danger">Delete</button>
    </div>

    <div class="col-sm-3 mt-5">
        <input type="hidden" name="booths[detail][{{$indexImageItem}}][is_question]" value="0">
        <input
            class="form-check-input"
            data-id="{{$indexImageItem}}"
            type="hidden" value="1"
            name="booths[detail][{{$indexImageItem}}][is_required]"
            id="br_{{$getInc}}">
    </div>
    <div class="row">
        {{--        <div class="col-sm-8">--}}
        {{--            <label class="col-form-label">Link NFT <span class="text-danger fs-11">(optional)</span></label>--}}
        {{--            <input class="form-control" type="" name="booths[detail][{{$indexImageItem}}][nft_link]">--}}
        {{--        </div>--}}
    </div>

    {{-- <div class="col-sm-12 text-right">
        <label class="col-form-label">&nbsp;</label>
        <div class="col-auto">
            <button type="button" data-id="{{$indexImageItem}}" class="btn btn-danger mb-3 bRemove">Remove</button>
        </div>
    </div> --}}
</div>
