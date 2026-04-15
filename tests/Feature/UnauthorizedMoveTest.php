<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('prevents an employee from moving a task they are not allowed to update', function () {
    $employee = User::factory()->create(['role' => 'employee']);
    $other = User::factory()->create(['role' => 'employee']);
    $task = Task::factory()->create(['is_completed' => false, 'assigned_to' => $other->id]);

    // Employee who is not the assignee nor owner should be forbidden
    $response = $this->actingAs($employee)->patchJson(route('tasks.move', $task), [
        'column' => 'done',
    ]);

    $response->assertStatus(403);
});
