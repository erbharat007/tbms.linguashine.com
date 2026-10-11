<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'TBMS') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/app-custom.css') }}?v={{ @filemtime(public_path('adminlte/dist/css/app-custom.css')) ?: time() }}">

    @stack('styles')
</head>
<body class="hold-transition" style="background-color: #e9ecef;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.webp') }}" alt="{{ config('app.name') }}" style="max-height: 48px;">
                </div>
                <div class="card">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
                <p class="text-center text-muted small mt-3 mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'TBMS') }}</p>
            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
