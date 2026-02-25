<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\TaskReviewed;
use App\Events\TaskSentToReview;
use App\Events\TaskStarted;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->wasChanged('status')) {
            $from = $task->getOriginal('status');
            $to = $task->status;

            match ([$from, $to]) {
                ['pending', 'in_progress'] => event(new TaskStarted($task, $from, $to)),
                ['in_progress', 'review'] => event(new TaskSentToReview($task, $from, $to)),
                ['review', 'done'],
                ['review', 'pending'],
                ['review', 'blocked'] => event(new TaskReviewed($task, $from, $to)),
                default => null,
            };
        }
    }
}
