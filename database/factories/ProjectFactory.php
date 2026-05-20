<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-30 days', '+0 days');
        
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'manager_id' => User::factory(),
            'status' => $this->faker->randomElement(['active', 'on-hold', 'completed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'start_date' => $startDate,
            'due_date' => $this->faker->dateTimeBetween('+1 days', '+60 days'),
        ];
    }
}
