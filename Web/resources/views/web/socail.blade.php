@extends('web.layout')
@section('content')
    <div >
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Nhà cung cấp</th>
                <th>Tài khoản</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Discord</td>
                @if($discord == null)
                    <td>Không có tài khoản nào được kết nối</td>
                    <td>
                        <a class="underline" href="{{env('DISCORD_URL')}}/oauth2/authorize?client_id={{env("DISCORD_CLIENT_ID")}}&redirect_uri={{env("DISCORD_REDIRECT_URI")}}&response_type=code&scope=identify%20email">Kết nối</a>
                    </td>
                @else
                    <td>{{$discord}}</td>
                    <td>Đã kết nối</td>
                @endif
            </tr>
            <tr>
                <td>Telegram</td>
                @if($telegram == null)
                    <td>Không có tài khoản nào được kết nối</td>
                    <td>
                        <script async src="{{env('TELEGRAM_URL')}}/js/telegram-widget.js" data-telegram-login="{{env("BOOT_TELEGRAM")}}" data-size="small" data-auth-url="{{env("APP_URL")}}/telegram" data-request-access="write"></script>
                    </td>
                @else
                    <td>{{$telegram}}</td>
                    <td>Đã kết nối</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>

@endsection
