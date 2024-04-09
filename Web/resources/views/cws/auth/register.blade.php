@extends('cws.auth.auth')

@section('content')
    <div class="card">
        <div class="card-body p-4"> 
            <div class="text-center mt-2">
                <h5>Register Account</h5>
                <p class="text-muted">Get your free plats account now.</p>
            </div>
            <div class="p-2 mt-4">
                <form action="{{ route('cws.register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="fullname">Fullname <span class="text-danger">*</span></label>
                        <div class="position-relative input-custom-icon">
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                id="fullname"
                                placeholder="Nguyen Van A"
                                required />  
                            <span class="bx bx-user"></span>
                        </div>     
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <div class="position-relative input-custom-icon">
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                id="email"
                                placeholder="test@test.com"
                                required />  
                            <span class="bx bx-mail-send"></span>
                        </div>     
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                            <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                <span class="bx bx-lock-alt"></span>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    id="password"
                                    placeholder="*********"
                                    required />
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="confirmation">Password confirm <span class="text-danger">*</span></label>
                            <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                <span class="bx bx-lock-alt"></span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    id="confirmation"
                                    placeholder="*********"
                                    required />
                            </div>
                        </div>
                    </div>

                    <div class="form-check py-1">
                        <input
                            type="checkbox"
                            name="term"
                            class="form-check-input"
                            id="term"
                            required />
                        <label class="form-check-label" for="term">
                            I accept <a href="#" class="text-dark">Terms and Conditions</a> <span class="text-danger">*</span>
                        </label>
                    </div>
                    
                    <div class="mt-3">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Register</button>
                    </div>

                    <div class="mt-4 text-center">
                        <p class="mb-0">Already have an account ? <a href="{{route('cws.formLogin')}}" class="fw-medium text-primary"> Login</a></p>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
