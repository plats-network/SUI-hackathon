<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\{TaskEventResource};
use Illuminate\Http\Resources\MissingValue;
use App\Helpers\{DateHelper, ActionHelper, BaseImage};
use Illuminate\Support\Facades\Http;
use App\Models\{
    TaskUser, Reward, UserTaskAction,
    TaskGroup, Group, User
};
use App\Models\Event\EventUserTicket;
use Carbon\Carbon;


class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'banner_url' => $this->banner_url,
            'address' => $this->address,
            'date' => DateHelper::getDateTime($this->created_at),
        ];
    }
}
