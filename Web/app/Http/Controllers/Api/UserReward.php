<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\User\{
    UserReward as Reward
};
use App\Http\Resources\{UserRewardResource};

class UserReward extends ApiController
{
    /**
     * @param $userReward
     */
    public function __construct(
        private Reward $userReward
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $userRewards = $this->userReward->whereUserId($userId)->get();

            if ($userRewards) {
                $datas = UserRewardResource::collection($userRewards);
            } else {
                return $this->respondNotFound();
            }
        } catch (\Exception $e) {
            return $this->respondNotFound();
        }

        
        return $this->respondWithIndex($datas);
    }
}
