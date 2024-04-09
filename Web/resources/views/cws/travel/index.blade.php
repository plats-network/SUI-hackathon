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
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Lists Travel Games</h5>
                    </div>
                    <div class="table-responsive">
                        <div class="card">
                            <div class="card-body">
                                <input type="hidden" id="id" value="">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <label class="form-label">Name <span class="text-danger">(*)</span></label>
                                        <input type="text" class="form-control" name="name" id="name" maxlength="100">
                                        <span class="text-danger d-none" id="name-error"></span>
                                    </div>
                                    <div class="col-xl-2">
                                        <label class="form-label">Date Prize <span class="text-danger">(*)</label>
                                        <input type="text" class="form-control flatpickr-input" name="" id="prize_at">
                                        <span class="text-danger d-none" id="prize_at-error"></span>
                                    </div>
                                    <div class="col-xl-4">
                                        <label class="form-label">Note Prize <span class="text-danger">(*)</label>
                                        <textarea class="form-control" name="note" id="note" maxlength="1000"></textarea>
                                        <span class="text-danger d-none" id="note-error"></span>
                                    </div>
                                    <div class="col-xl-2" style="margin-top: 40px;">
                                        <button
                                            id="create"
                                            class="btn btn-primary"
                                            data-url="{{route('cws.travel.create')}}">Save</button>
                                        <button id="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>
                                        Author
                                        <br>
                                        Email
                                    </th>
                                    <th>Name</th>
                                    <th>Time Prize</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($travels as $k => $item)
                                    <tr style="background-color: {{ $item->status ? '#fff' : '#f1f1f1' }};">
                                        <td>{{$k+1}}</td>
                                        <td>
                                            {{optional($item->user)->name}}
                                            <br>
                                            <p class="text-success" style="font-size: 11px;margin-bottom: 0;">
                                                {{optional($item->user)->email}}
                                            </p>
                                        </td>
                                        <td>{{$item->name}}</td>
                                        <td>{{dateFormat($item->prize_at)}}</td>
                                        <td>{{$item->note}}</td>
                                        <td>
                                            <input
                                                type="checkbox"
                                                id="t_{{$item->id}}"
                                                switch="none"
                                                @if($item->status) checked @endif
                                            >
                                            <label
                                                class="mini"
                                                data-id="{{$item->id}}"
                                                data-url="{{route('cws.travel.updStatus', ['id' => $item->id])}}"
                                                for="t_{{$item->id}}"
                                                data-on-label="On"
                                                data-off-label="Off"></label>
                                        </td>
                                        <td>{{dateFormat($item->created_at)}}</td>
                                        <td>
                                            @if ($item->status)
                                                <button
                                                    class="btn btn-danger btn-sm edit"
                                                    data-id="{{$item->id}}"
                                                    data-name="{{$item->name}}"
                                                    data-note="{{$item->note}}"
                                                    data-prize-at="{{dateFormat($item->prize_at)}}"
                                                >Edit</button>
                                            @else
                                                <button class="btn btn-danger btn-sm" disabled>Edit</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            $(document).on('click', '.edit', function (event) {
                var id = $(this).data('id'),
                    name = $(this).data('name'),
                    note = $(this).data('note'),
                    prize_at = $(this).data('prize-at');

                $('#name').val(name);
                $('#note').val(note);
                $('#prize_at').val(prize_at);
                $('#id').val(id);
            });

            $(document).on('click', '#reset', function (event) {
                $('#name').val('');
                $('#note').val('');
                $('#prize_at').val('');
                $('#id').val('');
            });

            $(document).on('click', '#create', function (event) {
                $(this).attr('disabled', true);
                var _token = $('meta[name="csrf-token"]').attr('content');
                var name = $('#name').val(),
                    note = $('#note').val(),
                    prize_at = $('#prize_at').val(),
                    url = $(this).data('url'),
                    id = $('#id').val();

                if (name == '' || note == '' || prize_at == '') {
                    $(this).removeAttr('disabled');
                    if (name == '') {
                        $('#name-error').removeClass('d-none');
                        $('#name-error').html('Please input name');
                    } else {
                        $('#name-error').addClass('d-none');
                    }

                    if (note == '') {
                        $('#note-error').removeClass('d-none');
                        $('#note-error').html('Please input note prize');
                    } else {
                        $('#note-error').addClass('d-none');
                    }

                    if (prize_at == '') {
                        $('#prize_at-error').removeClass('d-none');
                        $('#prize_at-error').html('Please input prize at');
                    } else {
                        $('#prize_at-error').addClass('d-none');
                    }
                } else {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: _token,
                            id: id,
                            name: name,
                            note: note,
                            prize_at: prize_at
                        },
                        success: function (data) {
                            $.notify('Success', 'success');
                            setTimeout(function(e) {
                                location.reload();
                            }, 2000);
                        },
                        error: function (data) {
                            $.notify('Error', 'error');
                            setTimeout(function(e) {
                                location.reload();
                            }, 2000);
                        }
                    });
                }

            });

            $(document).on('click', '.mini', function (event) {
                var url = $(this).data('url');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    data: {_token: _token},
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
                });
            });
        });
    </script>
@endsection
