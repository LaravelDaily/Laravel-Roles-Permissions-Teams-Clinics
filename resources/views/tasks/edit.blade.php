<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$task->name" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Due Date -->
                        <div class="mt-4">
                            <x-input-label for="due_date" :value="__('Due date')" />
                            <x-text-input id="due_date" class="block mt-1 w-full" type="text" name="due_date" :value="$task->due_date" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <!-- Assignee -->
                        <div class="mt-4">
                            <x-input-label for="assigned_to_user_id" :value="__('Assignee')" />
                            <select name="assigned_to_user_id" id="assigned_to_user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option>-- SELECT ASSIGNEE --</option>
                                @foreach($assignees as $id => $name)
                                    <option value="{{ $id }}" @selected(old('assigned_to_user_id', $task->assigned_to_user_id) === $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assigned_to_user_id')" class="mt-2" />
                        </div>

                        <!-- Patients -->
                        <div class="mt-4">
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option>-- SELECT PATIENT --</option>
                                @foreach($patients as $id => $name)
                                    <option value="{{ $id }}" @selected(old('patient_id', $task->patient_id) === $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-primary-button>
                                {{ __('Save Task') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
