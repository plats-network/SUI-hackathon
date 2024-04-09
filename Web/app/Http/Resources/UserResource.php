<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    
    /**
     * Response with token
     *
     * @var bool | string
     */
    protected $token = false;

    /**
     * Token expires timestamp when use $token
     *
     * @var bool | int
     */
    protected $expires = false;

    /**
     * Token type
     *
     * @var string
     */
    protected $tokenType = 'bearer';

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $result = parent::toArray($request);

        if ($this->token != false) {
            $result['jwt'] = [
                'access_token' => $this->token,
                'token_type'   => $this->tokenType,
                'expires_in'   => $this->expires,
            ];
        }

        return $result;
    }

    /**
     * Response with token for API
     *
     * @param $token
     * @param $expires
     *
     * @return $this
     */
    public function withToken($token, $expires)
    {
        $this->token   = $token;
        $this->expires = $expires;

        return $this;
    }
}
