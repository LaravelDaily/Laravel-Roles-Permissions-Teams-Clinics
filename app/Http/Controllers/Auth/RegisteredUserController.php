<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $teams = Team::where('name', '!=', 'Master Team')->pluck('name', 'id');

        return view('auth.register', compact('teams'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'team_id' => ['required', 'exists:teams,id'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'current_team_id' => $request->team_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        setPermissionsTeamId($request->team_id);

        /** @var $user User */
        $user->assignRole(Role::Patient);

        return redirect(route('dashboard', absolute: false));
    }
}
