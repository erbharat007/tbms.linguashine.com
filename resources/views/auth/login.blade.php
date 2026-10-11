<x-guest-layout>
    <p class="login-box-msg">Sign in to start your session</p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if (session('error'))
        <div class="alert alert-danger py-2 px-3 small">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="form-control @error('password') is-invalid @enderror" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>

        <div class="row align-items-center">
            <div class="col-7">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                    <label class="custom-control-label" for="remember">Remember me</label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block btn-sm">Log in</button>
            </div>
        </div>
    </form>

    @if (Route::has('password.request'))
        <p class="mb-0 mt-3">
            <a href="{{ route('password.request') }}" class="text-muted small">Forgot your password?</a>
        </p>
    @endif
</x-guest-layout>
