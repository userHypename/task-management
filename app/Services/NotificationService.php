<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Task;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a task assignment
     */
    public static function notifyTaskAssigned(Task $task, User $assignedUser, User $createdBy)
    {
        return Notification::create([
            'user_id' => $assignedUser->id,
            'type' => 'task_assigned',
            'notifiable_type' => Task::class,
            'notifiable_id' => $task->id,
            'message' => "{$createdBy->name} assigned you a new task: {$task->title}",
            'data' => [
                'task_id' => $task->id,
                'assigned_by' => $createdBy->id,
                'title' => $task->title,
                'priority' => $task->priority,
            ],
        ]);
    }

    /**
     * Create notifications for multiple task assignments
     */
    public static function notifyTaskAssignedToMany(Task $task, array $userIds, User $createdBy)
    {
        foreach ($userIds as $userId) {
            if ($userId !== $createdBy->id) {
                $user = User::find($userId);
                if ($user) {
                    static::notifyTaskAssigned($task, $user, $createdBy);
                }
            }
        }
    }

    /**
     * Notify task creator when assigned user updates status
     */
    public static function notifyTaskStatusUpdated(Task $task, User $updatedBy, string $oldStatus, string $newStatus)
    {
        if ($task->created_by !== $updatedBy->id) {
            $creator = User::find($task->created_by);
            if ($creator) {
                Notification::create([
                    'user_id' => $creator->id,
                    'type' => 'task_updated',
                    'notifiable_type' => Task::class,
                    'notifiable_id' => $task->id,
                    'message' => "{$updatedBy->name} updated task '{$task->title}' status from {$oldStatus} to {$newStatus}",
                    'data' => [
                        'task_id' => $task->id,
                        'updated_by' => $updatedBy->id,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                    ],
                ]);
            }
        }
    }

    /**
     * Notify manager when task is completed
     */
    public static function notifyTaskCompleted(Task $task, User $completedBy)
    {
        if ($task->created_by !== $completedBy->id) {
            $creator = User::find($task->created_by);
            if ($creator) {
                Notification::create([
                    'user_id' => $creator->id,
                    'type' => 'task_completed',
                    'notifiable_type' => Task::class,
                    'notifiable_id' => $task->id,
                    'message' => "{$completedBy->name} completed task '{$task->title}'",
                    'data' => [
                        'task_id' => $task->id,
                        'completed_by' => $completedBy->id,
                    ],
                ]);
            }
        }
    }

    /**
     * Clear old read notifications (cleanup)
     */
    public static function clearOldReadNotifications($days = 30)
    {
        Notification::whereNotNull('read_at')
            ->where('updated_at', '<', now()->subDays($days))
            ->delete();
    }
}
