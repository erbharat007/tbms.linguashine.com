<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TBMS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/app-custom.css') }}?v={{ @filemtime(public_path('adminlte/dist/css/app-custom.css')) ?: time() }}">

    @stack('styles')
</head>
<body class="hold-transition auth-split-layout">
    <div class="auth-split-wrapper">
        <!-- Left Hero Column -->
        <div class="auth-hero-section">
            <div class="auth-hero-overlay"></div>
            <div class="auth-hero-content">
                <!-- Brand logo header -->
                <div class="auth-hero-brand mb-auto">
                    <a href="/" class="auth-brand-logo-wrap">
                        <span class="auth-brand-icon mr-2">
                            <i class="fas fa-cubes"></i>
                        </span>
                        <span class="auth-brand-title">
                            TBMS<span class="brand-accent">Linguashine</span>
                        </span>
                    </a>
                </div>

                <!-- Hero main headlines -->
                <div class="auth-hero-main my-auto">
                    <div class="auth-hero-badge">
                        Enterprise Portal
                    </div>
                    <h1 class="auth-hero-title">
                        Translation &amp; Business Management System
                    </h1>
                    <p class="auth-hero-desc">
                        Streamlining workflow operations, translation projects, and role-based management in one unified platform.
                    </p>

                    <!-- 3 Feature Glass Cards -->
                    <div class="auth-hero-features">
                        <div class="auth-feature-card">
                            <div class="auth-feature-icon icon-cyan">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h6 class="auth-feature-title">Role-Based Access</h6>
                            <p class="auth-feature-desc">Custom privileges for every user type.</p>
                        </div>
                        <div class="auth-feature-card">
                            <div class="auth-feature-icon icon-emerald">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h6 class="auth-feature-title">Real-Time Analytics</h6>
                            <p class="auth-feature-desc">Live performance tracking &amp; metrics.</p>
                        </div>
                        <div class="auth-feature-card">
                            <div class="auth-feature-icon icon-amber">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h6 class="auth-feature-title">Task Management</h6>
                            <p class="auth-feature-desc">Automated workflow distribution.</p>
                        </div>
                    </div>
                </div>

                <!-- Hero Footer -->
                <div class="auth-hero-footer mt-auto pt-4">
                    <span>&copy; {{ date('Y') }} Linguashine. All rights reserved.</span>
                    <span class="mx-2">|</span>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
        </div>

        <!-- Right Form Column -->
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-form-card">
                    <div class="text-center mb-4">
                        <a href="/" class="d-inline-block">
                            <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name', 'TBMS Linguashine') }}" class="auth-form-logo">
                        </a>
                    </div>

                    <div class="auth-form-box">
                        {{ $slot }}
                    </div>

                    <div class="auth-trust-badge text-center mt-4 pt-3 border-top">
                        <span>
                            <i class="fas fa-shield-alt text-success mr-1"></i> Secure 256-Bit SSL Encrypted Connection
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
