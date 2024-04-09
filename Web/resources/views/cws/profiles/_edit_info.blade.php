<div class="tab-pane {{$flagInfo ? 'active' : ''}}" id="edit-info" role="tabpanel">
    <form action="{{route('cws.changeInfo')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('layouts.flash')
        <div class="row">
            <div class="col-md-12 my-3">
                <label for="formFile" class="form-label">Avatar</label>
                <input class="form-control" name="avatar_path" type="file" id="formFile">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="fullname">Fullname <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="name"
                        value="{{old($user->name)}}"
                        class="form-control"
                        placeholder="{{$user->name}}"
                        id="fullname"
                        required />
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label" for="formrow-password-input">Gender <span class="text-danger">*</span></label>
                    <select class="form-select" name="gender" required>
                        <option value="1" @if (old('gender') == $user->gender) {{ 'selected' }} @endif>Male</option>
                        <option value="2" @if (old('gender') == $user->gender) {{ 'selected' }} @endif>Female</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="user-date" class="form-label">Date <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input
                            class="form-control"
                            name="birth"
                            type="date"
                            value="{{$user->birth ?? null}}"
                            id="user-date"
                            required />
                    </div>
                </div>
            </div>
        </div>
        @include('layouts._btn', ['name' => 'Save'])
    </form>
</div>
