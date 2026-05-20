<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskActivity;

class ActivityLogger
{
    public static function log(Task $task, string $action, string $description): void
    {
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
