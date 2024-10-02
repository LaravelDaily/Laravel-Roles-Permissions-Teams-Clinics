<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $randomAssignee = collect([
            User::factory()->doctor(),
            User::factory()->staff(),
        ])->random();

        return [
            'name'     => fake()->text(30),
            'due_date' => now()->addDays(rand(1, 100)),

            'assigned_to_user_id' => $randomAssignee,
            'patient_id' => User::factory()->patient(),
        ];
    }
}
