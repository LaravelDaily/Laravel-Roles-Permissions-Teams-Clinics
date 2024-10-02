<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user): bool
    {
        return $user->hasAnyRole(Role::ClinicOwner, Role::ClinicAdmin);
    }
}
