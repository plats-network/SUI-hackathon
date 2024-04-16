<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\TaskEventResource;
use Illuminate\Http\Resources\MissingValue;
use App\Helpers\{DateHelper, ActionHelper, BaseImage};
use Illuminate\Support\Facades\Http;
use App\Models\{
    TaskUser, Reward, UserTaskAction,
    TaskGroup, Group, User
};
use App\Models\Event\{EventUserTicket, TaskEvent,
    TaskEventDetail, UserJoinEvent, EventDiscords, EventSocial};
use Carbon\Carbon;

class TaskDetailResource extends JsonResource
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
        $url = 'https://'.config('plats.event').'/event/'.$this->id;
        $likeCount = UserTaskAction::whereUserId($userId)->whereTaskId($this->id)->whereType(TASK_LIKE)->count();
        $pinCount = UserTaskAction::whereUserId($userId)->whereTaskId($this->id)->whereType(TASK_PIN)->count();
        $checkTaskStart = TaskUser::whereUserId($userId)->whereTaskId($this->id)->whereStatus(0)->count();
        $groups = $this->groupTasks->count() > 0 ? TaskGroupResource::collection($this->groupTasks) : null;
        $creator = User::whereId($this->creator_id)->first();
        $ticket = EventUserTicket::whereUserId($userId)->whereTaskId($this->id)->first();
        $taskSession = TaskEvent::whereTaskId($this->id)->whereType(TASK_SESSION)->first();
        $taskBooth = TaskEvent::whereTaskId($this->id)->whereType(TASK_BOOTH)->first();
        $sessionSuccess = UserJoinEvent::whereUserId($userId)
            ->whereTaskEventId(optional($taskSession)->id)
            ->count();
        $boothSuccess = UserJoinEvent::whereUserId($userId)
            ->whereTaskEventId(optional($taskBooth)->id)
            ->count();
        $taskIds = EventUserTicket::select('task_id')->whereUserId($userId)->pluck('task_id')->toArray();

        $sessions = [];
        $booths = [];
        $dataSession = null;
        $dataBooth = null;

        if ($taskSession) {
            $ses = TaskEventDetail::whereTaskEventId($taskSession->id)->get();

            if ($ses) {
                foreach($ses as $session) {
                    $doneJob = UserJoinEvent::whereUserId($userId)
                        ->whereTaskEventDetailId($session->id)
                        ->exists();
                    $sessions[] = [
                        'id' => $session->id,
                        'name' => $session->name,
                        'description' => $session->description,
                        'code' => $session->code,
                        'status_done' => $doneJob
                    ];
                }
            }

            $dataSession = [
                'id' => $taskSession->id,
                'name' => $taskSession->name,
                'session_success' => $sessionSuccess.'/' . count($sessions),
                'jobs' => $sessions
            ];
        }

        if ($taskBooth) {
            $booth = TaskEventDetail::whereTaskEventId($taskBooth->id)->get();
            if ($booth) {
                foreach($booth as $item) {
                    $doneJob = UserJoinEvent::whereUserId($userId)
                        ->whereTaskEventDetailId($item->id)
                        ->exists();
                    $booths[] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'code' => $item->code,
                        'status_done' => $doneJob
                    ];
                }
            }

            $dataBooth = [
                'id' => $taskBooth->id,
                'name' => $taskBooth->name,
                'booth_success' => $boothSuccess. '/' . count($booths),
                'booth_code' => 1,
                'jobs' => $booths
            ];
        }

        $discord = EventDiscords::whereTaskId($this->id)->first();
        $twitter = EventSocial::whereTaskId($this->id)->first();

        $discordJob = null;
        $twitterJob = null;
        if ($discord) {
            $discordJob = [
                'id' => $discord->id,
                'is_join' => false
            ];
        }

        if ($twitter) {
            $twitterJob = [
                'id' => $twitter->id,
                'url' => $twitter->url,
                'text_tweet' => $twitter->text,
                'is_comment' => false,
                'is_like' => false,
                'is_retweet' => false,
                'is_tweet' => false,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'banner_url' => $this->banner_url,
            'post_by' => $creator ? $creator->name : 'Plats Team',
            'address' => $this->address,
            'date' => DateHelper::getDateTime($this->created_at),
            'lat' => (float) $this->lat,
            'lng' => (float) $this->lng,
            'start_at' => DateHelper::getDateTime($this->start_at),
            'end_at' => DateHelper::getDateTime($this->end_at),
            'task_start' => $checkTaskStart > 0 ? true : false,
            'type' => $this->type == 1 ? 'event' : 'task',
            'code_session' => optional($ticket)->sesion_code,
            'code_booth' => optional($ticket)->booth_code,
            'like' => [
                'is_like' => $likeCount > 0 ? true : false,
                'type_like' => $likeCount > 0 ? 'like' : 'unlike'
            ],
            'pin' => [
                'is_pin' => $pinCount > 0 ? true : false,
                'type_pin' => $pinCount > 0 ? 'pin' : 'unpin',
            ],
            'flag_ticket' => in_array($this->id, $taskIds),
            'link_quiz' => 'https://'.config('plats.event').'/quiz-game/answers/'.$this->id,
            'session' => $dataSession,
            'booth' => $dataBooth,
            'discord' => $discordJob,
            'twitter' => $twitterJob,
            'shares' => [
                'facebook' => $url.'?type=facebook',
                'twitter' => $url.'?type=twitter',
                'telegram' => $url.'?type=telegram',
                'discord' => $url.'?type=discord',
                'email' => $url.'?type=email',
                'linkedin' => $url.'?type=linkedin',
                'whatsapp' => $url.'?type=whatsapp'
            ]
        ];
    }
}
