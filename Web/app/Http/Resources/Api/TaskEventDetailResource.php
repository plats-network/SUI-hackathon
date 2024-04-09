<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Event\UserJoinEvent;

class TaskEventDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userId = optional($request->user())->id;
        $checkDoneJob = UserJoinEvent::whereUserId($userId)
            ->whereTaskEventDetailId($this->id)
            ->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'code' => $this->code,
            'status_done' => $checkDoneJob > 0 ? true : false,
        ];
    }
}
