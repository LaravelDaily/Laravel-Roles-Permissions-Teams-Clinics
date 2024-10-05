<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-hidden overflow-x-auto bg-white">
                        <div class="min-w-full align-middle">
                            @can(\App\Enums\Permission::CREATE_TASK)
                            <a href="{{ route('tasks.create') }}" class="underline">Add new task</a>
                            <br /><br />
                            @endcan
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Assigned To</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Patient</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left"></th>
                                </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach($tasks as $task)
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $task->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $task->assignee->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $task->patient->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            @can(\App\Enums\Permission::EDIT_TASK)
                                                <a href="{{ route('tasks.edit', $task) }}" class="underline">Edit</a>
                                            @endcan
                                            @can(\App\Enums\Permission::DELETE_TASK)
                                                |
                                                <form action="{{ route('tasks.destroy', $task) }}"
                                                      method="POST"
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure?')">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="underline text-red-600">Delete</button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
