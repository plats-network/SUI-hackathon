@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">User by Event</h4>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h4 class="card-title mb-0">Search</h4> --}}
                    <div class="ms-auto">
                        <a href="{{route('cws.eventPreview', [
                            'id' => $id,
                            'tab' => 0,
                            'preview' => 1])}}"
                            class="btn btn-danger btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('cws.event.users', ['id' => $id])}}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="col-form-label">Name or Email</label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{request()->input('name') ?? ''}}"
                                        class="form-control"
                                        id="name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Vip/Normal</label>
                                <div class="form-group">
                                    @php
                                        $vips = [
                                            '' => 'Selected',
                                            '1' => 'Normal',
                                            '2' => 'Vip'
                                        ];
                                    @endphp
                                    <select name="vip" class="form-select">
                                        @foreach($vips as $k => $v)
                                            <option value="{{ $k }}" {{($k == request()->get('vip')) ? 'selected' : ''}}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" style="margin-top: 38px;">
                                <button type="submit" class="btn btn-primary w-md">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        @php
                            $vips = [
                                0 => 'Normal',
                                1 => 'Vip'
                            ];
                        @endphp
                        <div class="col-3">
                            <label class="col-form-label">Name <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" name="name" id="userName">
                            <input type="hidden" name="task_id" value="{{$id}}" id="taskId">
                            <p class="text-danger d-none" id="errorName">ss</p>
                        </div>
                        <div class="col-3">
                            <label class="col-form-label">Email <span class="text-danger">(*)</span></label>
                            <input type="text" class="form-control" name="email" id="userEmail">
                            <p class="text-danger d-none" id="errorEmail"></p>
                        </div>
                        <div class="col-2">
                            <label class="col-form-label">Set Vip</label>
                            <select name="type_prize" class="form-select" id="userVip">
                                @foreach($vips as $k => $v)
                                    <option value="{{ $k }}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <button
                                class="btn btn-primary btn-sm cUserVip"
                                style="margin-top: 43px;"
                                data-url="{{route('cws.createUser')}}">Save</button>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Lists Users</h5>
                    </div>
                    <div class="table-responsive">
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
                                @foreach($users as $user)
                                    @php
                                        $userInfo = eventInfo($user->id, $id);
                                    @endphp
                                    <tr>
                                        <td style="width: 5%;">
                                            <div class="avatar">
                                                <div class="product-img avatar-title img-thumbnail bg-soft-primary border-0">
                                                    <img src="{{imgAvatar($user->avatar_path)}}" class="img-fluid" alt="{{$user->name}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold" style="width: 10%;">{{$user->name}}</td>
                                        <td class="fw-semibold" style="width: 10%;">
                                            <p class="text-success" style="font-size: 11px">{{$user->email}}</p>
                                        </td>
                                        <td>
                                            {{$userInfo->is_checkin ? 'Checkin' : 'Not checkin'}}
                                        </td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                id="v_{{$id}}"
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
                                            {{dateFormat($user->created_at)}}
                                        </td>
                                        <td>
                                            {{dateFormat($user->updated_at)}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                {!! $users->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('.genCode').on('click', function(e) {
            var user_id = $(this).data('user-id'),
                id = $(this).data('id'),
                type = $(this).data('type');

            $.ajax({
                url: '/gen-code/'+id,
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: _token,
                    user_id: user_id,
                    type: type
                },
                success: function (data) {
                    if (data.message == 'OK') {
                        location.reload();
                    } else {
                        location.reload();
                    }
                },
                error: function (data) {
                    location.reload();
                }
            })
        })

        $('.userVip').on('click', function(e) {
            var id = $(this).data('id'),
                url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    location.reload();
                }
            })
        })

        $('.cUserVip').on('click', function(e) {
            var name = $('#userName').val(),
                email = $('#userEmail').val(),
                task_id = $('#taskId').val(),
                checkMail = checkEmail(email),
                vip = $('#userVip').val(),
                url = $(this).data('url');

            if (name == '' || email == '' || !checkMail) {
                if (name == '') {
                    $('#errorName').removeClass('d-none').html('Please input name');
                } else {
                    $('#errorName').addClass('d-none');
                }
                if (email == '') {
                    $('#errorEmail').removeClass('d-none').html('Please input email');
                } else {
                    $('#errorEmail').addClass('d-none');
                }
                if (email && !checkMail) {
                    $('#errorEmail').removeClass('d-none').html('Email not format');
                } else {
                    $('#errorEmail').addClass('d-none');
                }
            } else {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: _token,
                        name: name,
                        email: email,
                        task_id: task_id,
                        vip: vip
                    },
                    success: function (data) {
                        $.notify('Success', 'success');
                        setTimeout(function(e) {
                            location.reload();
                        }, 1000);
                    },
                    error: function (data) {
                        $.notify('Error', 'error');
                        setTimeout(function(e) {
                            location.reload();
                        }, 1000);
                    }
                })
            }
        })

        function checkEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }
    </script>
@endsection

