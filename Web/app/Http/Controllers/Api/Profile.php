<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\{
    ChangePasswordRequest,
    SocialRequest,
    UpdateProfileRequest
};
use App\Http\Requests\Api\User\{
    AvatarRequest,
    ProfileRequest
};

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;

class Profile extends ApiController
{
    /**
     * @param $userService
     * @param $user
     */
    public function __construct(
        private UserService $userService,
        private User $user
    ) {}

    /**
     * @param \App\Http\Requests\UpdateProfileRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return $this->respondNotFound();
            }
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        return new UserResource($user);
    }

    /**
     * @param \App\Http\Requests\UpdateProfileRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function update(ProfileRequest $request)
    {
        try {
            $user = $request->user();
            $request['gender'] = $request->input('gender') == 'male' ? 0 : 1;
            $user->update($request->all());
        } catch (\Exception $e) {
            return $this->respondError('Errors');
        }

        return new UserResource($user);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(AvatarRequest $request)
    {
        $data = $this->userService->updateAvatar($request);

        return $this->respondWithData($data, 'Update successful');
    }

    /**
     * @param \App\Http\Requests\ChangePasswordRequest $request
     *
     * @return \App\Http\Resources\UserResource
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return new UserResource(
            $this->userService->changePassword($request->user()->id, $request->input('password'))
        );
    }

    /**
     * @param $request
     *
     * @return \App\Http\Resources\UserResource
     */
    public function updateSocialAccount(SocialRequest $request)
    {
        try {
            $userId = $request->user()->id;
            $infoUser = $this->user->find($userId);
            $socialTweeter = $this->user->where('twitter', $request->account)->first();
            $socialFacebook = $this->user->where('facebook', $request->account)->first();
            $socialDiscord = $this->user->where('discord', $request->account)->first();
            $socialTelegram = $this->user->where('telegram', $request->account)->first();

            if ($socialTweeter) { return $this->respondError('Twitter ID is exits!', 400); }
            if ($socialFacebook) { return $this->respondError('Facebook ID is exits!', 400); }
            if ($socialDiscord) { return $this->respondError('Discord ID is exits!', 400); }
            if ($socialTelegram) { return $this->respondError('Telegram ID is exits!', 400); }

            $user = $this->userService->updateSocialAccount($userId, $request);
        } catch (\Exception $e) {
            return $this->respondError('Errors: ' . $e->getMessage());
        }


        return new UserResource($user);
    }
}
