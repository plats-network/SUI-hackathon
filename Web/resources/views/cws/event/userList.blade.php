@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">List Users</h4>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">List Users</h5>
                        <div class="ms-auto">
                            <a href="{{ route('cws.event.miniGame', ['id' => $id]) }}" class="btn btn-danger btn-sm mb-2 me-2">Back</a>
                        </div>
                    </div>

                    <table class="table table-bordered mb-0 table-responsive">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number codes</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="background-color: #3EA2FF; color: #fff;">
                                <td colspan="2">
                                    <strong>Total</strong>
                                </td>
                                <td colspan="3"><strong>{{count($userLists)}}</strong></td>
                            </tr>
                            @forelse($userLists as $k => $user)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$user['name']}}</td>
                                    <td>{{$user['email']}}</td>
                                    <td>{{$user['code']}}</td>
                                    <td>{{$user['created_at']}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <p class="text-danger text-center">No results!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
