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
            case RoleEnum::MasterAdmin->value:
                $permissions = [
                    Permission::LIST_TEAM,
                    Permission::CREATE_TEAM,
                ];
                break;
            case RoleEnum::ClinicOwner->value:
                $permissions = [
                    Permission::SWITCH_TEAM,
                    Permission::LIST_USER,
                    Permission::CREATE_USER,
                ];
                break;
            case RoleEnum::ClinicAdmin->value:
                $permissions = [
                    Permission::LIST_USER,
                    Permission::CREATE_USER,
                    Permission::LIST_TASK,
                    Permission::CREATE_TASK,
                    Permission::EDIT_TASK,
                    Permission::DELETE_TASK,
                ];
                break;
            case RoleEnum::Staff->value:
                $permissions = [
                    Permission::LIST_TASK,
                    Permission::CREATE_TASK,
                    Permission::EDIT_TASK,
                    Permission::DELETE_TASK,
                ];
                break;
            case RoleEnum::Doctor->value:
                $permissions = [
                    Permission::LIST_TASK,
                    Permission::CREATE_TASK,
                    Permission::EDIT_TASK,
                ];
                break;
            case RoleEnum::Patient->value:
                $permissions = [
                    Permission::LIST_TASK,
                ];
                break;
        }

        $role->syncPermissions($permissions);
    }
}
