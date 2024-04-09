@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Lists prize</h4>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Lists prize</h5>
                        <div class="ms-auto">
                            <a href="{{ route('cws.event.miniGame', ['id' => $id]) }}" class="btn btn-danger btn-sm mb-2 me-2">Back</a>
                        </div>
                    </div>

                    <table class="table table-bordered mb-0 table-responsive">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Travel Name</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Prize</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prizeLists as $k => $prize)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{optional($prize->travelGame)->name}}</td>
                                    <td>{{optional($prize->user)->name}}</td>
                                    <td>
                                        <p class="text-success" style="font-size: 11px; margin-bottom: 0;">
                                            {{optional($prize->user)->email}}
                                        </p>
                                    </td>
                                    <td>{{codePrize($prize->name_prize)}}</td>
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
