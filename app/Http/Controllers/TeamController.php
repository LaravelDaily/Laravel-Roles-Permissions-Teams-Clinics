<?php

namespace App\Http\Controllers;

use App\Enums\Role as RoleEnum;
use App\Models\Team;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreTeamRequest;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role as RoleModel;

class TeamController extends Controller
{
    public function index(): View
    {
        $teams = Team::where('name', '!=', 'Master Admin Team')->paginate();

        return view('teams.index', compact('teams'));
    }

    public function create(): View
    {
        $users = User::whereRelation('rolesWithoutTeam', 'name', '=', RoleEnum::ClinicOwner->value)->pluck('name', 'id');

        return view('teams.create', compact('users'));
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $team = Team::create($request->validated());

        User::find($request->integer('user_id'))
            ->teams()
            ->attach($team->id, [
                'model_type' => User::class,
                'role_id' => RoleModel::where('name', RoleEnum::ClinicOwner->value)->first()->id,
            ]);

        return redirect()->route('teams.index');
    }

    public function changeCurrentTeam(int $teamId)
    {
        $team = auth()->user()->teams()->findOrFail($teamId);

        if (! auth()->user()->belongsToTeam($team) || ! auth()->user()->hasRole(RoleEnum::ClinicOwner)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // Change team
        auth()->user()->update(['current_team_id' => $team->id]);
        setPermissionsTeamId($team->id);
        auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

        return redirect(route('dashboard'), Response::HTTP_SEE_OTHER);
    }
}
