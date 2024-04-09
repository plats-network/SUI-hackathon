@extends('cws.auth.auth')

@section('content')
    <div class="card">
        <div class="card-body p-4"> 
            <div class="text-center mt-2">
                <h5>Reset Password</h5>
                <p class="text-muted">Reset Password with plats.</p>
            </div>
            <div class="p-2 mt-4">
                <div class="alert alert-success text-center small mb-4" role="alert">
                    Enter your Email and instructions will be sent to you!
                </div>
                <form action="{{route('cws.forgot')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="useremail">Email</label>
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
                    <div class="mt-4">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Reset</button>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="mb-0">Remember It ? <a href="{{route('cws.formLogin')}}" class="fw-medium text-primary">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
