<div class="mb-3 row itemSponsorDetail" id="itemSponsor{{$indexImageItem}}">
    <hr>
    <label class="col-sm-12 col-form-label">
        Sponsor package {{$getInc}} <span class="text-danger fs-11">(Note: Fields marked with * are required)</span>
    </label>
    <input
        type="hidden"
        name="sponsor[detail][{{$indexImageItem}}][id]"
        value="">
    <input
        type="hidden"
        name="sponsor[detail][{{$indexImageItem}}][is_delete]"
        id="sponsorFlagDelete{{$indexImageItem}}"
        value="0">
    <div class="row" >
        <div class="col-sm-8">
            <label class="col-form-label">Name <span class="text-danger fs-11">(*)</span></label>
            <input
                type="text"
                placeholder="Name"
                class="form-control"
                id="sponsor[detail][{{$indexImageItem}}][name]"
                name="sponsor[detail][{{$indexImageItem}}][name]"
                value="">
        </div>
        <div class="col-sm-4">
            <label class="col-form-label">Price <span class="text-danger fs-11">(*)</span></label>
            <input
                type="number"
                placeholder="Price"
                class="form-control"
                id="sponsor[detail][{{$indexImageItem}}][price]"
                name="sponsor[detail][{{$indexImageItem}}][price]"
                min="0"
                max="1000000000000"
                value="">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10">
            <label class="col-form-label">Description <span class="text-danger fs-11">(optional)</label>
            <textarea
                class="form-control"
                name="sponsor[detail][{{$indexImageItem}}][description]"
                rows="3"></textarea>
        </div>
        <div class="col-sm-2 text-right mt-5">
            <div class="col-auto">
                <button type="button" data-id="{{$indexImageItem}}" class="btn btn-danger mb-3 spRemove">Remove</button>
            </div>
        </div>
    </div>
</div>
