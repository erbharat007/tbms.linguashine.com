{{--
    Shared user form fields.
    Expects: $user (User|null), $roles (id => name), $isEdit (bool)
--}}
@php($user = $user ?? null)

<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $user?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $user?->email)" required />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="password" :value="$isEdit ? __('New Password') : __('Password')" />
        <x-text-input id="password" name="password" type="password" :required="! $isEdit" autocomplete="new-password" />
        @if ($isEdit)
            <small class="form-text text-muted">Leave blank to keep the current password.</small>
        @endif
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="role_id" :value="__('Role')" />
        <x-select-input id="role_id" name="role_id">
            <option value="">— Select a role —</option>
            @foreach ($roles as $id => $name)
                <option value="{{ $id }}" @selected((int) old('role_id', $user?->role_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('role_id')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <input type="hidden" name="is_active" value="0">
        <div class="custom-control custom-checkbox mb-2">
            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                   @checked(old('is_active', $user?->is_active ?? true)) />
            <label class="custom-control-label" for="is_active">Active account</label>
        </div>
        <input type="hidden" name="is_manager" value="0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_manager" name="is_manager" value="1"
                   @checked(old('is_manager', $user?->is_manager ?? false)) />
            <label class="custom-control-label" for="is_manager">Is Manager</label>
        </div>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update User') : __('Create User') }}</button>
    <a href="{{ route('users.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
