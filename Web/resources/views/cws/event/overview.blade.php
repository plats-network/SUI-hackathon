@extends('cws.layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/styles/metro/notify-metro.min.css" />
@endsection

@section('name_page')
    <div class="page-title-box align-self-center d-none d-md-block">
        <h4 class="page-title mb-0">Overview</h4>
    </div>
@endsection

@section('content')
    @php
        $datas = [
            ['link' => route('cws.quiz.questions', ['eventId' => $event->id]), 'type' => 'Quiz', 'flag' => true]
        ];
    @endphp

    <div class="row">
        <div class="col-xl-12 mb-3">
            <a href="{{route('cws.eventPreview', [
                'id' => $event->id,
                'tab' => 0,
                'preview' => 1])}}"
                class="btn btn-danger btn-sm">Back</a>
        </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Link Games</h5>
                    </div>
                    <table class="table table-bordered mb-0 table-responsive">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Type</th>
                                <th>Link</th>
                                <th>Copy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $k => $data)
                                @if ($data['flag'] == true)
                                    <tr>
                                        <td width="5%">{{$k+1}}</td>
                                        <td width="5%">{{$data['type']}}</td>
                                        <td>
                                            <a href="{{$data['link']}}" target="_blank">
                                                Click Here!
                                            </a>
                                       </td>
                                       <td>
                                            <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                data-clipboard-text="{{$data['link']}}">Copy Link</button>
                                       </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center mb-2">
                        <h5 class="card-title">Link Share</h5>
                    </div>
                    <table class="table table-bordered mb-0 table-responsive">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Link</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="5%">#</td>
                                <td width="35%">
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        id="frmName"
                                        placeholder="Name"
                                        required>
                                    <input type="hidden" name="id" id="frmId">
                                    <input type="hidden" name="task_id" id="frmTaskId" value="{{$event->id}}">
                                </td>
                                <td width="40%">
                                    <p id="frmUrl"></p>
                                </td>
                                <td width="5%">__</td>
                                <td width="15%">
                                   <button type="button" id="genLink" class="btn btn-primary btn-sm">Save</button>
                                   <button type="button" id="frmReset" class="btn btn-secondary btn-sm">Reset</button>
                                </td>
                            </tr>
                            @foreach($shares as $k => $share)
                                <tr>
                                    <td width="5%">{{$k+1}}</td>
                                    <td width="5%">{{$share->name}}</td>
                                    <td>
                                        <a href="{{$share->url}}" target="_blank">Link share</a>
                                    </td>
                                    <td>{{rand(100,1000)}}</td>
                                    <td>
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm editLink"
                                            data-name="{{$share->name}}"
                                            data-link="{{$share->url}}"
                                            data-id="{{$share->id}}">Edit</button>
                                        <button
                                            type="button"
                                            class="btn btn-secondary btn-sm"
                                            data-clipboard-text="{{$share->url}}">Copy Link</button>
                                    </td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--Script--}}
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            var clipboard = new ClipboardJS('.btn');
            clipboard.on('success', function(e) {
                $.notify("Copy link success.", "success");
                e.clearSelection();
            });

            $('.editLink').on('click', function(e) {
                var name = $(this).data('name'),
                    id = $(this).data('id'),
                    link = $(this).data('link');
                $('#frmUrl').html(link);
                $('#frmName').val(name);
                $('#frmId').val(id);
            });

            $('#frmReset').on('click', function(e) {
                $('#frmUrl').html('');
                $('#frmName').val('');
                $('#frmId').val('');
            });

            $('#genLink').on('click', function(e) {
                var name = $('#frmName').val(),
                    task_id = $('#frmTaskId').val(),
                    id = $('#frmId').val();
                if (name == "") {
                    $('#frmName').focus();
                } else {
                    $(this).attr('disabled', true);
                    $.ajax({
                        url: '/gen-link-social',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            _token: _token,
                            id: id,
                            task_id: task_id,
                            name: name
                        },
                        success: function (data) {
                            $(this).attr('disabled', false);
                            setTimeout(function(e) {
                                location.reload();
                            }, 500);
                        },
                        error: function (data) {
                            $(this).attr('disabled', false);
                            setTimeout(function(e) {
                                location.reload();
                            }, 500);
                            // location.reload();
                        }
                    });
                }

            });
        });
    </script>
@endsection
