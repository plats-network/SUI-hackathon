<?php

namespace App\Events;

use App\Contracts\UserCompletedSubtask;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCheckedInLocationEvent implements UserCompletedSubtask
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var \App\Models\TaskLocationHistory
     */
    public $userLocation;

    /**
     * @var \App\Models\TaskLocation
     */
    public $taskLocation;

    /**
     * @var string
     */
    public $taskId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userLocation, $taskLocation, $taskId)
    {
        $this->userLocation = $userLocation;
        $this->taskLocation = $taskLocation;
        $this->taskId    = $taskId;
    }

    /**
     * Id of task
     *
     * @return string
     */
    public function taskId()
    {
        return $this->taskId;
    }

    /**
     * User Id
     *
     * @return string
     */
    public function userId()
    {
        return $this->userLocation->user_id;
    }
}
