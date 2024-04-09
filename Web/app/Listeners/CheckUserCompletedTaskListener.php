<?php

namespace App\Listeners;

use App\Contracts\UserCompletedSubtask;
use App\Events\UserCompletedTaskEvent;
use App\Repositories\LocationHistoryRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TaskUserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CheckUserCompletedTaskListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var \App\Repositories\TaskRepository
     */
    protected $taskRepository;

    /**
     * @var \App\Repositories\LocationHistoryRepository
     */
    protected $localHistoryRepo;

    /**
     * @var \App\Repositories\TaskUserRepository
     */
    protected $taskUserRepository;

    /**
     * @param \App\Repositories\TaskRepository $taskRepository
     * @param \App\Repositories\LocationHistoryRepository $localHistoryRepo
     * @param \App\Repositories\TaskUserRepository $taskUserRepository
     */
    public function __construct(
        TaskRepository $taskRepository,
        LocationHistoryRepository $localHistoryRepo,
        TaskUserRepository $taskUserRepository
    ) {
        $this->taskRepository   = $taskRepository;
        $this->localHistoryRepo = $localHistoryRepo;
        $this->taskUserRepository = $taskUserRepository;
    }

    /**
     * Handle the event.
     *
     * @param UserCompletedSubtask $event
     *
     * @return void
     */
    public function handle(UserCompletedSubtask $event)
    {
        $userId = $event->userId();
        $taskId = $event->taskId();

        //Get task end check
        $task = $this->taskRepository->userJoinedTask($userId, $taskId);
        if (is_null($task)) {
            return;
        }

        $subtasks   = $this->taskRepository->subtasks($task);
        $subtaskIds = $subtasks->pluck('id');

        //Get subtasks has been processed by user
        $userStatusInSubtask = $this->localHistoryRepo->userStatusInSubTasks($userId, $subtaskIds);
        if (
            $userStatusInSubtask
                ->whereIn('location_id', $subtaskIds)
                ->whereNotNull('ended_at')
                ->count() != $subtasks->count()
        ) {
            return;
        }

        // Update status
        $this->taskUserRepository->updateStatusTask($taskId, $userId, USER_COMPLETED_TASK);
        //Fire event
        UserCompletedTaskEvent::dispatch($userId, $taskId);
    }
}
