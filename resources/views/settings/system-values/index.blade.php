<x-app-layout>
    <x-slot name="header">
        <x-page-header title="System values" subtitle="Manage linguistic reference data, units, currencies, and services.">
        </x-page-header>
    </x-slot>

    <div class="row">
        <!-- Language Pairs -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-outline card-info h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-language text-info mr-2"></i> Language pairs
                    </h3>
                    <span class="badge badge-info">{{ $stats['language_pairs']['total'] }} Total</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Source and target language combinations for translation and localization workflows.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm font-weight-bold">Active Pairs:</span>
                        <span class="badge badge-success">{{ $stats['language_pairs']['active'] }} active</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('language-pairs.create') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-plus mr-1"></i> New pair
                    </a>
                    <a href="{{ route('language-pairs.index') }}" class="btn btn-info btn-sm">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-outline card-success h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-cogs text-success mr-2"></i> Services
                    </h3>
                    <span class="badge badge-success">{{ $stats['services']['total'] }} Total</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Translation, editing, proofreading, DTP, transcription, and other deliverables.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm font-weight-bold">Active Services:</span>
                        <span class="badge badge-success">{{ $stats['services']['active'] }} active</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('services.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> New service
                    </a>
                    <a href="{{ route('services.index') }}" class="btn btn-success btn-sm">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Specializations -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-outline card-warning h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-certificate text-warning mr-2"></i> Specializations
                    </h3>
                    <span class="badge badge-warning">{{ $stats['specializations']['total'] }} Total</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Domain subject matters (Medical, Legal, Technical, Marketing, Financial, etc.).
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm font-weight-bold">Active Specializations:</span>
                        <span class="badge badge-success">{{ $stats['specializations']['active'] }} active</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('specializations.create') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-plus mr-1"></i> New specialization
                    </a>
                    <a href="{{ route('specializations.index') }}" class="btn btn-warning btn-sm text-dark">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Units -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-outline card-primary h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-ruler-combined text-primary mr-2"></i> Units
                    </h3>
                    <span class="badge badge-primary">{{ $stats['units']['total'] }} Total</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Measurement units (Source words, Target words, Hours, Pages, Flat fee, Minutes).
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm font-weight-bold">Active Units:</span>
                        <span class="badge badge-success">{{ $stats['units']['active'] }} active</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('units.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New unit
                    </a>
                    <a href="{{ route('units.index') }}" class="btn btn-primary btn-sm">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Currencies -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-outline card-secondary h-100 shadow-sm" style="border-top-color: #20c997;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-money-bill-wave text-teal mr-2"></i> Currencies
                    </h3>
                    <span class="badge badge-info">{{ $stats['currencies']['total'] }} Total</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Currencies, exchange rates, and base system currency for financial accounting.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm font-weight-bold">System Currency:</span>
                        <span class="badge badge-success font-weight-bold">{{ $stats['currencies']['system'] }}</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('currencies.create') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New currency
                    </a>
                    <a href="{{ route('currencies.index') }}" class="btn btn-secondary btn-sm">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Languages -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-outline card-secondary h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-globe text-secondary mr-2"></i> Languages
                    </h3>
                    <span class="badge badge-secondary">{{ $stats['languages']['total'] }} Total</span>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        World ISO languages and regional locales supported by the agency.
                    </p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-sm font-weight-bold">Available Languages:</span>
                        <span class="badge badge-secondary">{{ $stats['languages']['total'] }} registered</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('languages.create') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New language
                    </a>
                    <a href="{{ route('languages.index') }}" class="btn btn-secondary btn-sm">
                        Manage <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
