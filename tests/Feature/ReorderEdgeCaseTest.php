<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('reorders tasks correctly when moving within same column', function () {
    $manager = User::factory()->create(['role' => 'manager']);

    // Create three unassigned tasks in todo column with orders 1,2,3
    $t1 = Task::factory()->create(['is_completed' => false, 'assigned_to' => null, 'kanban_order' => 1]);
    $t2 = Task::factory()->create(['is_completed' => false, 'assigned_to' => null, 'kanban_order' => 2]);
    $t3 = Task::factory()->create(['is_completed' => false, 'assigned_to' => null, 'kanban_order' => 3]);

    // Move t1 to position 3 (end)
    $response = $this->actingAs($manager)->patchJson(route('tasks.move', $t1), [
        'column' => 'todo',
        'position' => 3,
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);

    $t1->refresh(); $t2->refresh(); $t3->refresh();

    // Expected orders: t2 -> 1, t3 -> 2, t1 -> 3
    expect($t2->kanban_order)->toBe(1);
    expect($t3->kanban_order)->toBe(2);
    expect($t1->kanban_order)->toBe(3);
});
