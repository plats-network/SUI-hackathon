@extends('cws.auth.auth')


@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="text-center mt-2">
                <h5>Sign in</h5>
                <p class="text-muted">Sign in to continue to plats.</p>
            </div>
            <div class="p-2 mt-4">
                <x-form::open action="{{ route('cws.login') }}" method="POST">
                    @include('layouts.flash')
                    <div class="mb-3">
                        <label class="form-label" for="username">Email <span class="text-danger">*</span></label>
                        <div class="position-relative input-custom-icon">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="user@event.com"
                                required >
                            <span class="bx bx-user"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="float-end">
                            <a href="{{route('cws.formForgot')}}" class="text-muted text-decoration-underline">Forgot password?</a>
                        </div>
                        <label class="form-label" for="password-input">Password</label>
                        <div class="position-relative auth-pass-inputgroup input-custom-icon">
                            <span class="bx bx-lock-alt"></span>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                id="password-input"
                                placeholder="********">
                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-check py-1">
                        <input type="checkbox" name="remember" class="form-check-input" id="auth-remember-check">
                        <label class="form-check-label" for="auth-remember-check">Remember me</label>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Sign In</button>
                    </div>
                </x-form>
                <div class="mt-4 text-center">
                    <p class="mb-0">Don't have an account ?
                        <a href="{{route('cws.fromSignUp')}}" class="fw-medium text-primary">Signup now</a>
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
