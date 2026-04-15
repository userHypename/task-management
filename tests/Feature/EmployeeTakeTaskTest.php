<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('allows an employee to take an unassigned task by moving it to in_progress', function () {
    $employee = User::factory()->create(['role' => 'employee']);
    $task = Task::factory()->create(['is_completed' => false, 'assigned_to' => null]);

    $response = $this->actingAs($employee)->patchJson(route('tasks.move', $task), [
        'column' => 'in_progress',
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);

    $task->refresh();
    expect($task->assigned_to)->toBe($employee->id);
    expect($task->is_completed)->toBeFalse();
    expect($task->kanban_order)->toBeInt();
});
