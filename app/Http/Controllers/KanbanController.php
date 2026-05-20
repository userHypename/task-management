<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function index()
    {
        $statuses = ['pending', 'in-progress', 'on-hold', 'completed', 'cancelled'];
        
        $tasks = Task::with(['project', 'assignedTo', 'creator'])
            ->get()
            ->groupBy('status');

        // Ensure all statuses exist in the array, even if empty
        foreach ($statuses as $status) {
            if (!isset($tasks[$status])) {
                $tasks[$status] = collect();
            }
        }

        return view('kanban.index', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'kanban_order' => 'sometimes|integer',
        ]);

        // Authorization check
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            if ($task->assigned_to !== auth()->id()) {
                abort(403);
            }
        }

        $task->update($validated);

        return response()->json(['message' => 'Status updated successfully']);
    }
}

