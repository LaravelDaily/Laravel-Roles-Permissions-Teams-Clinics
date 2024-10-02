<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::with('assignee', 'patient')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        Gate::authorize('create', Task::class);

        $assignees = User::whereRelation('roles', 'name', '=', Role::Doctor->value)
            ->orWhereRelation('roles', 'name', '=', Role::Staff->value)
            ->pluck('name', 'id');

        $patients = User::whereRelation('roles', 'name', '=', Role::Patient->value)->pluck('name', 'id');

        return view('tasks.create', compact('patients', 'assignees'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        Task::create($request->only('name', 'due_date', 'assigned_to_user_id', 'patient_id'));

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update', $task);

        $assignees = User::whereRelation('roles', 'name', '=', Role::Doctor->value)
            ->orWhereRelation('roles', 'name', '=', Role::Staff->value)
            ->pluck('name', 'id');

        $patients = User::whereRelation('roles', 'name', '=', Role::Patient->value)->pluck('name', 'id');

        return view('tasks.edit', compact('task', 'assignees', 'patients'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $task->update($request->only('name', 'due_date', 'assigned_to_user_id', 'patient_id'));

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index');
    }
}
