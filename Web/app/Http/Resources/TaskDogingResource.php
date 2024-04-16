<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\{Task, TaskLocation};
use App\Helpers\DateHelper;
use App\Http\Resources\TaskGuideResource;

class TaskDogingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $task = Task::find($this->task_id);
        $taskLocation = TaskLocation::whereId($this->location_id)->first();

        return [
            'id' => $this->id,
            'time_left' => $this->time_left,
            'duration' => $task->duration,
            'duration_units' => 'minute',
            'time_start' => Carbon::parse($this->time_start)->timestamp,
            'time_end' => Carbon::parse($this->time_end)->timestamp,
            'time_start_orginal' => !is_null($this->time_start) ? DateHelper::parseDate($this->time_start)->format('Y-m-d H:i:s') : null,
            'time_end_orginal' => !is_null($this->time_end) ? DateHelper::parseDate($this->time_end)->format('Y-m-d H:i:s') : null,
            'wallet_address' => $this->wallet_address,
            'time_expried'  => ($this->time_end && Carbon::now() < $this->time_end) ? false : true,
            'near' => [
                'radius' => $task->valid_radius ?? 100,
                'units' => 'm',
            ],
            'task' => [
                'id' => optional($task)->id,
                'name' => optional($task)->name,
                'cover_url' => optional($task)->cover_url,
            ],
            'guide' => new TaskGuideResource($task->task_guides),
            'task_locations' => $taskLocation,
        ];
    }
}
