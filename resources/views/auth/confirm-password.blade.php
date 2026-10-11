<x-guest-layout>
    <p class="login-box-msg">
        {{ __('This is a secure area. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-sm">{{ __('Confirm') }}</button>
    </form>
</x-guest-layout>
