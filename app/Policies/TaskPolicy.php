<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Task;
use App\Models\User;
use App\Enums\Permission;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::LIST_TASK);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_TASK);
    }

    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo(Permission::EDIT_TASK);
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_TASK);
    }
}
