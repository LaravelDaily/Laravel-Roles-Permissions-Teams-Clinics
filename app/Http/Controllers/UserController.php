<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as RoleModel;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')
            ->whereHas('roles', function (Builder $query) {
                return $query->where('name', '=', Role::ClinicAdmin->value)
                    ->orWhere('name', '=', Role::Doctor->value)
                    ->orWhere('name', '=', Role::Staff->value);
            })
            ->whereRelation('teams', 'team_id', '=', auth()->user()->current_team_id)->get();

        return view('user.index', compact('users'));
    }

    public function create(): View
    {
        $roles = RoleModel::where('name', '=', Role::ClinicAdmin->value)
            ->orWhere('name', '=', Role::Doctor->value)
            ->orWhere('name', '=', Role::Staff->value)
            ->pluck('name', 'id');

        return view('user.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create($request->except('role_id'));

        $user->assignRole($request->integer('role_id'));

        return redirect()->route('users.index');
    }
}
