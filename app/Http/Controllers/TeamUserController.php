<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Enums\Role as RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role as RoleModel;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreTeamUserRequest;

class TeamUserController extends Controller
{
    public function store(StoreTeamUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', Team::class);

        $user = User::create($request->only(['name', 'email', 'password']));

        $team = $user
            ->teams()
            ->create(['name' => $request->input('team_name')], [
                'model_type' => User::class,
                'role_id' => RoleModel::where('name', RoleEnum::ClinicOwner->value)->first()->id,
            ]);

        $user->update(['current_team_id' => $team->id]);

        return redirect()->route('teams.index');
    }
}
