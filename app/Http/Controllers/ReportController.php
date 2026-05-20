<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Standard statistics
        $stats = [
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('is_completed', true)->count(),
            'pending_tasks' => Task::where('is_completed', false)->count(),
            'total_projects' => Project::count(),
        ];

        // Data for charts (tasks per status)
        $tasksByStatus = Task::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Data for charts (tasks per priority)
        $tasksByPriority = Task::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        return view('reports.index', compact('stats', 'tasksByStatus', 'tasksByPriority'));
    }
}
