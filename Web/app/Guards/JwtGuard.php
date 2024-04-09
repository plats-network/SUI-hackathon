<?php

namespace App\Guards;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\JWT;

class JwtGuard extends SessionGuard
{
    /**
     * The name of the Guard.
     *
     * @var string
     */
    protected $name = 'tymon.jwt';

    /**
     * Instantiate the class.
     *
     * @param \PHPOpenSourceSaver\JWTAuth\JWT $jwt
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Events\Dispatcher $eventDispatcher
     */
    public function __construct(
        JWT $jwt,
        UserProvider $provider,
        Session $session,
        Request $request,
        Dispatcher $eventDispatcher
    ) {
        $this->jwt      = $jwt;
        $this->provider = $provider;
        $this->request  = $request;
        $this->events   = $eventDispatcher;
        $this->session  = $session;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return !is_null($this->user());
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return null;
        }

        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        // The case of using tokens for login is still accepted
        if ($this->jwt->setRequest($this->request)->getToken()) {
            return $this->checkAndGetUserFromToken();
        }

        $sessionToken = $this->session->get($this->getName());
        if (is_null($sessionToken)) {
            return null;
        }

        // Set token
        $this->jwt->setToken($sessionToken)->getToken();

        return $this->checkAndGetUserFromToken();
    }

    /**
     * Determine if the current user is authenticated. If not, throw an exception.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function authenticate()
    {
        if (!is_null($user = $this->user())) {
            return $user;
        }

        throw new AuthenticationException;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id()
    {
        if ($this->user()) {
            return $this->user()->id;
        }

        return null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        dd(__FUNCTION__);

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param mixed $user
     * @param array $credentials
     *
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Ensure the JWTSubject matches what is in the token.
     *
     * @return bool
     */
    protected function validateSubject()
    {
        // If the provider doesn't have the necessary method
        // to get the underlying model name then allow.
        if (!method_exists($this->provider, 'getModel')) {
            return true;
        }

        return $this->jwt->checkSubjectModel($this->provider->getModel());
    }

    /**
     * @return Authenticatable|null
     */
    protected function checkAndGetUserFromToken()
    {
        $payload = $this->jwt->check(true);

        if ($payload && $this->validateSubject()) {
            return new User($payload->toArray(), $this->jwt->getToken());
        }

        return null;
    }
}
