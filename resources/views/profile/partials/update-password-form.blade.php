<form method="post" action="{{ route('password.update') }}" style="max-width: 680px;">
    @csrf
    @method('put')

    <div class="form-group row">
        <label for="update_password_current_password" class="col-sm-3 col-form-label">Current Password</label>
        <div class="col-sm-9">
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <label for="update_password_password" class="col-sm-3 col-form-label">New Password</label>
        <div class="col-sm-9">
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <label for="update_password_password_confirmation" class="col-sm-3 col-form-label">Confirm Password</label>
        <div class="col-sm-9">
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-sm-9 offset-sm-3 d-flex align-items-center">
            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
            @if (session('status') === 'password-updated')
                <span class="text-muted small ml-3">{{ __('Saved.') }}</span>
            @endif
        </div>
    </div>
</form>
