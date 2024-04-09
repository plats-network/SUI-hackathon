<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\{BaseImage, DateHelper};
use App\Models\UserGroup;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'username' => $this->username,
            'country' => $this->country,
            'desc_vn' => $this->desc_vn,
            'desc_en' => $this->desc_en,
            'avatar_url' => BaseImage::imgGroup($this->avatar_url),
            'cover_url' => BaseImage::imgGroup($this->cover_url),
            'headline' => $this->headline,
            'site_url' =>  $this->site_url,
            'twitter_url' => $this->twitter_url,
            'telegram_url' => $this->telegram_url,
            'facebook_url' => $this->facebook_url,
            'youtube_url' => $this->youtube_url,
            'discord_url' => $this->discord_url,
            'instagram_url' => $this->instagram_url,
            'status' => $this->status == 1 ? true : false,
            'status_label' => $this->status == 1 ? 'Active' : 'Disable',
            'total_user' => $this->totalUserJoinGroup($this->id),
        ];
    }

    private function totalUserJoinGroup($groupId)
    {
        try {
            $total = UserGroup::whereGroupId($groupId)->count();
        } catch (\Exception $e) {
            $total = 0;
        }

        return $total;
    }
}
