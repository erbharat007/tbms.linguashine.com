<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="auth-form-card-title">Sign In</h2>
        <p class="auth-form-card-subtitle">Enter your credentials to access your session</p>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if (session('error'))
        <div class="alert alert-danger py-2 px-3 small border-0 shadow-sm rounded-lg mb-3">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-custom-form">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="form-control @error('email') is-invalid @enderror" placeholder="name@linguashine.com">
            </div>
            @error('email')<span class="invalid-feedback d-block small mt-1">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-primary font-weight-500 text-decoration-none">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="form-control has-toggle @error('password') is-invalid @enderror" placeholder="••••••••">
                <div class="input-group-append">
                    <button type="button" class="btn btn-password-toggle" id="togglePasswordBtn" title="Toggle password visibility">
                        <i class="far fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>
            @error('password')<span class="invalid-feedback d-block small mt-1">{{ $message }}</span>@enderror
        </div>

        <div class="form-group d-flex align-items-center justify-content-between mt-3 mb-4">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                <label class="custom-control-label small text-secondary" for="remember">Remember me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block auth-submit-btn">
            <span>Log in</span>
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </form>

    @push('scripts')
    <script>
        $(function () {
            $('#togglePasswordBtn').on('click', function () {
                var input = $('#password');
                var icon = $('#togglePasswordIcon');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
    @endpush
</x-guest-layout>

