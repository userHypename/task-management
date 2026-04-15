<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskActivityController extends Controller
{
    public function index(Task $task)
    {
        $activities = $task->activities()->with('user')->latest()->paginate(10, ['*'], 'activities_page');
        return view('tasks.activities', compact('task', 'activities'));
    }
}
