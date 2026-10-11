{{--
    Shared role form.
    Expects: $role (Role), $permissionGroups (Collection grouped by group),
             $selected (array of permission ids), $isEdit (bool)
--}}

<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="name" :value="__('Role Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $role->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="description" :value="__('Description')" />
        <x-text-input id="description" name="description" type="text" :value="old('description', $role->description)" />
        <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
</div>

<div class="form-group">
    <x-input-label :value="__('Screen Access')" />
    <p class="text-muted small mb-2">Choose which screens this role can access.</p>

    @foreach ($permissionGroups as $group => $permissions)
        <div class="card mb-3 perm-group">
            <div class="card-header py-2">
                <span class="text-uppercase text-muted small font-weight-bold">{{ $group }}</span>
            </div>
            <div class="card-body py-2">
                <div class="custom-control custom-checkbox mb-2 pb-2 border-bottom">
                    <input type="checkbox" class="custom-control-input perm-select-all" id="select_all_{{ $loop->index }}">
                    <label class="custom-control-label small font-weight-bold" for="select_all_{{ $loop->index }}">Select all</label>
                </div>
                <div class="perm-cols">
                    @foreach ($permissions as $permission)
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input perm" id="perm_{{ $permission->id }}"
                                   name="permissions[]" value="{{ $permission->id }}"
                                   @checked(in_array($permission->id, old('permissions', $selected)))>
                            <label class="custom-control-label" for="perm_{{ $permission->id }}">{{ $permission->label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
    <x-input-error :messages="$errors->get('permissions')" class="mt-1" />
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update Role') : __('Create Role') }}</button>
    <a href="{{ route('roles.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>

@push('scripts')
<script>
    $(function () {
        // Per-group "Select all" toggles the group's permission checkboxes.
        $('.perm-select-all').on('change', function () {
            $(this).closest('.perm-group').find('.perm').prop('checked', this.checked);
        });
        // Reflect initial state: tick "Select all" if every perm in the group is checked.
        $('.perm-group').each(function () {
            var $perms = $(this).find('.perm');
            if ($perms.length && $perms.filter(':checked').length === $perms.length) {
                $(this).find('.perm-select-all').prop('checked', true);
            }
        });
        // Keep "Select all" in sync when individual boxes change.
        $('.perm').on('change', function () {
            var $group = $(this).closest('.perm-group');
            var $perms = $group.find('.perm');
            $group.find('.perm-select-all').prop('checked', $perms.filter(':checked').length === $perms.length);
        });
    });
</script>
@endpush
