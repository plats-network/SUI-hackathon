@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Edit User</h4>
    </div>
@endsection

@section('content')
    @viteReactRefresh
    @vite([
        'resources/js/addClient.jsx',
    ])
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-danger btn-sm mb-2 mr-5" style="margin-right: 10px;"
                   href="{{ route('cws.users') }}">Back</a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header plats-step">
                        <h4 class="card-title mb-0">Forms</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('cws.users.update', ['id'=>$user->id])}}" method="POST" id="form_user"
                              onSubmit="event.preventDefault();">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{$user->name}}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{$user->email}}" disabled>
                                    </div>
                                </div>
                                {{--                                <div class="col-md-12">--}}
                                {{--                                    <div class="mb-3">--}}
                                {{--                                        <label for="role" class="form-label">Role</label>--}}
                                {{--                                        <select class="form-select" id="role" name="role">--}}
                                {{--                                            <option value="">Select role</option>--}}
                                {{--                                            @foreach($roles as $key => $role)--}}
                                {{--                                                <option value="{{$key}}" {{$user->role == $key ? 'selected' : ''}}>{{$role}}</option>--}}
                                {{--                                            @endforeach--}}
                                {{--                                        </select>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                @if($user->role == CLIENT_ROLE)
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="wallet_address" class="form-label">Wallet Address</label>
                                            <input type="text" class="form-control" id="wallet_address"
                                                   name="wallet_address"
                                                   value="{{$user->wallet_address}}">
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" id="user_id" value="{{$user->id}}">
                            </div>
                            @if($user->role == CLIENT_ROLE)
                                <div id="add_button"></div>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        // $(document).ready(function () {
        //     $(document).on('click', '#submit_form', function () {
        //         $('#form_user').submit();
        //     });
        // });
    </script>
@endsection

{{-- @section('js')
@endsection --}}

