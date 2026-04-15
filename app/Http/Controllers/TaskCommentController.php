<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        // Ensure the user may view the task (and thus comment)
        $this->authorize('view', $task);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        // Log activity
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'type' => 'comment',
            'meta' => ['comment_id' => $comment->id],
            'note' => substr($request->body, 0, 250),
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Comment added');
    }

    public function destroy(TaskComment $comment)
    {
        $user = auth()->user();

        // Only comment owner, manager or admin may delete
        if ($comment->user_id !== $user->id && !$user->isAdmin() && !$user->isManager()) {
            abort(403, 'Unauthorized');
        }

        $task = $comment->task;
        $comment->delete();

        // Log activity
        TaskActivity::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'type' => 'comment_deleted',
            'meta' => ['comment_id' => $comment->id],
            'note' => 'Comment deleted',
        ]);

        return back()->with('success', 'Comment deleted');
    }
}
