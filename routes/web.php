<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('teams', TeamController::class)->only(['index', 'create', 'store']);
    Route::post('teams/user', [TeamUserController::class, 'store'])->name('teams.user.store');
    Route::get('team/change/{teamId}', [TeamController::class, 'changeCurrentTeam'])->name('team.change');

    Route::resource('tasks', TaskController::class);
});

require __DIR__.'/auth.php';
