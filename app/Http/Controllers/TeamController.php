<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Team;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::where('name', '!=', 'Master Admin Team')->paginate();

        return view('teams.index', compact('teams'));
    }

    public function changeCurrentTeam(int $teamId)
    {
        $team = auth()->user()->teams()->findOrFail($teamId);

        if (! auth()->user()->belongsToTeam($team) || ! auth()->user()->hasRole(Role::ClinicOwner)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // Change team
        auth()->user()->update(['current_team_id' => $team->id]);
        setPermissionsTeamId($team->id);
        auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

        return redirect(route('dashboard'), Response::HTTP_SEE_OTHER);
    }
}
