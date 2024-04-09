<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Traits\AuthenticatesUsers;
use App\Services\{UserService, ProviderService};
use GeneaLabs\LaravelSocialiter\Facades\Socialiter;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class Login extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var \App\Services\UserService
     */
    protected $userService;

    /**
     * @var \App\Services\ProviderService
     */
    protected $providerService;

    /**
     * @param \App\Services\UserService $userService
     * @param \App\Services\ProviderService $providerService
     */
    public function __construct(UserService $userService, ProviderService $providerService)
    {
        $this->userService = $userService;
        $this->providerService = $providerService;
    }

    /**
     * @param \App\Http\Requests\Auth\LoginRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function login(LoginRequest $request)
    {
        $request = $this->credentials($request);
        $user = $this->guard()->attempt($request);
        $data = $this->respondWithToken($user);

        if(!$data->resource || !$data->email_verified_at) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'These credentials do not match our records.'
            ], 400);
        }
        if(!$data->email_verified_at) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Your account has not been verified, please check your email.'
            ], EMAIL_UNVERIFIED);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Logged in successfully.'
        ], 200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function refresh(Request $request)
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param $token
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    protected function respondWithToken($token, $user = null)
    {
        $refreshTtl = $this->guard()->factory()->getTTL() * config('jwt.refresh_ttl');
        return (new UserResource($this->guard()->user() ?? $user))
            ->withToken($token, $refreshTtl);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Get user through provider via access token
     *
     * @param $provider
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function getUserProvider($provider, Request $request)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }
    
    /**
     * Social Login
     * 
     * @param $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function socialLogin(Request $request)
    {
        $providerName = $request->post('provider_name');
        $socialToken = $request->post('access_token');
        // Check valid social account and get provider
        try {
            $providerUser = Socialite::driver($providerName)->userFromToken($socialToken);
        } catch (\Exception $e) {
            $errorCode = $providerName == "facebook" ? LOGIN_FACEBOOK_ERROR : ($providerName == "google" ? LOGIN_GOOGLE_ERROR : LOGIN_SOCIAL_ERROR);
            return $this->respondError('Invalid credentials provided.' . $e->getMessage(), 422, $errorCode);
        }

        // Check exist user by email and get data authenticated
        $data = $this->checkUserByEmail($providerUser, $providerName);

        // Then respond back with the token
        return $this->respondWithResource($data, 'Logged in successfully.');
    }
    
    /**
     * Apple Login
     * 
     * @return $redirect
     */
    public function loginApple()
    {
        try {
            return Socialite::driver("sign-in-with-apple")->scopes(["name", "email"])->redirect();
        } catch (\Exception $e) {
            return $this->respondError('There are some error with apple login: ' . $e->getMessage(), 422, LOGIN_APPLE_ERROR);
        }
    }
    /**
     * Social call back
     * 
     * @param $providerName
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function callbackProvider($providerName)
    {
        $providerName = $providerName === "apple" ? 'sign-in-with-apple' : $providerName;
        // get abstract user object, not persisted
        $providerUser = Socialite::driver($providerName)->user();
        dd($providerUser);
        // Check exist user by email and get data authenticated
        $data = $this->checkUserByEmail($providerUser, $providerName);

        // Then respond back with the token
        return $this->respondWithResource($data, 'Logged in successfully.');
    }
    /**
     * Check exist user by email and get data authenticated
     * 
     * @param $profile
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function checkUserByEmail($providerUser, $providerName) {
        DB::beginTransaction();
        try {
            $dataUser = [
                'name' => $providerUser->name,
                'email' => $providerUser->email
            ];
            $user = $this->userService->firstOrCreate($dataUser);
            $dataProvider = [
                'provider' => $providerName,
                'provider_id' => $providerUser->id,
                'user_id' => $user->id,
                'avatar' => $providerUser->avatar
            ];
    
            $user->providers()->updateOrCreate(
                ['provider' => $providerName, 'provider_id' => $providerUser->id],
                $dataProvider
            );
            // create a token for the user, so they can login
            $tokenJWT = JWTAuth::fromUser($user);
            DB::commit();
        } catch (RuntimeException $exception) {
            DB::rollBack();
            throw $exception;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new RuntimeException($exception->getMessage(), 500062, $exception->getMessage(), $exception);
        }

        return $this->respondWithToken($tokenJWT, $user);
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return $this->respondError('Invalid credentials provided.' . $exception->getMessage(), 422);
        }

        $userCreated = $this->userService->firstOrCreate($user);
        $userCreated->providers()->updateOrCreate(
            ['provider' => $provider, 'provider_id' => '$user->getId()'],
            ['avatar' => '$user->getAvatar()']
        );
        // $token = $userCreated->createToken('token-name')->plainTextToken;
        $tokenJWT = JWTAuth::fromUser($userCreated);
        $data =  $this->respondWithToken($tokenJWT, $userCreated);

        // then respond back with the token
        return $this->respondWithResource($data, 'Logged in successfully.');
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    public function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'github', 'google'])) {
            return $this->respondError('Please login using facebook, github or google.', 422);
        }
    }

    /**
     * Check accout was registed with social accout
     * 
     * @param $email
     * @return String
     */
    public function checkUserRegistedWithSocial($email)
    {
        $user = $this->userService->findByEmail($email);
        return !is_null($user->providers) ? $user->providers->provider : null;
    }

    /**
     * Validate email unique
     *
     * @param  $request
     */
    public function validateEmailUnique($request)
    {
        return $request->validate(['email' => ['required', 'max:' . INPUT_MAX_LENGTH, 'email', 'exists:users,email']]);
    }
}
