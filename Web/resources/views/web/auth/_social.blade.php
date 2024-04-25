<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
@vite('resources/js/zklogin.js')
<style>
  .bg-login{
    background-size: cover;
    background-repeat: no-repeat;
    background-image: url(/build/assets/login-img-e3b48670.png);
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
<head>
    <meta name='redirect_uri' content="{{ env('REDIRECT_URI')}}">
    <meta name='type_network' content="{{ env('TYPE_NETWORK')}}">
</head>
<!--  <div class="social-sign row">
 <a href="{{ url('/login/facebook') }}" class="loginBtn loginBtn--facebook">
      Login with Facebook
    </a>

    <a href="{{url('/login/google')}}" class="loginBtn loginBtn--google">
      Login with Google
    </a>
    <div class="col-12 text-center">
      <h1>Login With Social</h1>
    </div>
    <div class="col-12 text-center">
      <a class="btn btn-info Google loginBtn loginBtn--google" href="#">With Google</a>
    </div>
</div>
-->
<div class="d-flex flex-column min-vh-100 px-3 pt-4 bg-login">
  <div class="row justify-content-center my-auto">
      <div class="col-12">
          <div class="card">
              <div class="card-body p-4">
                  <div class="text-center mt-2">
                      <h5>Sign in</h5>
                      <p class="text-muted">Sign in to continue to plats.</p>
                  </div>
                  <div class="p-2 mt-4">
                          <div class="mt-3">
                              <button class="Google btn btn-primary w-100 waves-effect waves-light" type="submit">Sign In with google</button>
                          </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="row">
      <div class="col-lg-12">
          <div class="text-center p-4">
              <p>
                  ©
                  <script>
                      document.write(new Date().getFullYear());
                  </script>
                  2024 Plats.
              </p>
          </div>
      </div>
  </div>
</div>

