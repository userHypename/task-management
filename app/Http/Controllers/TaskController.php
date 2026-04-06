<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Show all tasks with role-based filtering
     * Manager: sees all tasks
     * Employee: sees only assigned tasks
     */
    public function index()
    {
        $user = Auth::user();
        
        // Read filters from request
        $query = request()->input('q');
        $status = request()->input('status'); // expected: pending|completed|all
        $perPage = 10; // tasks per page

        // Base query
        if ($user->isManager() || $user->isAdmin()) {
            $tasksQuery = Task::with(['createdBy', 'assignedTo']);
        } else {
            $tasksQuery = Task::with(['createdBy', 'assignedTo'])->assignedTo($user->id);
        }

        // Search
        if ($query) {
            $tasksQuery->where('title', 'like', '%' . $query . '%');
        }

        // Filter by status
        if ($status === 'completed') {
            $tasksQuery->where('is_completed', true);
        } elseif ($status === 'pending') {
            $tasksQuery->where('is_completed', false);
        }

        // Order and paginate
        $tasks = $tasksQuery->latest()->paginate($perPage)->withQueryString();

        return view('tasks.index', compact('tasks', 'query', 'status'));
    }

    /**
     * Show create form
     * Only managers can create tasks
     */
    public function create()
    {
        // Only managers and admins can create tasks
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized: Only managers can create tasks');
        }

        // Get all employees for dropdown
        $employees = User::where('role', 'employee')->get();
        
        return view('tasks.create', compact('employees'));
    }

    /**
     * Store new task
     */
    public function store(Request $request)
    {
        // Only managers can create tasks
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized: Only managers can create tasks');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'employee'),
            ],
        ]);

        Task::create([
            'created_by' => Auth::id(),
            'user_id' => Auth::id(), // Keep for backward compatibility
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display single task
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Employee can only view tasks assigned to them
        if ($user->isEmployee() && $task->assigned_to !== $user->id) {
            abort(403, 'Unauthorized: You can only view tasks assigned to you');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show edit form
     * Only manager who created it or admin can edit
     */
    public function edit(Task $task)
    {
        $user = Auth::user();
        
        // Employees cannot edit tasks; managers and admins may
        if ($user->isEmployee()) {
            abort(403, 'Unauthorized: Only managers can edit tasks');
        }

        // Get all employees for dropdown
        $employees = User::where('role', 'employee')->get();
        
        return view('tasks.edit', compact('task', 'employees'));
    }

    /**
     * Update task in database
     */
    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Allow employees to update only is_completed if task is assigned to them
        if ($user->isEmployee()) {
            if ($task->assigned_to !== $user->id) {
                abort(403, 'Unauthorized: You can only update tasks assigned to you');
            }
            
            $request->validate([
                'is_completed' => 'boolean',
            ]);
            
            $task->update(['is_completed' => $request->boolean('is_completed')]);
            return redirect()->route('tasks.index')->with('success', 'Task status updated!');
        }

        // Manager/Admin can update everything
        if (!($user->isManager() || $user->isAdmin())) {
            abort(403, 'Unauthorized: You can only edit tasks you created');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'is_completed' => 'boolean',
            'assigned_to' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', 'employee'),
            ],
        ]);

        $data = $request->only([
            'title', 'description', 'due_date', 'priority', 'is_completed', 'assigned_to'
        ]);
        
        $task->update($data);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Delete task
     * Only manager who created it or admin can delete
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Employees cannot delete tasks; managers and admins may
        if ($user->isEmployee()) {
            abort(403, 'Unauthorized: Only managers can delete tasks');
        }

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
}