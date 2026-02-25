<?php

declare(strict_types=1);

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure the observer and event listeners are registered
    \App\Models\Task::observe(\App\Observers\TaskObserver::class);
});

describe('Task Event Tracking', function () {
    it('creates task_events record when task status changes to in_progress', function () {
        $task = Task::factory()->create(['status' => 'pending']);

        $task->update(['status' => 'in_progress']);

        $this->assertDatabaseHas('task_events', [
            'task_id' => $task->id,
            'event_type' => 'started',
            'from_status' => 'pending',
            'to_status' => 'in_progress',
        ]);
    });

    it('creates task_events record when task status changes to review', function () {
        $task = Task::factory()->create(['status' => 'in_progress']);

        $task->update(['status' => 'review']);

        $this->assertDatabaseHas('task_events', [
            'task_id' => $task->id,
            'event_type' => 'sent_to_review',
            'from_status' => 'in_progress',
            'to_status' => 'review',
        ]);
    });

    it('creates task_events record when task is reviewed and approved', function () {
        $task = Task::factory()->create(['status' => 'review']);

        $task->update(['status' => 'done']);

        $this->assertDatabaseHas('task_events', [
            'task_id' => $task->id,
            'event_type' => 'reviewed',
            'from_status' => 'review',
            'to_status' => 'done',
        ]);
    });

    it('creates task_events record when task is sent back from review to pending', function () {
        $task = Task::factory()->create(['status' => 'review']);

        $task->update(['status' => 'pending']);

        $this->assertDatabaseHas('task_events', [
            'task_id' => $task->id,
            'event_type' => 'reviewed',
            'from_status' => 'review',
            'to_status' => 'pending',
        ]);
    });

    it('creates task_events record when task is blocked from review', function () {
        $task = Task::factory()->create(['status' => 'review']);

        $task->update(['status' => 'blocked']);

        $this->assertDatabaseHas('task_events', [
            'task_id' => $task->id,
            'event_type' => 'reviewed',
            'from_status' => 'review',
            'to_status' => 'blocked',
        ]);
    });

    it('creates multiple event records for multiple status transitions', function () {
        $task = Task::factory()->create(['status' => 'pending']);

        $task->update(['status' => 'in_progress']);
        $task->update(['status' => 'review']);
        $task->update(['status' => 'done']);

        $events = \DB::table('task_events')
            ->where('task_id', $task->id)
            ->orderBy('occurred_at')
            ->get();

        expect($events)->toHaveCount(3);
        expect($events[0]->event_type)->toBe('started');
        expect($events[1]->event_type)->toBe('sent_to_review');
        expect($events[2]->event_type)->toBe('reviewed');
    });

    it('captures assignee information in task_events', function () {
        $task = Task::factory()->create([
            'status' => 'pending',
            'assignee' => 'john-doe',
        ]);

        $task->update(['status' => 'in_progress']);

        $this->assertDatabaseHas('task_events', [
            'task_id' => $task->id,
            'event_type' => 'started',
            'assignee' => 'john-doe',
        ]);
    });
});
