@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">User</h4>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Search</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('cws.users')}}">
                        <div class="row">
                            <div class="col-md-2">
                                <div>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        placeholder="name"
                                        id="s_name">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div>
                                    <input type="text" class="form-control" placeholder="Enter City" id="formrow-inputCity">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div>
                                    <select id="formrow-inputState" class="form-select">
                                        <option selected>Choose...</option>
                                        <option>...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
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
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Lists Users</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Img</th>
                                    <th>
                                        Name
                                        <br>
                                        Email
                                    </th>
                                    <th>Events</th>
                                    <th>Status</th>
                                    <th>
                                        Created
                                        <br>
                                        Logined
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td style="width: 5%;">
                                            <div class="avatar">
                                                <div class="product-img avatar-title img-thumbnail bg-soft-primary border-0">
                                                    <img src="{{imgAvatar($user->avatar_path)}}" class="img-fluid" alt="{{$user->name}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold" style="width: 10%;">
                                            {{$user->name}}
                                            <p class="text-success" style="font-size: 12px;">{{$user->email}}</p>
                                        </td>
                                        <td width="30%">
                                            @foreach($user->user_events()->get() as $k => $event)
                                                @if ($k < 2)
                                                    <p class="mb-0 text-primary">{{optional($event->task)->name}}</p>
                                                @else
                                                    <p class="text-primary mb-0 d-none e-{{$user->id}}">{{optional($event->task)->name}}</p>
                                                @endif
                                            @endforeach

                                            @if($user->user_events()->count() > 2)
                                                <p class="text-center showEvent"
                                                    style="cursor: pointer;"
                                                    data-count="{{$user->user_events()->count()}}"
                                                    data-id="{{$user->id}}">Show More</p>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-soft-primary font-size-12">Pending</span></td>
                                        <td>
                                            {{dateFormat($user->created_at)}}
                                            <br>
                                            {{dateFormat($user->updated_at)}}
                                        </td>
                                        <td>
                                             <div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-horizontal"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('cws.users.edit', ['id'=>$user->id])}}">Edit</a>
{{--                                                    <a class="dropdown-item" href="#">Print</a>--}}
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div id="paging" class="mb-5">
                                    {!! $users->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.showEvent').on('click', function (e) {
                var count = $(this).data('count'),
                    id = $(this).data('id');

                for(var i=0; i <= count; i++) {
                    $('.e-'+id).removeClass('d-none');
                }
                $(this).addClass('d-none');
            });
        });
    </script>
@endsection

{{-- @section('js')
@endsection --}}

