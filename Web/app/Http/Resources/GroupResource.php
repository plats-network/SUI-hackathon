<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserGroup;
use App\Helpers\{BaseImage, DateHelper};
use Log;


class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userId = $request->user()->id;
        $userGroup = $this->userGroup($userId, $this->id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'username' => $this->username,
            'country' => $this->country,
            'desc_vn' => $this->desc_vn,
            'desc_en' => $this->desc_en,
            'headline' => $this->headline,
            'avatar_url' => BaseImage::imgGroup($this->avatar_url),
            'cover_url' => BaseImage::imgGroup($this->cover_url),
            'site_url' =>  $this->site_url,
            'twitter_url' => $this->twitter_url,
            'telegram_url' => $this->telegram_url,
            'facebook_url' => $this->facebook_url,
            'youtube_url' => $this->youtube_url,
            'discord_url' => $this->discord_url,
            'instagram_url' => $this->instagram_url,
            'is_join' => $userGroup > 0 ? true : false,
        ];
    }

    private function userGroup($userId, $groupId)
    {
        try {
            $userGroup = UserGroup::whereUserId($userId)
                ->whereGroupId($groupId)
                ->count();
        } catch (\Exception $e) {
            Log::err('Group log:' . $e->getMessage());
            $userGroup = 0;
        }

        return $userGroup;
    }
}
