<div class="tab-pane {{$flagEmail ? 'active' : ''}}" id="edit-email" role="tabpanel">
    @include('layouts.flash')
    <form action="{{route('cws.changeEmail')}}" method="POST" id="editEmai">
        @csrf
        <input type="hidden" name="type" value="edit-email">
        <div class="row">                                                            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="ed-email">New email <span class="text-danger">*</span></label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="demo@gmail.com"
                        id="ed-email"
                        required >
                </div>
            </div>
        </div>
        @include('layouts._btn', ['name' => ''])
    </form>
</div>