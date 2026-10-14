<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="auth-form-card-title">Reset Password</h2>
        <p class="auth-form-card-subtitle">Enter your email and we will send you a password reset link.</p>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="auth-custom-form">
        @csrf

        <div class="form-group mb-4">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-control @error('email') is-invalid @enderror" placeholder="name@linguashine.com">
            </div>
            @error('email')<span class="invalid-feedback d-block small mt-1">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block auth-submit-btn mb-3">
            <span>{{ __('Email Password Reset Link') }}</span>
            <i class="fas fa-paper-plane ml-2"></i>
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small text-primary font-weight-500 text-decoration-none">
                <i class="fas fa-arrow-left mr-1"></i> Back to login
            </a>
        </div>
    </form>
</x-guest-layout>

