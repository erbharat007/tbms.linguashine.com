<x-guest-layout>
    <p class="login-box-msg">
        {{ __('Forgot your password? Enter your email and we will send you a password reset link.') }}
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="form-control @error('email') is-invalid @enderror" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-sm">{{ __('Email Password Reset Link') }}</button>
    </form>

    <p class="mb-0 mt-3">
        <a href="{{ route('login') }}" class="text-muted small">Back to login</a>
    </p>
</x-guest-layout>
