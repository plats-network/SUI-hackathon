@extends('web.auth.auth_app')
@section('content')
    <div class="card">
        <h3 class="title mb-3">Forgot Password</h3>
        <p class="subtitle">Already have an account? <a href="{{route('web.formLogin')}}">Sign In</a></p>

        <x-form::open action="{{ route('forget.password.post') }}">
          <div class="email-login">
             <label for="email"> <b>Email</b> <span class="text-danger">*</span></label>
             <input
                type="text"
                name="email"
                placeholder="user@gmail.com"
                id="email"
                required />
          </div>
          <button class="cta-btn" type="submit">Send</button>
        </x-form>
    </div>
@endsection
