<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user): bool
    {
        return $user->hasAnyRole([Role::ClinicAdmin, Role::Doctor, Role::Staff]);
    }

    public function update(User $user, Task $task): bool
    {
        return ($user->hasAnyRole([Role::ClinicAdmin, Role::Doctor, Role::Staff])
            || $user->id === $task->user_id) && $user->current_team_id === $task->team_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasAnyRole([Role::ClinicAdmin, Role::Doctor, Role::Staff]) && $user->current_team_id === $task->team_id;
    }
}
