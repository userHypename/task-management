<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Notification;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Show all tasks with role-based filtering
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = request()->input('q');
        $status = request()->input('status');
        $perPage = 10;

        if ($user->isAdmin()) {
            $tasksQuery = Task::with(['creator', 'assignedTo', 'assignedUsers', 'project']);
        } elseif ($user->isManager()) {
            $tasksQuery = Task::where('created_by', $user->id)
                             ->with(['creator', 'assignedTo', 'assignedUsers', 'project']);
        } else {
            // Employee - see tasks assigned to them via many-to-many
            $tasksQuery = Task::whereHas('assignedUsers', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['creator', 'assignedTo', 'assignedUsers', 'project']);
        }

        if ($query) {
            $tasksQuery->where('title', 'like', '%' . $query . '%');
        }

        if ($status === 'completed') {
            $tasksQuery->where('is_completed', true);
        } elseif ($status === 'pending') {
            $tasksQuery->where('is_completed', false);
        }

        $tasks = $tasksQuery->latest()->paginate($perPage)->withQueryString();

        return view('tasks.index', compact('tasks', 'query', 'status'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized: Only managers can create tasks');
        }

        $projects = Project::all();
        $employees = User::where('role', 'employee')->where('account_status', 'active')->get();
        
        return view('tasks.create', compact('projects', 'employees'));
    }

    /**
     * Store new task with multiple employee assignments
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized: Only managers can create tasks');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in-progress,on-hold,completed,cancelled',
            'assigned_users' => 'required|array|min:1',
            'assigned_users.*' => 'exists:users,id',
        ]);

        DB::beginTransaction();
        
        try {
            // Create the task
            $task = Task::create([
                'created_by' => Auth::id(),
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'project_id' => $validated['project_id'],
                'due_date' => $validated['due_date'],
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'is_completed' => $validated['status'] === 'completed',
            ]);

            // Assign task to multiple employees
            if (!empty($validated['assigned_users'])) {
                $attachData = [];
                foreach ($validated['assigned_users'] as $employeeId) {
                    $attachData[$employeeId] = [
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $task->assignedUsers()->attach($attachData);
                
                // Set legacy assigned_to field for backward compatibility
                $task->assigned_to = $validated['assigned_users'][0];
                $task->save();
            }

            DB::commit();
            
            return redirect()->route('tasks.index')
                ->with('success', 'Task created and assigned to ' . count($validated['assigned_users']) . ' employee(s)!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create task: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display single task
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isEmployee()) {
            $isAssigned = $task->assignedUsers()->where('user_id', $user->id)->exists();
            if (!$isAssigned) {
                abort(403, 'Unauthorized: You can only view tasks assigned to you');
            }
        } elseif ($user->isManager() && $task->created_by !== $user->id) {
            abort(403, 'Unauthorized: You can only view tasks you created');
        }

        $task->load(['creator', 'assignedUsers', 'project', 'comments.user', 'activities.user']);
        
        // Get current user's assignment data if employee
        $userAssignment = null;
        if ($user->isEmployee()) {
            $userAssignment = $task->assignedUsers()->where('user_id', $user->id)->first();
        }

        return view('tasks.show', compact('task', 'userAssignment'));
    }

    /**
     * Show edit form
     */
    public function edit(Task $task)
    {
        $user = Auth::user();
        
        if ($user->isEmployee()) {
            abort(403, 'Unauthorized: Only managers can edit tasks');
        }

        if ($user->isManager() && $task->created_by !== $user->id) {
            abort(403, 'Unauthorized: You can only edit tasks you created');
        }

        $projects = Project::all();
        $employees = User::where('role', 'employee')->where('account_status', 'active')->get();
        $currentAssignees = $task->assignedUsers()->pluck('user_id')->toArray();
        
        return view('tasks.edit', compact('task', 'projects', 'employees', 'currentAssignees'));
    }

    /**
     * Update task
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Employee updating their own task status
        if ($user->isEmployee()) {
            $isAssigned = $task->assignedUsers()->where('user_id', $user->id)->exists();
            if (!$isAssigned) {
                abort(403, 'Unauthorized');
            }
            
            $validated = $request->validate([
                'pivot_status' => 'required|in:pending,in-progress,completed',
                'completion_notes' => 'nullable|string',
            ]);
            
            $updateData = [
                'status' => $validated['pivot_status'],
                'updated_at' => now(),
            ];
            
            if ($validated['pivot_status'] === 'in-progress') {
                $updateData['started_at'] = now();
            }
            
            if ($validated['pivot_status'] === 'completed') {
                $updateData['completed_at'] = now();
            }
            
            if ($validated['completion_notes']) {
                $updateData['completion_notes'] = $validated['completion_notes'];
            }
            
            $task->assignedUsers()->updateExistingPivot($user->id, $updateData);
            
            // Check if all assigned employees completed
            $allCompleted = $task->assignedUsers()
                ->wherePivot('status', '!=', 'completed')
                ->count() === 0;
            
            if ($allCompleted && !$task->is_completed) {
                $task->update([
                    'is_completed' => true,
                    'status' => 'completed',
                ]);
            }
            
            return redirect()->route('tasks.show', $task)
                ->with('success', 'Task status updated successfully!');
        }
        
        // Manager/Admin updating task
        if (!($user->isManager() || $user->isAdmin())) {
            abort(403);
        }

        if ($user->isManager() && $task->created_by !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in-progress,on-hold,completed,cancelled',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'project_id' => $validated['project_id'],
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'is_completed' => $validated['status'] === 'completed',
        ]);

        // Update assignments if provided
        if (isset($validated['assigned_users'])) {
            $syncData = [];
            foreach ($validated['assigned_users'] as $employeeId) {
                $syncData[$employeeId] = ['status' => 'pending', 'updated_at' => now()];
            }
            $task->assignedUsers()->sync($syncData);
            
            if (!empty($validated['assigned_users'])) {
                $task->assigned_to = $validated['assigned_users'][0];
                $task->save();
            }
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Delete task
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        if ($user->isEmployee()) {
            abort(403, 'Unauthorized: Only managers can delete tasks');
        }

        if ($user->isManager() && $task->created_by !== $user->id) {
            abort(403, 'Unauthorized: You can only delete tasks you created');
        }

        $task->delete();
        
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * My Tasks view for employees
     */
    public function myTasks()
    {
        $user = Auth::user();
        
        if (!$user->isEmployee()) {
            return redirect()->route('tasks.index');
        }
        
        $tasks = Task::whereHas('assignedUsers', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['project', 'creator'])->latest()->paginate(10);
        
        $stats = [
            'total' => Task::whereHas('assignedUsers', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
            'pending' => Task::whereHas('assignedUsers', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'pending');
            })->count(),
            'in_progress' => Task::whereHas('assignedUsers', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'in-progress');
            })->count(),
            'completed' => Task::whereHas('assignedUsers', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })->count(),
        ];
        
        return view('tasks.my-tasks', compact('tasks', 'stats'));
    }
}