<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\TaskReviewed;
use App\Events\TaskSentToReview;
use App\Events\TaskStarted;
use Illuminate\Support\Facades\DB;

class RecordTaskEvent
{
    /**
     * Handle the TaskStarted event.
     */
    public function handleTaskStarted(TaskStarted $event): void
    {
        $this->recordEvent($event->task, 'started', $event->fromStatus, $event->toStatus);
    }

    /**
     * Handle the TaskSentToReview event.
     */
    public function handleTaskSentToReview(TaskSentToReview $event): void
    {
        $this->recordEvent($event->task, 'sent_to_review', $event->fromStatus, $event->toStatus);
    }

    /**
     * Handle the TaskReviewed event.
     */
    public function handleTaskReviewed(TaskReviewed $event): void
    {
        $this->recordEvent($event->task, 'reviewed', $event->fromStatus, $event->toStatus);
    }

    /**
     * Record the event to the database.
     */
    private function recordEvent($task, string $eventType, ?string $fromStatus, string $toStatus): void
    {
        DB::table('task_events')->insert([
            'task_id' => $task->id,
            'event_type' => $eventType,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'assignee' => $task->assignee ?? null,
            'metadata' => null,
            'occurred_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
