<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" style="max-width: 680px;">
    @csrf
    @method('patch')

    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-9">
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-9">
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="small mt-2 mb-0">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">{{ __('Click here to re-send the verification email.') }}</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="text-success small font-weight-bold mt-2 mb-0">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-sm-9 offset-sm-3 d-flex align-items-center">
            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="text-muted small ml-3">{{ __('Saved.') }}</span>
            @endif
        </div>
    </div>
</form>
