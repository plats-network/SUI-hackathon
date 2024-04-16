<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use App\Helpers\{ActionHelper};
use Illuminate\Support\Facades\Http;
use App\Http\Resources\{TaskGuideResource};
use App\Models\{TaskUser};
use App\Models\User\UserTaskHistory;
use Illuminate\Support\Str;

class TaskSocialResource extends JsonResource
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
        $types = ActionHelper::commonType($this->platform, $this->type);
        $statusJob = UserTaskHistory::whereUserId($userId)
            ->whereTaskId($this->task_id)
            ->whereSourceId($this->id)
            ->count();

        return [
            'id' => $this->id,
            'reward_id' => $this->reward_id,
            'name' => $this->name,
            'description' => $this->description,
            'platform' => $this->platform,
            'platform_label' => $types['platform'],
            'type' => $this->type,
            'type_label' => $types['type'],
            'job_type' => 'social',
            'job_status' => $statusJob > 0 ? true : false,
            'url' => $this->url,
            'amount' => $this->amount,
            'lock' => $this->lock,
        ];
    }
}
