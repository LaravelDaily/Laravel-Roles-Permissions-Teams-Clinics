<?php

namespace App\Http\Controllers;

use App\Enums\Role as RoleEnum;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreTeamRequest;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role as RoleModel;

class TeamController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Team::class);

        $teams = Team::where('name', '!=', 'Master Admin Team')->paginate();

        return view('teams.index', compact('teams'));
    }

    public function create(): View
    {
        Gate::authorize('create', Team::class);

        $users = User::whereRelation('rolesWithoutTeam', 'name', '=', RoleEnum::ClinicOwner->value)
            ->pluck('name', 'id');

        return view('teams.create', compact('users'));
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        Gate::authorize('create', Team::class);

        $team = Team::create(['name' => $request->input('clinic_name')]);

        if ($request->has('user_id')) {
            $user = User::find($request->integer('user_id'));

            $user->teams()
                ->attach($team->id, [
                    'model_type' => User::class,
                    'role_id'    => RoleModel::where('name', RoleEnum::ClinicOwner->value)->first()->id,
                ]);

            $user->update(['current_team_id' => $team->id]);
        } else {
            $user = User::create($request->only(['name', 'email', 'password']) + ['current_team_id' => $team->id]);

            $user
                ->teams()
                ->attach($team->id, [
                    'model_type' => User::class,
                    'role_id' => RoleModel::where('name', RoleEnum::ClinicOwner->value)->first()->id,
                ]);
        }

        return redirect()->route('teams.index');
    }

    public function changeCurrentTeam(int $teamId)
    {
        Gate::authorize('changeTeam', Team::class);

        $team = auth()->user()->teams()->findOrFail($teamId);

        if (! auth()->user()->belongsToTeam($team)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // Change team
        auth()->user()->update(['current_team_id' => $team->id]);
        setPermissionsTeamId($team->id);
        auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

        return redirect(route('dashboard'), Response::HTTP_SEE_OTHER);
    }
}
