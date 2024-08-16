<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class MasterAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name'    => \App\Enums\Role::MasterAdmin->value,
            'team_id' => null,
        ]);
    }
}
