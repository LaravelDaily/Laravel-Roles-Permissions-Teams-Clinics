<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeamsPermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! empty($user = auth()->user()) && ! empty($user->current_team_id)) {
            setPermissionsTeamId($user->current_team_id);
        }

        return $next($request);
    }
}
