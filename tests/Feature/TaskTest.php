<?php


use App\Models\User;
use App\Models\Team;
use App\Models\Task;
use function Pest\Laravel\actingAs;

it('allows clinic admin and staff to access create task page', function (User $user) {
    actingAs($user)
        ->get(route('tasks.create'))
        ->assertOk();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow patient to access create task page', function () {
    $user =  User::factory()->patient()->create();

    actingAs($user)
        ->get(route('tasks.create'))
        ->assertForbidden();
});

it('allows clinic admin and staff to enter update page for any task in their team', function (User $user) {
    $team = Team::first();

    $clinicAdmin = User::factory()->clinicAdmin()->create();
    $clinicAdmin->update(['current_team_id' => $team->id]);
    setPermissionsTeamId($team->id);
    $clinicAdmin->unsetRelation('roles')->unsetRelation('permissions');

    $task = Task::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertOk();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow administrator and manager to enter update page for other teams task', function (User $user) {
    $team = Team::factory()->create();

    $taskUser = User::factory()->clinicAdmin()->create();
    $task = Task::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertNotFound();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('allows administrator and manager to update any task in their team', function (User $user) {
    $team = Team::first();

    $otherUser = User::factory()->clinicAdmin()->create();
    $otherUser->update(['current_team_id' => $team->id]);
    setPermissionsTeamId($team->id);
    $otherUser->unsetRelation('roles')->unsetRelation('permissions');

    $task = Task::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->put(route('tasks.update', $task), [
            'name' => 'updated task name',
        ])
        ->assertRedirect();

    expect($task->refresh()->name)->toBe('updated task name');
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('allows super admin and admin to delete task for his team', function (User $user) {
    $taskUser = User::factory()->create(['current_team_id' => $user->current_team_id]);

    $task = Task::factory()->create([
        'team_id' => $user->current_team_id,
    ]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect();

    expect(Task::count())->toBeInt()->toBe(0);
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow super admin and admin to delete task for other team', function (User $user) {
    $team = Team::factory()->create();

    $taskUser = User::factory()->clinicAdmin()->create();
    $taskUser->update(['current_team_id' => $team->id]);

    $task = Task::factory()->create([
        'team_id' => $taskUser->current_team_id,
    ]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertNotFound();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);
