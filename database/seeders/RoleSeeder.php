<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (\App\Enums\Role::cases() as $role) {
            Role::create([
                'name'    => $role->value,
                'team_id' => null,
            ]);
        }
    }
}
