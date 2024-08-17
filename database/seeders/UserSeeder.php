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
            ->superAdmin()
            ->create([
                'name' => 'Master Admin',
                'email' => 'super@admin.com',
            ]);

        User::factory()
            ->admin()
            ->create([
                'name' => 'Master Admin',
                'email' => 'admin@admin.com',
            ]);

        User::factory()
            ->user()
            ->create([
                'name' => 'Regular User',
                'email' => 'user@user.com',
            ]);

        User::factory(5)
            ->user()
            ->create();
    }
}
