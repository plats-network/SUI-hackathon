@extends('web.auth.auth_app')

@viteReactRefresh
@vite(['resources/js/connect-wallet.jsx'])
@vite(['resources/js/ModalWallet.jsx'])


@section('content')
    <div class="card">
        @if($sessionCheckin)
                <h3 class="title mb-3">Thông tin checkin</h3>
        @else
        <h3 class="title mb-3">Thông tin của bạn</h3>
        @endif
        {{-- @include('web.auth._social') --}}
        {{-- <p class="note-login">
           Bạn vui lòng nhập đúng số điện thoại hoặc email để có cơ hội nhận những phần quà hấp dẫn từ chương trình.
           Phần thưởng trong qua trình tham gia mini game chúng tôi sẽ liên hệ thông qua email or số điện thoại này.
           Xin chân thành cảm ơn!
        </p> --}}

        <x-form::open action="{{ route('web.loginGuest') }}">
          <div class="email-login">
             <label for="name">Name(<span class="text-danger">*</span>)</label>
             <input
                type="text"
                name="name"
                placeholder="Nguyen Van A"
                value="{{old('name') ?? null}}"
                id="name"
                required />
             <label for="account">Email or Phone(<span class="text-danger">*</span>)</label>
             <input
                type="text"
                name="account"
                placeholder="user@gmail.com or 0987223123"
                id="account"
                required />
          </div>
          <button class="cta-btn" type="submit">Submit</button>
        </x-form>
    </div>
@endsection
