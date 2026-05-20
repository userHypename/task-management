<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'project_id' => Project::factory(),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'due_date' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'on-hold', 'completed', 'cancelled']),
            'is_completed' => $this->faker->boolean(30),
            'kanban_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
