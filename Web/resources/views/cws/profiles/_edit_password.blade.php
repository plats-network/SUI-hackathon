<div class="tab-pane {{$flagPass ? 'active' : ''}}" id="edit-password" role="tabpanel">
    <form action="{{route('cws.changePassword')}}" method="POST">
        @csrf
        @include('layouts.flash')
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="old-pass">Current password <span class="text-danger">*</span></label>
                    <input
                        type="password"
                        name="old"
                        class="form-control"
                        placeholder="*******"
                        id="old-pass"
                        required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="*******"
                        id="password"
                        required />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="formrow-password-input">Password Confirmation <span class="text-danger">*</span></label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="*******"
                        id="password"
                        required />
                </div>
            </div>
        </div>
        @include('layouts._btn', ['name' => ''])
    </form>
</div>