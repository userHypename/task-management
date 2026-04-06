<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'due_date' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'is_completed' => $this->faker->boolean(30),
        ];
    }
}
