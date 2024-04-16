<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCheckingLocationEvent
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
     * @var string
     */
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userLocation, $taskLocation, $taskId, $userId)
    {
        $this->userLocation = $userLocation;
        $this->taskLocation = $taskLocation;
        $this->taskId    = $taskId;
        $this->userId = $userId;
    }
}
