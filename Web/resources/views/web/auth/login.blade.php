@extends('web.auth.auth_app')

@viteReactRefresh
@vite(['resources/js/connect-wallet.jsx'])
{{--@vite(['resources/js/login.js'])--}}


@section('content')
{{--    <div class="card">--}}
{{--        <h3 class="title mb-3"> Log in</h3>--}}
{{--        <p class="subtitle">Don't have an account? <a href="{{route('web.formSignup')}}"> Sign Up</a></p>--}}

{{--        --}}{{-- @include('web.auth._social') --}}

{{--        <x-form::open action="{{ route('web.login') }}">--}}
{{--            <div class="email-login">--}}
{{--                <label for="email"> <b>Email</b> <span class="text-danger">*</span></label>--}}
{{--                <input--}}
{{--                    type="text"--}}
{{--                    name="email"--}}
{{--                    placeholder="user@gmail.com"--}}
{{--                    value="{{old('email') ?? null}}"--}}
{{--                    id="email"--}}
{{--                    required/>--}}
{{--                <label for="psw"><b>Password</b> <span class="text-danger">*</span></label>--}}
{{--                <input--}}
{{--                    type="password"--}}
{{--                    name="password"--}}
{{--                    placeholder="********"--}}
{{--                    id="psw"--}}
{{--                    required/>--}}
{{--            </div>--}}


{{--            <button class="cta-btn" type="submit">Sign In</button>--}}
{{--            <a class="forget-pass" href="{{route('web.formForgot')}}">Forgot password?</a>--}}

{{--        </x-form>--}}


{{--            @if($is_show_connect_wallet)--}}
{{--                <div class="text-center">--}}
{{--                    <span id="login_button"></span>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--    </div>--}}

<script
    src="https://auth.magic.link/pnp/login"
    data-magic-publishable-api-key="pk_live_7AC27AA25AE25994"
    data-terms-of-service-uri="/path/to/your/terms-of-service"
    data-privacy-policy-uri="/path/to/your/privacy-policy"
    data-redirect-uri="/callback"> <!-- Replace with the location of your callback.html -->
</script>
@endsection
