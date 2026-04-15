<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskActivity;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Read filters from request
        $query = request()->input('q');
        $status = request()->input('status'); // expected: pending|completed|all

        // Base query with eager loading
        if ($user->isManager() || $user->isAdmin()) {
            $tasksQuery = Task::with(['createdBy', 'assignedTo', 'project']);
        } else {
            $tasksQuery = Task::with(['createdBy', 'assignedTo', 'project'])->assignedTo($user->id);
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

        // Additional filters: priority, assigned_to
        if ($priority = request()->input('priority')) {
            $tasksQuery->where('priority', $priority);
        }

        if ($assigned = request()->input('assigned_to')) {
            $tasksQuery->where('assigned_to', $assigned);
        }

        if ($project = request()->input('project_id')) {
            $tasksQuery->where('project_id', $project);
        }

        // Order and paginate
        $perPage = request()->input('per_page', 10); // tasks per page
        $tasks = $tasksQuery->latest()->paginate($perPage)->withQueryString();

        return view('tasks.index', compact('tasks', 'query', 'status'));
    }

    public function create()
    {
        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized: Only managers can create tasks');
        }

        $employees = User::where('role', 'employee')->get();
        $projects = Project::latest()->get();
        return view('tasks.create', compact('employees', 'projects'));
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['user_id'] = Auth::id();

        // Ensure assigned_to is an employee if provided
        if (!empty($data['assigned_to'])) {
            $assignee = User::find($data['assigned_to']);
            if (!$assignee || $assignee->role !== 'employee') {
                return back()->withErrors(['assigned_to' => 'Assigned user must be an existing employee.'])->withInput();
            }
        }

        $task = Task::create($data);

        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'type' => 'created',
            'meta' => null,
            'note' => 'Task created',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $user = Auth::user();
        // Authorize viewing via policy
        $this->authorize('view', $task);

        $task->load(['createdBy', 'assignedTo', 'project']);

        // Paginate comments and activities for usability
        $comments = $task->comments()->with('user')->latest()->paginate(10, ['*'], 'comments_page');
        $activities = $task->activities()->with('user')->latest()->paginate(10, ['*'], 'activities_page');

        return view('tasks.show', compact('task', 'comments', 'activities'));
    }

    /**
     * Kanban board view — lightweight columns: To Do, In Progress, Done
     */
    public function kanban()
    {
        $user = Auth::user();

        if ($user->isManager() || $user->isAdmin()) {
            $tasks = Task::with(['createdBy', 'assignedTo', 'project'])->get();
        } else {
            $tasks = Task::with(['createdBy', 'assignedTo', 'project'])->where('assigned_to', $user->id)->orWhere('created_by', $user->id)->get();
        }

        // Simple grouping: To Do (pending & unassigned), In Progress (pending & assigned), Done (completed)
        $todo = $tasks->where('is_completed', false)->whereNull('assigned_to');
        $inProgress = $tasks->where('is_completed', false)->whereNotNull('assigned_to');
        $done = $tasks->where('is_completed', true);

        return view('tasks.kanban', compact('todo', 'inProgress', 'done'));
    }

    public function edit(Task $task)
    {
        $user = Auth::user();
        $this->authorize('update', $task);

        if ($user->isEmployee()) {
            abort(403, 'Unauthorized: Only managers or assignees can edit tasks');
        }

        $employees = User::where('role', 'employee')->get();
        $projects = Project::latest()->get();
        return view('tasks.edit', compact('task', 'employees', 'projects'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $user = Auth::user();

        $this->authorize('update', $task);

        // Employees may only toggle completion on tasks assigned to them
        if ($user->isEmployee()) {
            if ($task->assigned_to !== $user->id) {
                abort(403, 'Unauthorized: You can only update tasks assigned to you');
            }

            $request->validate(['is_completed' => 'boolean']);

            $prevCompleted = $task->is_completed;
            $task->update(['is_completed' => $request->boolean('is_completed')]);

            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'type' => 'status_change',
                'meta' => ['from' => $prevCompleted, 'to' => $task->is_completed],
                'note' => 'Status updated by assignee',
            ]);

            return redirect()->route('tasks.show', $task)->with('success', 'Task status updated!');
        }

        // Managers/Admins can update full task fields
        if (!($user->isManager() || $user->isAdmin())) {
            abort(403, 'Unauthorized: You can only edit tasks you created or manage');
        }

        $data = $request->validated();
        $prevAssigned = $task->assigned_to;
        $prevCompleted = $task->is_completed;

        // If assigned_to provided, ensure assignee is employee
        if (!empty($data['assigned_to'])) {
            $assignee = User::find($data['assigned_to']);
            if (!$assignee || $assignee->role !== 'employee') {
                return back()->withErrors(['assigned_to' => 'Assigned user must be an existing employee.'])->withInput();
            }
        }

        $task->update($data);

        if (array_key_exists('assigned_to', $data) && $data['assigned_to'] != $prevAssigned) {
            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'type' => 'assignment',
                'meta' => ['from' => $prevAssigned, 'to' => $data['assigned_to']],
                'note' => 'Assignment changed',
            ]);
        }

        if (array_key_exists('is_completed', $data) && $data['is_completed'] != $prevCompleted) {
            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'type' => 'status_change',
                'meta' => ['from' => $prevCompleted, 'to' => $data['is_completed']],
                'note' => 'Status changed by manager/admin',
            ]);
        }

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        // Authorize via policy (owner or admin, managers allowed if created_by)
        $this->authorize('delete', $task);

        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'type' => 'deleted',
            'meta' => null,
            'note' => 'Task deleted',
        ]);

        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Update only the status of a task (toggle completion).
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        $this->authorize('update', $task);

        // Employees may only toggle their assigned tasks
        if ($user->isEmployee()) {
            if ($task->assigned_to !== $user->id) {
                abort(403, 'Unauthorized: You can only update tasks assigned to you');
            }

            $task->update(['is_completed' => !$task->is_completed]);

            TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'type' => 'status_change',
                'meta' => ['is_completed' => $task->is_completed],
                'note' => 'Status toggled by assignee',
            ]);

            return back()->with('success', 'Task status updated');
        }

        // Managers and admins may set explicit status value
        $request->validate(['is_completed' => 'required|boolean']);
        $prev = $task->is_completed;
        $task->update(['is_completed' => $request->boolean('is_completed')]);

        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'type' => 'status_change',
            'meta' => ['from' => $prev, 'to' => $task->is_completed],
            'note' => 'Status changed by manager/admin',
        ]);

        return back()->with('success', 'Task status updated');
    }
    
    /**
     * Move or update task from Kanban (AJAX).
     * Accepts: column (todo|in_progress|done), assigned_to (nullable), position (optional)
     */
    public function move(Request $request, Task $task)
    {
        $user = Auth::user();

        $data = $request->validate([
            'column' => ['required', Rule::in(['todo','in_progress','done'])],
            'assigned_to' => ['nullable','exists:users,id'],
            'position' => ['nullable','integer'],
        ]);

        // Allow employees to "take" an unassigned task by moving it to in_progress and assigning to themselves.
        $canProceed = false;
        if ($user->isEmployee() && $data['column'] === 'in_progress') {
            $assigningToSelf = array_key_exists('assigned_to', $data) && $data['assigned_to'] == $user->id;
            $takingUnassigned = !array_key_exists('assigned_to', $data) && !$task->assigned_to;
            if ($assigningToSelf || $takingUnassigned) {
                $canProceed = true;
            }
        }

        if (!$canProceed) {
            // Only allow authorized users to move tasks in other cases
            $this->authorize('update', $task);
        }

        $prevAssigned = $task->assigned_to;
        $prevCompleted = $task->is_completed;

        // Map column to is_completed
        $task->is_completed = ($data['column'] === 'done');

        // Assignment handling
        if (array_key_exists('assigned_to', $data)) {
            $task->assigned_to = $data['assigned_to'];
        } else {
            if ($data['column'] === 'in_progress' && $user->isEmployee() && !$task->assigned_to) {
                $task->assigned_to = $user->id;
            }
        }

        // Position / ordering: reindex other tasks in source and target columns to keep contiguous ordering.
        $targetColumn = $data['column'];
        $position = null;
        if (array_key_exists('position', $data) && is_numeric($data['position'])) {
            $position = (int) $data['position'];
        }

        // Determine target filters
        $targetIsCompleted = ($targetColumn === 'done');
        $targetAssignedNull = ($targetColumn === 'todo');

        // Save previous position to allow source reindexing
        $prevPosition = $task->kanban_order;
        $prevColumnIsCompleted = $task->is_completed;
        $prevAssignedNull = !$task->assigned_to;

        // If position provided, adjust ordering.
        if ($position) {
            // Determine whether source and target are same logical column
            $sameColumn = ($prevColumnIsCompleted === $targetIsCompleted) && ($prevAssignedNull === $targetAssignedNull);

            if ($sameColumn) {
                // Moving within same column: shift others appropriately
                if ($position > $prevPosition) {
                    // moving down: decrement items between prevPosition+1 .. position
                    $q = Task::where('is_completed', $targetIsCompleted)
                        ->when($targetAssignedNull, fn($q) => $q->whereNull('assigned_to'), fn($q) => $q->whereNotNull('assigned_to'))
                        ->whereBetween('kanban_order', [$prevPosition + 1, $position]);
                    $q->decrement('kanban_order');
                } elseif ($position < $prevPosition) {
                    // moving up: increment items between position .. prevPosition-1
                    $q = Task::where('is_completed', $targetIsCompleted)
                        ->when($targetAssignedNull, fn($q) => $q->whereNull('assigned_to'), fn($q) => $q->whereNotNull('assigned_to'))
                        ->whereBetween('kanban_order', [$position, $prevPosition - 1]);
                    $q->increment('kanban_order');
                }

                $task->kanban_order = $position;
                $task->save();
            } else {
                // Different column: insert into target by incrementing >= position, append if needed
                $targetQuery = Task::query();
                $targetQuery->where('is_completed', $targetIsCompleted);
                if ($targetAssignedNull) {
                    $targetQuery->whereNull('assigned_to');
                } else {
                    $targetQuery->whereNotNull('assigned_to');
                }
                $targetQuery->where('kanban_order', '>=', $position)->increment('kanban_order');
                $task->kanban_order = $position;
                $task->save();

                // Close gap in source column
                if ($prevPosition) {
                    $sourceQuery = Task::query();
                    $sourceQuery->where('is_completed', $prevColumnIsCompleted);
                    if ($prevAssignedNull) {
                        $sourceQuery->whereNull('assigned_to');
                    } else {
                        $sourceQuery->whereNotNull('assigned_to');
                    }
                    $sourceQuery->where('kanban_order', '>', $prevPosition)->decrement('kanban_order');
                }
            }
        } else {
            // Append to end of target column
            $targetQuery = Task::query();
            $targetQuery->where('is_completed', $targetIsCompleted);
            if ($targetAssignedNull) {
                $targetQuery->whereNull('assigned_to');
            } else {
                $targetQuery->whereNotNull('assigned_to');
            }
            $max = $targetQuery->max('kanban_order');
            $task->kanban_order = $max ? $max + 1 : 1;
            $task->save();

            // If moved from a different column, close gap
            if ($prevPosition && ($prevColumnIsCompleted !== $targetIsCompleted || $prevAssignedNull !== $targetAssignedNull)) {
                $sourceQuery = Task::query();
                $sourceQuery->where('is_completed', $prevColumnIsCompleted);
                if ($prevAssignedNull) {
                    $sourceQuery->whereNull('assigned_to');
                } else {
                    $sourceQuery->whereNotNull('assigned_to');
                }
                $sourceQuery->where('kanban_order', '>', $prevPosition)->decrement('kanban_order');
            }
        }

        // Create minimal activity record
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'type' => 'moved',
            'meta' => ['column' => $data['column'], 'position' => $task->kanban_order, 'from_assigned' => $prevAssigned, 'to_assigned' => $task->assigned_to, 'from_completed' => $prevCompleted, 'to_completed' => $task->is_completed],
            'note' => 'Moved via kanban',
        ]);

        return response()->json(['success' => true, 'task' => $task->fresh()]);
    }
    
}