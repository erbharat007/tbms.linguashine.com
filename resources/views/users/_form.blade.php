{{--
    Shared user form fields.
    Expects: $user (User|null), $roles (id => name), $isEdit (bool)
--}}
@php($user = $user ?? null)

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5"
                          :value="old('name', $user?->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5"
                          :value="old('email', $user?->email)" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="role_id" :value="__('Role')" />
            <x-select-input id="role_id" name="role_id" class="mt-1.5">
                <option value="">— Select a role —</option>
                @foreach ($roles as $id => $name)
                    <option value="{{ $id }}" @selected((int) old('role_id', $user?->role_id) === (int) $id)>{{ $name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <div class="flex flex-col justify-end gap-2.5 pb-1">
            <label class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       @checked(old('is_active', $user?->is_active ?? true)) />
                <span class="text-sm text-gray-700">Active account</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="is_manager" value="0">
                <input type="checkbox" name="is_manager" value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       @checked(old('is_manager', $user?->is_manager ?? false)) />
                <span class="text-sm text-gray-700">Is Manager</span>
            </label>
        </div>
    </div>

    <hr class="border-gray-100">

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="password" :value="$isEdit ? __('New Password') : __('Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1.5"
                          :required="! $isEdit" autocomplete="new-password" />
            @if ($isEdit)
                <p class="mt-1 text-xs text-gray-400">Leave blank to keep the current password.</p>
            @endif
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                          class="mt-1.5" autocomplete="new-password" />
        </div>
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update User') : __('Create User') }}</x-primary-button>
        <a href="{{ route('users.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
