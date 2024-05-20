@extends('cws.layouts.app')

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Event</h4>
    </div>
@endsection
@vite([
    //{{--  'resources/js/session.jsx',  --}}
    //{{--  'resources/js/formNft.jsx',
    //'resources/js/mintNftSession.jsx',  --}}
    'resources/js/statusEvent.jsx',
    'resources/js/connect_suit_create_event.jsx'
])
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Search</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('cws.eventList')}}" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="name"
                                        value="{{request()->get('name') ?? ''}}"
                                        placeholder="Name event">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    @php
                                        $statuses = [
                                            '' => 'Selected',
                                            '0' => 'Default',
                                            '1' => 'Public',
                                            '2' => 'Draft',
                                            '99' => 'Deleted'
                                        ];
                                    @endphp
                                    <select name="status" class="form-select">
                                        @foreach($statuses as $k => $v)
                                            <option value="{{ $k }}" {{($k == request()->get('status')) ? 'selected' : ''}}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input
                                        class="form-control flatpickr-input"
                                        type="text"
                                        name="start_at"
                                        value="{{request()->get('start_at') ?? ''}}"
                                        placeholder="Start At">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input
                                        class="form-control flatpickr-input"
                                        name="end_at"
                                        type="text"
                                        value="{{request()->get('end_at') ?? ''}}"
                                        placeholder="End At">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button
                                    class="btn btn-primary btn-gray-800 animate-up-2"
                                    type="submit">Search</button>
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
                        <h5 class="card-title">Lists events</h5>
                        <div class="ms-auto">
                            <div>

                            </div>
                            {{--  <div id="GenItemCreateEvent">

                            </div>  --}}
                            <a href="{{ route('cws.eventCreate') }}" class="btn  btn-primary d-inline-flex align-items-center me-2" ><svg class="icon icon-xs me-2 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Create Event</a>
                        </div>
                    </div>

                    <table class="table table-bordered mb-0 table-responsive">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Img</th>
                                <th>Name</th>
                                <th>Start - End</th>
                                <th>View</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $i => $event )
                                <tr>
                                    <td style="width: 5%;">{{ $i+1 }}</td>
                                    <td style="width: 10%;">
                                        <img src="{{ commonImg($event->banner_url) }}" alt="{{$event->name}}">
                                    </td>
                                    <td style="width: 20%;">
                                        <a
                                            href="{{ route('cws.eventPreview', [
                                                'id' => $event->id,
                                                'tab' => $tab,
                                                'preview' => 1
                                            ]) }}"
                                            title="{{$event->name}}"
                                            target="_blank">{{$event->name}}</a>
                                    </td>
                                    <td class="d-flex">
                                                <b class="text-success">{{ dateFormat($event->start_at) }}</b>
                                        &nbsp;
                                        -
                                        &nbsp;    <b class="text-danger">{{ dateFormat($event->end_at) }}</b>
                                    </td>
                                    <td>{{rand(100,1000)}}</td>
                                    <td>
                                        <div
                                            class="statusEvent"
                                            data-eventid="{{ $event->id }}"
                                            data-contracteventid="{{ $event->contract_event_id }}",
                                            data-status="{{ $event->status }}"
                                            data-switchid="switch_{{ $i+1 }}"
                                            >
                                        </div>

                                    </td>
                                    <td style="width: 20%;">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                @include('cws.actions.link', [
                                                    'url' => route('cws.eventPreview', [
                                                        'id' => $event->id,
                                                        'tab' => $tab,
                                                        'preview' => 1
                                                    ]),
                                                    'label' => 'Show',
                                                    'icon' => 'show'
                                                ])

                                                @include('cws.actions.link', [
                                                    'url' => route('cws.event.users', ['id' => $event->id]),
                                                    'label' => 'Users',
                                                    'icon' => 'user-plus'
                                                ])

                                                @if (isSponsor($event->id))
                                                    @include('cws.actions.link', [
                                                        'url' => route('cws.event.sponsor', ['id' => $event->id]),
                                                        'label' => 'Sponsor',
                                                        'icon' => 'stats'
                                                    ])
                                                @endif

                                                {{-- <a
                                                    href="{{ route('cws.eventPreview', [
                                                            'id' => $event->id,
                                                            'tab' => $tab,
                                                            'preview' => 1
                                                        ]) }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    class="px-2 text-primary"
                                                    data-bs-original-title="Show"
                                                    aria-label="Show">
                                                        <i class="bx bx-show font-size-18"></i>
                                                </a> --}}
                                                {{--  <a href="{{ route('cws.eventDelete', $event->id) }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    class="px-2 text-danger btnDeleteRow"
                                                    data-bs-original-title="Delete"
                                                    data-url="{{ route('cws.eventDelete', $event->id)}}"
                                                    data-id="{{ $event->id}}"
                                                    aria-label="Delete">
                                                    <i class="bx bx-trash-alt font-size-18"></i>
                                                </a>  --}}
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Confirm -->
    @include('cws.layouts.data.modal_confirm_delete')
@endsection

{{--Script--}}

@section('scripts')
    <script src="{{asset('js/sweetalert2@11.min.js')}}"></script>

    <script>
        var spinText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ';
        // var hasPage = {{Request::get('page', 0)}};

        jQuery(document).ready(function ($) {
            //start_at datepicker
            var option = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                locale: 'en'
            };
            $('.flatpickr-input').flatpickr(option);
            var modalDelete = $('#modalDelete');
            var btnDeleteRow = $('.btnDeleteRow');
            $(document).on('click', '.btnDeleteRow', function (event) {
                event.preventDefault();
                let urlDelete = $(this).attr('data-url');
                let id = $(this).attr('data-id');
                $('#formDeleteItem').attr('action', urlDelete);
                modalDelete.modal("show");
            });

            $(document).on('click', '#hideModalDelete', function (event) {
                modalDelete.modal("hide");
            });

            $('.event').on('click', function (e) {
                return;
                var id = $(this).data('id');
                var _token = $('meta[name="csrf-token"]').attr('content');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Change status!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/event-status/'+ id,
                            type: 'GET',
                            dataType: 'json',
                            data: {_token: _token},
                            success: function (data) {
                                location.reload();
                            },
                            error: function (data) {
                                location.reload();
                            }
                        });
                    } else {
                        location.reload();
                    }

                });
            });
        });
    </script>
@endsection

