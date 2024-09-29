<?php


use App\Models\User;
use App\Models\Team;
use function Pest\Laravel\actingAs;

it('allows user to change team', function () {
    $user = User::factory()->superAdmin()->create();
    $otherUser = User::factory()->superAdmin()->create();

    $user->teams()->attach($otherUser->id);

    actingAs($user)
        ->get(route('team.change', $otherUser->current_team_id));

    expect($user->refresh()->current_team_id)->toBe($otherUser->current_team_id);
});

it('does not allow user to change team if user is not in the team', function () {
    $user = User::factory()->superAdmin()->create();
    $otherUser = User::factory()->superAdmin()->create();

    actingAs($user)
        ->get(route('team.change', $otherUser->current_team_id))
        ->assertNotFound();

    expect($user->refresh()->current_team_id)->toBe($user->current_team_id);
});

it('does not allow to change team for user without super admin role', function (User $user) {
    $team = Team::factory()->create();

    actingAs($user)
        ->get(route('team.change', $team->id))
        ->assertNotFound();

})->with([
    fn () => User::factory()->admin()->create(),
    fn () => User::factory()->patient()->create(),
]);
