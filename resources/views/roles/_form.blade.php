{{--
    Shared role form.
    Expects: $role (Role), $permissionGroups (Collection grouped by group),
             $selected (array of permission ids), $isEdit (bool)
--}}

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="name" :value="__('Role Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5"
                          :value="old('name', $role->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" name="description" type="text" class="mt-1.5"
                          :value="old('description', $role->description)" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label :value="__('Screen Access')" />
        <p class="mt-1 text-sm text-gray-500">Choose which screens this role can access.</p>

        <div class="mt-3 space-y-4">
            @foreach ($permissionGroups as $group => $permissions)
                <div x-data="{ toggleAll(e) { this.$root.querySelectorAll('input[type=checkbox].perm').forEach(c => c.checked = e.target.checked) } }"
                     class="rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-4 py-2.5">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $group }}</span>
                        <label class="flex items-center gap-2 text-xs text-gray-500">
                            <input type="checkbox" @change="toggleAll($event)"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Select all
                        </label>
                    </div>
                    <div class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       class="perm rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                       @checked(in_array($permission->id, old('permissions', $selected)))>
                                <span class="text-sm text-gray-700">{{ $permission->label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update Role') : __('Create Role') }}</x-primary-button>
        <a href="{{ route('roles.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
