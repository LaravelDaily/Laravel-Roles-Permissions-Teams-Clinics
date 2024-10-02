<?php

namespace Database\Seeders;

use App\Enums\Permission;
use Illuminate\Database\Seeder;
use App\Enums\Role as RoleEnum;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permission::cases() as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission->value]);
        }

        foreach (RoleEnum::cases() as $role) {
            $role = Role::create(['name' => $role->value]);

            $this->syncPermissionsToRole($role);
        }
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = [];

        switch ($role->name) {
            case RoleEnum::Doctor->value:
            case RoleEnum::Staff->value:
            case RoleEnum::ClinicAdmin->value:
                $permissions = [
                    Permission::CREATE_TASK,
                    Permission::EDIT_TASK,
                    Permission::DELETE_TASK,
                ];
                break;
            case RoleEnum::Patient->value:
                $permissions = [
                    Permission::LIST_TASK,
                ];
                break;
            case RoleEnum::ClinicOwner->value:
                $permissions = [
                    Permission::CREATE_USER,
                    Permission::SWITCH_TEAM,
                ];
                break;
            case RoleEnum::MasterAdmin->value:
                $permissions = [
                    Permission::CREATE_TEAM,
                ];
                break;
        }

        $role->syncPermissions($permissions);
    }
}
