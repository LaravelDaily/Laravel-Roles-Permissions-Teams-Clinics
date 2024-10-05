<?php

use App\Models\User;
use App\Enums\Role as RoleEnum;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as RoleModel;
use function Pest\Laravel\actingAs;

it('allows clinic owner and admin to view users list', function () {
    $clinicOwner = User::factory()->clinicOwner()->create();
    $clinicAdmin = User::factory()->clinicAdmin()->create();

    $doctor = User::factory()->doctor()->create();
    $staff = User::factory()->staff()->create();

    $patient = User::factory()->patient()->create();

    actingAs($clinicOwner)
        ->get(route('users.index'))
        ->assertOk()
        ->assertViewHas('users', function (Collection $users) use ($clinicAdmin, $doctor, $staff, $patient): bool {
            return $users->contains(fn (User $user) => $user->name === $clinicAdmin->name
                || $user->name === $doctor->name
                || $user->name === $staff->name
            ) && $users->doesntContain(fn (User $user) => $user->name === $patient->name);
        });

    actingAs($clinicAdmin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertViewHas('users', function (Collection $users) use ($clinicAdmin, $doctor, $staff, $patient): bool {
            return $users->contains(fn (User $user) => $user->name === $clinicAdmin->name
                    || $user->name === $doctor->name
                    || $user->name === $staff->name
                ) && $users->doesntContain(fn (User $user) => $user->name === $patient->name);
        });
});

it('forbids users without access to enter users list page', function (User $user) {
    actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
})->with([
    fn() => User::factory()->masterAdmin()->create(),
    fn() => User::factory()->doctor()->create(),
    fn() => User::factory()->staff()->create(),
]);

it('forbids users without access to enter create user page', function (User $user) {
    actingAs($user)
        ->get(route('users.create'))
        ->assertForbidden();
})->with([
    fn() => User::factory()->masterAdmin()->create(),
    fn() => User::factory()->doctor()->create(),
    fn() => User::factory()->staff()->create(),
]);

it('allows clinic owner to create a new user and assign a role', function (RoleEnum $role) {
    $clinicOwner = User::factory()->clinicOwner()->create();

    actingAs($clinicOwner)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'password',
            'role_id' => RoleModel::where('name', $role->value)->first()->id,
        ]);

    $newUser = User::where('email', 'new@user.com')->first();

    expect($newUser->hasRole($role))->toBeTrue();
})->with([
    RoleEnum::ClinicAdmin,
    RoleEnum::Doctor,
    RoleEnum::Staff,
]);

it('allows clinic admin to create a new user and assign a role', function (RoleEnum $role) {
    $clinicAdmin = User::factory()->clinicAdmin()->create();

    actingAs($clinicAdmin)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'password',
            'role_id' => RoleModel::where('name', $role->value)->first()->id,
        ]);

    $newUser = User::where('email', 'new@user.com')->first();

    expect($newUser->hasRole($role))->toBeTrue();
})->with([
    RoleEnum::ClinicAdmin,
    RoleEnum::Doctor,
    RoleEnum::Staff,
]);
