<?php


use App\Models\User;
use App\Models\Team;
use App\Models\Task;
use function Pest\Laravel\actingAs;

it('allows super admin and admin to access create task page', function (User $user) {
    actingAs($user)
        ->get(route('tasks.create'))
        ->assertOk();
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow user to access create task page', function () {
    $user =  User::factory()->patient()->create();

    actingAs($user)
        ->get(route('tasks.create'))
        ->assertForbidden();
});

it('allows super admin and admin to enter update page for any task in their team', function (User $user) {
    $team = Team::first();

    $otherUser = User::factory()->admin()->create();
    $otherUser->teams()->sync($team);
    $otherUser->update(['current_team_id' => $team->id]);
    setPermissionsTeamId($team->id);
    $otherUser->unsetRelation('roles')->unsetRelation('permissions');

    $task = Task::factory()->create([
        'user_id' => $otherUser->id,
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertOk();
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->doctor()->create(),
]);

it('does not allow administrator and manager to enter update page for other teams task', function (User $user) {
    $team = Team::factory()->create();

    $taskUser = User::factory()->admin()->create();
    $taskUser->teams()->sync($team);
    $task = Task::factory()->create([
        'user_id' => $taskUser->id,
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertNotFound();
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->doctor()->create(),
]);

it('allows administrator and manager to update any task in their team', function (User $user) {
    $team = Team::first();

    $otherUser = User::factory()->admin()->create();
    $otherUser->teams()->sync($team);
    $otherUser->update(['current_team_id' => $team->id]);
    setPermissionsTeamId($team->id);
    $otherUser->unsetRelation('roles')->unsetRelation('permissions');

    $task = Task::factory()->create([
        'user_id' => $otherUser->id,
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->put(route('tasks.update', $task), [
            'name' => 'updated task name',
        ])
        ->assertRedirect();

    expect($task->refresh()->name)->toBe('updated task name');
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->doctor()->create(),
]);

it('allows user to update his own task', function () {
    $user = User::factory()->patient()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'team_id' => $user->current_team_id,
    ]);

    actingAs($user)
        ->put(route('tasks.update', $task), [
            'name' => 'updated task name',
        ]);

    expect($task->refresh()->name)->toBe('updated task name');
});

it('does not allow user to update other users task on the same team', function () {
    $user = User::factory()->patient()->create();
    $task = Task::factory()->create([
        'user_id' => User::factory()->create(['current_team_id' => $user->id])->id,
        'team_id' => $user->current_team_id,
    ]);

    actingAs($user)
        ->put(route('tasks.update', $task), [
            'name' => 'updated task name',
        ])
        ->assertForbidden();
});

it('allows super admin and admin to delete task for his team', function (User $user) {
    $taskUser = User::factory()->create(['current_team_id' => $user->current_team_id]);

    $task = Task::factory()->create([
        'user_id' => $taskUser->id,
        'team_id' => $user->current_team_id,
    ]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect();

    expect(Task::count())->toBeInt()->toBe(0);
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow super admin and admin to delete task for other team', function (User $user) {
    $team = Team::factory()->create();

    $taskUser = User::factory()->admin()->create();
    $taskUser->teams()->sync($team);
    $taskUser->update(['current_team_id' => $team->id]);

    $task = Task::factory()->create([
        'user_id' => $taskUser->id,
        'team_id' => $taskUser->current_team_id,
    ]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertNotFound();
})->with([
    fn () => User::factory()->superAdmin()->create(),
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->staff()->create(),
]);
