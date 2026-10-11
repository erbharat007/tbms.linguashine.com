<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TBMS') }}</title>

    <!-- Google Font: Source Sans Pro (AdminLTE default) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE theme (Bootstrap 4 based) -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- App tweaks -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/app-custom.css') }}?v={{ @filemtime(public_path('adminlte/dist/css/app-custom.css')) ?: time() }}">

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Top navbar -->
    @include('layouts.topbar')

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        {{-- Content header (page heading) --}}
        @isset($header)
            <div class="content-header">
                <div class="container-fluid">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @include('layouts.partials.flash')
                {{ $slot }}
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} {{ config('app.name', 'TBMS') }}.</strong>
        <div class="float-right d-none d-sm-inline-block">Translation Business Management System</div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>
