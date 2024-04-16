<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCanceledTaskEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var string
     */
    public $userId;

    /**
     * @var string
     */
    public $taskId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userId, $taskId)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
    }
}
