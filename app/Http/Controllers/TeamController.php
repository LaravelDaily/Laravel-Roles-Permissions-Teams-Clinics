<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    public function __invoke(int $teamId)
    {
        $team = auth()->user()->teams()->findOrFail($teamId);

        if (! auth()->user()->belongsToTeam($team) || ! auth()->user()->hasRole(Role::SuperAdmin)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // Change team
        auth()->user()->update(['current_team_id' => $team->id]);
        setPermissionsTeamId($team->id);
        auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

        return redirect(route('dashboard'), Response::HTTP_SEE_OTHER);
    }
}
