<?php

namespace App\Events;

use App\Contracts\UserCompletedTask;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCompletedTaskEvent implements UserCompletedTask
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
        return $this->userId;
    }
}
