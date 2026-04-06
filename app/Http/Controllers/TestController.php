<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return view('dashboards.employee', [
            'stats' => [
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'pending_tasks' => 0,
                'overdue_tasks' => 0,
                'high_priority' => 0,
                'medium_priority' => 0,
                'low_priority' => 0,
            ],
            'my_tasks' => collect(),
        ]);
    }
}
