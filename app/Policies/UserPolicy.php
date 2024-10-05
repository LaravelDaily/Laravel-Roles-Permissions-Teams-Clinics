<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::LIST_USER);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_USER);
    }
}
