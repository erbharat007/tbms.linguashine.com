<x-guest-layout>
    <p class="login-box-msg">
        {{ __('Thanks for signing up! Please verify your email by clicking the link we just emailed you. If you did not receive it, we will gladly send another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success py-2 px-3 small">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary btn-block btn-sm">{{ __('Resend Verification Email') }}</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3 text-center">
        @csrf
        <button type="submit" class="btn btn-link btn-sm text-muted">{{ __('Log Out') }}</button>
    </form>
</x-guest-layout>
