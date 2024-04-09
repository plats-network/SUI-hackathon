@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Sponsors</h4>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Info</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"><strong>Event Name:</strong></div>
                        <div class="col-md-10">{{optional($sponsor->task)->name}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><strong>Sponsor Name:</strong> </div>
                        <div class="col-md-10">{{$sponsor->name}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><strong>Sponsor Description:</strong> </div>
                        <div class="col-md-10">{{$sponsor->description}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"><strong>Sponsor Price:</strong> </div>
                        <div class="col-md-10">${{number_format($sponsor->price)}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Lists</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>
                                        UserName
                                        <br>
                                        Email
                                    </th>
                                    <th>Sponsor Detail</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userSponsors as $k => $userSponsor)
                                    <tr>
                                        <td style="width: 5%;">{{$k+1}}</td>
                                        <td class="fw-semibold" style="width: 10%;">
                                            {{optional($userSponsor->user)->name}}
                                            <br>
                                            <p class="text-success" style="font-size: 11px">
                                                {{optional($userSponsor->user)->email}}
                                            </p>
                                        </td>
                                        <td>{{optional($userSponsor->sponsorDetail)->name}}</td>
                                        <td width="15%">${{number_format($userSponsor->amount)}}</td>
                                        <td>{{$userSponsor->note}}</td>
                                        <td>{{dateFormat($userSponsor->created_at)}}</td>
                                    </tr>
                                @empty
                                   <tr>
                                       <td colspan="6"><p style="color: red; text-align: center;">No results!</p> </td>
                                   </tr> 
                                @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                {{-- {!! $users->links() !!} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

