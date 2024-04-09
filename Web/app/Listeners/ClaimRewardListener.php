<?php

namespace App\Listeners;

use App\Contracts\UserCompletedTask;
use App\Repositories\TaskUserRepository;
use App\Services\BLCGatewayService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClaimRewardListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var \App\Repositories\TaskUserRepository
     */
    protected $taskUserRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TaskUserRepository $taskUserRepository)
    {
        $this->taskUserRepository = $taskUserRepository;
    }

    /**
     * Handle the event.
     *
     * @param \App\Contracts\UserCompletedTask $event
     *
     * @return void
     */
    public function handle(UserCompletedTask $event)
    {
        $userTask = $this->taskUserRepository->userStartedTask($event->taskId(), $event->userId());
        if (is_null($userTask)) {
            return;
        }

        $task = $userTask->task;

        if (is_null($task)) {
            return;
        }
        
        app(BLCGatewayService::class)->award($task->reward_amount ?? null, $task->id, $userTask->wallet_address);
    }
}
