<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => fake()->text(30),
            'due_date' => now()->addDays(rand(1, 100)),

            'user_id' => User::factory(),
        ];
    }
}
