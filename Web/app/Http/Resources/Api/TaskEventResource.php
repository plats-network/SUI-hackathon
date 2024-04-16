<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\TaskEventDetailResource;
use App\Models\Event\{EventUserTicket, UserJoinEvent};

class TaskEventResource extends JsonResource
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
        $ticket = EventUserTicket::whereUserId($userId)
            ->whereTaskId($this->task_id)
            ->first();
        $sessionId = $this && $this->type == 0 ? $this->id :null;
        $boothId = $this && $this->type == 1 ? $this->id :null;
        $sessionSuccess = UserJoinEvent::whereUserId($userId)
            ->whereTaskEventId($sessionId)
            ->count();
        $boottSuccess = UserJoinEvent::whereUserId($userId)
            ->whereTaskEventId($boothId)
            ->count();

        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'description' => $this->description,
            // 'banner_url' => $this->banner_url,
            'session_success' => $this->type == 0 ? (string)  $sessionSuccess . '/' . $this->max_job : null,
            'booth_success' => $this->type == 1 ? (string) $boottSuccess . '/' . $this->max_job : null,
            // 'max_job' => $this->max_job,
            'type' => $this->type == 0 ? 'Session' : 'Booth',
            'type_number' => $this->type,
            'session_code' => $ticket && $ticket->sesion_code ? $ticket->sesion_code : null,
            'booth_code' => $ticket && $ticket->booth_code ? $ticket->booth_code : null,
            'jobs' => $this->detail->count() >= 0
                ? TaskEventDetailResource::collection($this->detail)
                : null
        ];

        return parent::toArray($request);
    }
}
