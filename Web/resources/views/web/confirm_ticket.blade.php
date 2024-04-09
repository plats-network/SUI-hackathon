@extends('admin.layout')
@section('content')

    @if(empty($active))
        <div id="app">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="alert alert-danger text-center" role="alert"> Tài khoản không có quyền quét mã QR</div>
                </div>
            </div>
        </div>
    @else
        <div id="app">
            <div class="card mb-5">
                <div class="card-body">
                    <div class="alert alert-success text-center" role="alert"> Xác nhận tham gia thành công !</div>
                </div>
            </div>
        </div>
    @endif
@endsection
