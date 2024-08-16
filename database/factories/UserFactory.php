<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function masterAdmin(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::create([
                'name' => 'Master Team',
            ]);

            $user->teams()->attach($team->id, ['is_owner' => true]);
            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::MasterAdmin);
        });
    }
}
