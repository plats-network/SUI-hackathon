<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\{TaskLocationJobResource};
use App\Models\User\UserTaskHistory;

class TaskLocationResource extends JsonResource
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

        $jobIds = $this->taskLocationJobs->pluck('id')->toArray();
        $statusJob = UserTaskHistory::whereUserId($userId)
            ->whereTaskId($this->task_id)
            ->whereIn('source_id', $jobIds)
            ->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'job_num' => $this->job_num,
            'job_type' => 'checkin',
            'job_status' => $statusJob > 0 ? true : false,
            'jobs' => $this->taskLocationJobs->count() > 0 ?
                TaskLocationJobResource::collection($this->taskLocationJobs) :
                null
        ];
      }
}
