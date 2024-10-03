<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf

                        <!-- Clinic Name -->
                        <div class="mt-4">
                            <x-input-label for="clinic_name" :value="__('Clinic Name')" />
                            <x-text-input id="clinic_name" class="mt-1 block w-full" type="text" name="clinic_name" :value="old('clinic_name')" required />
                            <x-input-error :messages="$errors->get('clinic_name')" class="mt-2" />
                        </div>

                        <h3 class="mt-4 text-lg font-semibold">Select User</h3>

                        <!-- Clinic Owner -->
                        <div class="mt-4">
                            <x-input-label for="user_id" :value="__('User')" />
                            <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- SELECT USER --</option>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <h3 class="mt-4 text-lg font-semibold">Or Create a new User</h3>

                        <!-- Name -->
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('User Name')" />
                            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('User Email')" />
                            <x-text-input id="email" class="mt-1 block w-full" type="text" name="email" :value="old('email')" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('User Password')" />
                            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" :value="old('password')" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <x-primary-button class="mt-4">
                            {{ __('Save') }}
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
