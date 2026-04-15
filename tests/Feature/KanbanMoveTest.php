<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('allows authorized user to move a task via kanban and set assignment and order', function () {
    // Create users
    $manager = User::factory()->create(['role' => 'manager']);
    $employee = User::factory()->create(['role' => 'employee']);

    // Create a todo task (unassigned)
    $task = Task::factory()->create(['is_completed' => false, 'assigned_to' => null]);

    // Act as manager and move to in_progress, assign to employee and set position
    $response = $this->actingAs($manager)->patchJson(route('tasks.move', $task), [
        'column' => 'in_progress',
        'assigned_to' => $employee->id,
        'position' => 1,
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);

    $task->refresh();
    expect($task->assigned_to)->toBe($employee->id);
    expect($task->is_completed)->toBeFalse();
    expect($task->kanban_order)->toBeInt();
});
