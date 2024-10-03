<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->masterAdmin()
            ->create([
                'name' => 'Master Admin',
                'email' => 'master@admin.com',
            ]);

        User::factory()
            ->clinicOwner()
            ->create([
                'name' => 'Clinic Owner',
                'email' => 'owner@clinic.com',
            ]);

        User::factory()
            ->clinicAdmin()
            ->create([
                'name' => 'Clinic Admin',
                'email' => 'admin@clinic.com',
            ]);

        User::factory()
            ->staff()
            ->create([
                'name' => 'Staff User',
                'email' => 'staff@clinic.com',
            ]);

        User::factory()
            ->patient()
            ->create([
                'name' => 'Regular Patient',
                'email' => 'user@clinic.com',
            ]);

        User::factory(5)
            ->patient()
            ->create();

        User::factory(5)
            ->doctor()
            ->create();

        User::factory(5)
            ->staff()
            ->create();
    }
}
