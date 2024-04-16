@extends('web.auth.auth_app')
@section('content')
    <div class="card sign-up">
        <h3 class="title mb-3">Sign Up</h3>
        <p class="subtitle">Already have an account? <a href="{{route('web.formLogin')}}">Sign In</a></p>

        {{-- @include('web.auth._social') --}}
        <x-form::open action="{{ route('web.signUp') }}" method="POST">
            @include('layouts.flash')

            <div class="email-login">
                <label for="name"> <b>Fullname</b> <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="name"
                    placeholder="Nguyen Van A"
                    value="{{old('name') ?? null}}"
                    id="name"
                    required />
                <label for="email"> <b>Email</b> <span class="text-danger">*</span></label>
                <input
                    type="email"
                    name="email"
                    placeholder="user@gmail.com"
                    value="{{old('email') ?? null}}"
                    id="email"
                    required />
                <div class="row">
                    <div class="col-md-6">
                        <label for="psw"><b>Password</b> <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="password"
                            placeholder="********"
                            id="psw"
                            class="w-100"
                            required />
                    </div>
                    <div class="col-md-6">
                        <label for="re-psw"><b>Password Confirmation</b> <span class="text-danger">*</span></label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="********"
                            id="re-psw"
                            class="w-100"
                            required />
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-1">
                        <input name="term" type="checkbox" id="term" />
                    </div>
                    <div class="col-md-11">
                        <label for="term">I agree to the <a href="#">Terms & Conditions.</a></label>
                    </div>
                </div>
            </div>
            <button class="cta-btn" type="submit">Sign Up</button>
        </x-form>
    </div>
@endsection
