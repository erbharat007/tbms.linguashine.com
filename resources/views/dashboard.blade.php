<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.5rem;">
                    Dashboard
                    <span class="badge {{ $roleBadgeClass }} ml-2 px-2 py-1 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        <i class="{{ $roleIcon }} mr-1"></i> {{ $roleLabel }}
                    </span>
                </h1>
                <p class="text-muted small mb-0 mt-1">
                    @if ($dashboardType === 'admin')
                        Executive overview of enterprise localization, portfolio profitability, and operations.
                    @elseif ($dashboardType === 'pm')
                        Project delivery schedule, linguist allocations, and upcoming milestone deadlines.
                    @elseif ($dashboardType === 'accountant')
                        Invoicing ledger, pending payables, supplier settlements, and cash balance.
                    @else
                        Welcome to your translation business management workspace.
                    @endif
                    <span class="ml-2 text-secondary"><i class="far fa-clock mr-1"></i> {{ now()->format('l, d M Y') }}</span>
                </p>
            </div>
            <div class="mt-2 mt-md-0 d-flex flex-wrap align-items-center">
                @if ($dashboardType === 'admin')
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm mr-2 shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> New project
                    </a>
                    <a href="{{ route('clients.create') }}" class="btn btn-outline-info btn-sm mr-2 shadow-sm bg-white">
                        <i class="fas fa-user-plus mr-1"></i> New client
                    </a>
                    <a href="{{ route('vendors.create') }}" class="btn btn-outline-success btn-sm mr-2 shadow-sm bg-white">
                        <i class="fas fa-truck-loading mr-1"></i> New vendor
                    </a>
                    <a href="{{ route('reports.general-by-receivables-and-payables') }}" class="btn btn-outline-secondary btn-sm shadow-sm bg-white">
                        <i class="fas fa-chart-pie mr-1"></i> Finances
                    </a>
                @elseif ($dashboardType === 'pm')
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm mr-2 shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> New project
                    </a>
                    <a href="{{ route('project-templates.index') }}" class="btn btn-outline-info btn-sm mr-2 shadow-sm bg-white">
                        <i class="fas fa-copy mr-1"></i> Templates
                    </a>
                    <a href="{{ route('project-tags.index') }}" class="btn btn-outline-secondary btn-sm mr-2 shadow-sm bg-white">
                        <i class="fas fa-tags mr-1"></i> Tags
                    </a>
                    <a href="{{ route('vendors.index') }}" class="btn btn-outline-success btn-sm shadow-sm bg-white">
                        <i class="fas fa-users mr-1"></i> Linguists
                    </a>
                @elseif ($dashboardType === 'accountant')
                    <a href="{{ route('vendor-payables.index') }}" class="btn btn-warning btn-sm mr-2 text-dark font-weight-bold shadow-sm">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Payables
                    </a>
                    <a href="{{ route('vendor-invoices.index') }}" class="btn btn-outline-info btn-sm mr-2 shadow-sm bg-white">
                        <i class="fas fa-file-invoice mr-1"></i> Invoices
                    </a>
                    <a href="{{ route('vendor-payments.index') }}" class="btn btn-outline-success btn-sm mr-2 shadow-sm bg-white">
                        <i class="fas fa-money-check-alt mr-1"></i> Payments
                    </a>
                    <a href="{{ route('reports.general-by-invoices') }}" class="btn btn-outline-secondary btn-sm shadow-sm bg-white">
                        <i class="fas fa-chart-line mr-1"></i> Report
                    </a>
                @else
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm mr-2 shadow-sm">
                        <i class="fas fa-project-diagram mr-1"></i> View Projects
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm shadow-sm bg-white">
                        <i class="fas fa-user-cog mr-1"></i> Profile
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    {{-- ============================================================= --}}
    {{-- 1. ROLE-SPECIFIC TOP 4 KPI CARDS                              --}}
    {{-- ============================================================= --}}
    <div class="row">
        @if ($dashboardType === 'admin')
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-info shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">${{ number_format($totalReceivables, 0) }} <small class="text-white-50" style="font-size: 0.9rem;">USD</small></h3>
                        <p class="font-weight-bold mb-1">Total Receivables</p>
                        <small class="d-block text-white-50">Across {{ $projectCount }} client project orders</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <a href="{{ route('projects.index') }}" class="small-box-footer py-1">
                        View project receivables <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-warning shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1 text-dark">${{ number_format($totalPayables, 0) }} <small class="text-dark-50" style="font-size: 0.9rem;">USD</small></h3>
                        <p class="font-weight-bold mb-1 text-dark">Vendor Liabilities</p>
                        <small class="d-block text-dark-50">{{ $payableCount }} supplier payables accrued</small>
                    </div>
                    <div class="icon" style="color: rgba(0,0,0,0.15);">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <a href="{{ route('vendor-payables.index') }}" class="small-box-footer text-dark py-1">
                        Audit vendor liabilities <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-success shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">${{ number_format($totalProfit, 0) }} <small class="text-white-50" style="font-size: 0.85rem;">({{ $avgProfitMargin }}%)</small></h3>
                        <p class="font-weight-bold mb-1">Net Portfolio Profit</p>
                        <small class="d-block text-white-50">Overall margin across completed projects</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="{{ route('reports.project') }}" class="small-box-footer py-1">
                        Production profit report <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-primary shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $inProgressProjectsCount }} <small class="text-white-50" style="font-size: 0.9rem;">/ {{ $projectCount }}</small></h3>
                        <p class="font-weight-bold mb-1">Projects In Production</p>
                        <small class="d-block text-white-50">{{ $completedProjectsCount }} delivered &bull; {{ $draftProjectsCount }} draft</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <a href="{{ route('projects.index', ['status' => 'in_progress']) }}" class="small-box-footer py-1">
                        Manage active pipeline <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

        @elseif ($dashboardType === 'pm')
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-primary shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $myActiveProjectsCount }} <small class="text-white-50" style="font-size: 0.9rem;">/ {{ $myProjectsCount }}</small></h3>
                        <p class="font-weight-bold mb-1">My Managed Projects</p>
                        <small class="d-block text-white-50">Assigned directly to your production queue</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <a href="{{ route('projects.index', ['manager_id' => $user->id]) }}" class="small-box-footer py-1">
                        Filter my projects <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-info shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $inProgressProjectsCount }} <small class="text-white-50" style="font-size: 0.9rem;">Active</small></h3>
                        <p class="font-weight-bold mb-1">Total Company Pipeline</p>
                        <small class="d-block text-white-50">Ongoing localization projects in system</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <a href="{{ route('projects.index') }}" class="small-box-footer py-1">
                        View complete portfolio <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-danger shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $approachingDeadlinesCount }}</h3>
                        <p class="font-weight-bold mb-1">Approaching Deadlines</p>
                        <small class="d-block text-white-50">Due for client delivery within 7 days</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <a href="{{ route('projects.index', ['status' => 'in_progress', 'sort' => 'deadline_at']) }}" class="small-box-footer py-1">
                        Check milestone deadlines <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-success shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $assignableVendorCount }} <small class="text-white-50" style="font-size: 0.9rem;">Linguists</small></h3>
                        <p class="font-weight-bold mb-1">Available Resource Pool</p>
                        <small class="d-block text-white-50">Verified translators ready for assignment</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <a href="{{ route('vendors.index') }}" class="small-box-footer py-1">
                        Search translator directory <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

        @elseif ($dashboardType === 'accountant')
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-warning shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1 text-dark">${{ number_format($payableTotal, 0) }} <small class="text-dark-50" style="font-size: 0.9rem;">EUR</small></h3>
                        <p class="font-weight-bold mb-1 text-dark">Accrued Payables Due</p>
                        <small class="d-block text-dark-50">{{ $payableCount }} linguist job purchase orders</small>
                    </div>
                    <div class="icon" style="color: rgba(0,0,0,0.15);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('vendor-payables.index') }}" class="small-box-footer text-dark py-1">
                        View payables ledger <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-danger shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">${{ number_format($unpaidInvoicesTotal, 0) }} <small class="text-white-50" style="font-size: 0.9rem;">EUR</small></h3>
                        <p class="font-weight-bold mb-1">Unpaid Supplier Invoices</p>
                        <small class="d-block text-white-50">{{ $unpaidInvoicesCount }} supplier invoices pending payment</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <a href="{{ route('vendor-invoices.index') }}" class="small-box-footer py-1">
                        Process pending invoices <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-success shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">${{ number_format($paymentTotal, 0) }} <small class="text-white-50" style="font-size: 0.9rem;">EUR</small></h3>
                        <p class="font-weight-bold mb-1">Cleared Disbursements</p>
                        <small class="d-block text-white-50">Total outgoing payments completed</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('vendor-payments.index') }}" class="small-box-footer py-1">
                        Disbursement records <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-primary shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $clientCount }} <small class="text-white-50" style="font-size: 0.9rem;">Clients</small></h3>
                        <p class="font-weight-bold mb-1">Corporate Accounts</p>
                        <small class="d-block text-white-50">Active contract rates and billing terms</small>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('clients.index') }}" class="small-box-footer py-1">
                        Client pricing directory <i class="fas fa-arrow-circle-right ml-1"></i>
                    </a>
                </div>
            </div>

        @else
            {{-- General / Staff --}}
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-primary shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $projectCount }}</h3>
                        <p class="font-weight-bold mb-1">Company Projects</p>
                        <small class="d-block text-white-50">{{ $inProgressProjectsCount }} currently in progress</small>
                    </div>
                    <div class="icon"><i class="fas fa-project-diagram"></i></div>
                    <a href="{{ route('projects.index') }}" class="small-box-footer py-1">View projects <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-info shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $clientCount }}</h3>
                        <p class="font-weight-bold mb-1">Corporate Clients</p>
                        <small class="d-block text-white-50">Active customer accounts</small>
                    </div>
                    <div class="icon"><i class="fas fa-user-tie"></i></div>
                    <a href="{{ route('clients.index') }}" class="small-box-footer py-1">Browse clients <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-success shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $vendorCount }}</h3>
                        <p class="font-weight-bold mb-1">Linguist Network</p>
                        <small class="d-block text-white-50">Qualified translation vendors</small>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <a href="{{ route('vendors.index') }}" class="small-box-footer py-1">Linguist pool <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div class="small-box bg-gradient-secondary shadow-sm">
                    <div class="inner">
                        <h3 class="mb-1">{{ $completedProjectsCount }}</h3>
                        <p class="font-weight-bold mb-1">Delivered Projects</p>
                        <small class="d-block text-white-50">Successfully closed milestones</small>
                    </div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                    <a href="{{ route('projects.index', ['status' => 'completed']) }}" class="small-box-footer py-1">Archived orders <i class="fas fa-arrow-circle-right ml-1"></i></a>
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================================= --}}
    {{-- 2. MAIN 2-COLUMN OPERATIONAL DASHBOARD                        --}}
    {{-- ============================================================= --}}
    <div class="row">
        {{-- LEFT COLUMN: Primary Operational Ledger (8 Cols) --}}
        <div class="col-lg-8">
            @if ($dashboardType === 'admin')
                {{-- Admin: Active Project Portfolio & Margin Ledger --}}
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-stream text-primary mr-2"></i> Recent Projects Portfolio & Margin Ledger
                            </h3>
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary">
                                View all ({{ $projectCount }}) <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th style="width: 80px;">Code</th>
                                        <th>Project Name</th>
                                        <th>Client</th>
                                        <th>Deadline</th>
                                        <th>Status</th>
                                        <th>Manager</th>
                                        <th class="text-right">Profit Margin</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentProjects as $project)
                                        <tr>
                                            <td class="font-weight-bold text-primary">
                                                <a href="{{ route('projects.show', $project) }}" class="text-primary font-weight-bold">
                                                    {{ $project->code }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.show', $project) }}" class="text-dark font-weight-600">
                                                    {{ Str::limit($project->name, 28) }}
                                                </a>
                                                @if ($project->tags->isNotEmpty())
                                                    <div class="mt-1">
                                                        @foreach ($project->tags->take(2) as $tag)
                                                            <span class="badge badge-{{ $tag->color }} px-1 text-white" style="font-size: 0.65rem;">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ Str::limit($project->client->name ?? '—', 18) }}</span>
                                            </td>
                                            <td>
                                                @if ($project->deadline_at)
                                                    <span class="small {{ $project->deadline_at->isPast() && $project->status !== 'completed' ? 'text-danger font-weight-bold' : 'text-secondary' }}">
                                                        {{ $project->deadline_at->format('d M, H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $project->status_badge_class }} px-2 py-1">
                                                    {{ $project->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="small text-muted">{{ Str::limit($project->primaryManager->name ?? '—', 14) }}</span>
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                @if ((float) $project->profit_margin >= 35)
                                                    <span class="text-success"><i class="fas fa-arrow-up mr-1"></i>{{ number_format($project->profit_margin, 1) }}%</span>
                                                @elseif ((float) $project->profit_margin >= 20)
                                                    <span class="text-info">{{ number_format($project->profit_margin, 1) }}%</span>
                                                @else
                                                    <span class="text-warning">{{ number_format($project->profit_margin, 1) }}%</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-xs btn-outline-secondary" title="View details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                No projects registered yet. <a href="{{ route('projects.create') }}">Create first project</a>.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Admin: Bottom Quick Metrics Grid --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box shadow-sm border bg-white mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">Active Clients</span>
                                <span class="info-box-number font-weight-bold">{{ $clientCount }}</span>
                                <div class="progress" style="height: 3px;"><div class="progress-bar bg-info" style="width: 100%"></div></div>
                                <span class="progress-description small text-muted">Corporate portfolio</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box shadow-sm border bg-white mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">Active Linguists</span>
                                <span class="info-box-number font-weight-bold">{{ $assignableVendorCount }}</span>
                                <div class="progress" style="height: 3px;"><div class="progress-bar bg-success" style="width: 100%"></div></div>
                                <span class="progress-description small text-muted">Assignable for jobs</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box shadow-sm border bg-white mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-clock text-white"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted">Recruitment</span>
                                <span class="info-box-number font-weight-bold">{{ $pendingApplicationsCount }}</span>
                                <div class="progress" style="height: 3px;"><div class="progress-bar bg-warning" style="width: 100%"></div></div>
                                <span class="progress-description small text-muted">Pending applications</span>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif ($dashboardType === 'pm')
                {{-- PM: My Production Schedule & Deadlines --}}
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-calendar-check text-primary mr-2"></i> Production Milestones & Deliverables Schedule
                            </h3>
                            <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus mr-1"></i> New project
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th style="width: 80px;">Code</th>
                                        <th>Project Name</th>
                                        <th>Client</th>
                                        <th>Delivery Deadline</th>
                                        <th>Status</th>
                                        <th>Countdown</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pmProjects as $project)
                                        @php
                                            $isOverdue = $project->deadline_at && $project->deadline_at->isPast() && $project->status !== 'completed';
                                            $daysLeft = $project->deadline_at ? now()->diffInDays($project->deadline_at, false) : null;
                                        @endphp
                                        <tr>
                                            <td class="font-weight-bold text-primary">
                                                <a href="{{ route('projects.show', $project) }}" class="text-primary font-weight-bold">
                                                    {{ $project->code }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('projects.show', $project) }}" class="text-dark font-weight-600">
                                                    {{ Str::limit($project->name, 30) }}
                                                </a>
                                                @if ($project->specialization)
                                                    <span class="d-block small text-muted">{{ $project->specialization->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ Str::limit($project->client->name ?? '—', 20) }}</span>
                                            </td>
                                            <td>
                                                @if ($project->deadline_at)
                                                    <span class="{{ $isOverdue ? 'text-danger font-weight-bold' : 'text-dark' }}">
                                                        {{ $project->deadline_at->format('d M Y, H:i') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $project->status_badge_class }} px-2 py-1">
                                                    {{ $project->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($project->status === 'completed')
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Delivered</span>
                                                @elseif ($isOverdue)
                                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> Overdue</span>
                                                @elseif ($daysLeft === 0)
                                                    <span class="badge badge-warning px-2 py-1 text-dark font-weight-bold"><i class="fas fa-clock mr-1"></i> Due Today</span>
                                                @elseif ($daysLeft !== null && $daysLeft > 0)
                                                    <span class="badge badge-light border px-2 py-1 text-secondary">In {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('projects.show', $project) }}" class="btn btn-xs btn-outline-primary" title="Open cockpit">
                                                    <i class="fas fa-folder-open"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                No active projects assigned to your queue.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @elseif ($dashboardType === 'accountant')
                {{-- Accountant: Supplier Invoices & Due Payments Queue --}}
                <div class="card card-outline card-warning shadow-sm mb-4">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-file-invoice text-warning mr-2"></i> Unsettled Vendor Invoices & Payment Schedule
                            </h3>
                            <a href="{{ route('vendor-invoices.index') }}" class="btn btn-sm btn-outline-warning text-dark font-weight-bold">
                                View all invoices <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Vendor / Linguist</th>
                                        <th>Due Date</th>
                                        <th class="text-right">Total Due</th>
                                        <th class="text-right">Paid</th>
                                        <th>Status</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($unpaidInvoices as $inv)
                                        <tr>
                                            <td class="font-weight-bold text-primary">
                                                {{ $inv->invoice_number }}
                                            </td>
                                            <td>
                                                <span class="font-weight-600 text-dark">{{ $inv->vendor->name ?? '—' }}</span>
                                            </td>
                                            <td>
                                                @if ($inv->due_date)
                                                    <span class="{{ $inv->due_date->isPast() ? 'text-danger font-weight-bold' : 'text-secondary' }}">
                                                        {{ $inv->due_date->format('d M Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-right font-weight-bold text-dark">
                                                ${{ number_format($inv->total, 2) }}
                                            </td>
                                            <td class="text-right text-muted">
                                                ${{ number_format($inv->paid_amount, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge badge-warning text-dark px-2 py-1">
                                                    {{ ucfirst($inv->status) }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('vendor-invoices.index') }}" class="btn btn-xs btn-outline-secondary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                No pending invoices due for payment. All accounts balanced.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Accountant: Recent Outgoing Disbursements --}}
                <div class="card card-outline card-success shadow-sm mb-4">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="card-title font-weight-bold text-dark">
                                <i class="fas fa-check-circle text-success mr-2"></i> Recent Supplier Disbursements
                            </h3>
                            <a href="{{ route('vendor-payments.index') }}" class="btn btn-sm btn-outline-success">
                                Payment ledger <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th>Disbursement #</th>
                                        <th>Vendor</th>
                                        <th>Date</th>
                                        <th>Payment Channel</th>
                                        <th class="text-right">Disbursed Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentPayments as $payment)
                                        <tr>
                                            <td class="font-weight-bold text-secondary">{{ $payment->payment_number }}</td>
                                            <td class="font-weight-600 text-dark">{{ $payment->vendor->name ?? '—' }}</td>
                                            <td class="text-muted">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '—' }}</td>
                                            <td>
                                                <span class="badge badge-light border">{{ $payment->paymentMethod->name ?? 'Wire Transfer' }}</span>
                                            </td>
                                            <td class="text-right font-weight-bold text-success">
                                                ${{ number_format($payment->amount, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3 text-muted">No disbursement records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @else
                {{-- General / Staff Project List --}}
                <div class="card card-outline card-primary shadow-sm mb-4">
                    <div class="card-header border-0 pb-0">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-project-diagram text-primary mr-2"></i> Active Projects
                        </h3>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th>Code</th>
                                        <th>Project Name</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Deadline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($generalProjects as $project)
                                        <tr>
                                            <td class="font-weight-bold text-primary">{{ $project->code }}</td>
                                            <td class="font-weight-600 text-dark">{{ $project->name }}</td>
                                            <td class="text-muted">{{ $project->client->name ?? '—' }}</td>
                                            <td><span class="badge {{ $project->status_badge_class }}">{{ $project->status_label }}</span></td>
                                            <td class="text-muted small">{{ $project->deadline_at ? $project->deadline_at->format('d M Y') : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-4 text-muted">No active projects.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Quick Toolkits, Watchlists & Status Visuals (4 Cols) --}}
        <div class="col-lg-4">
            {{-- Quick Actions Card --}}
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-bolt text-warning mr-2"></i> Quick Actions & Shortcuts
                    </h3>
                </div>
                <div class="card-body pt-3">
                    <div class="d-flex flex-column" style="gap: 8px;">
                        @if ($dashboardType === 'admin')
                            <a href="{{ route('projects.create') }}" class="btn btn-outline-primary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-plus-circle text-primary mr-2"></i> <strong>Create Project</strong>
                                <small class="d-block text-muted ml-4">Initialize client order & workflow</small>
                            </a>
                            <a href="{{ route('project-templates.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-copy text-info mr-2"></i> <strong>Project Templates</strong>
                                <small class="d-block text-muted ml-4">Manage reusable project blueprints</small>
                            </a>
                            <a href="{{ route('project-tags.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-tags text-warning mr-2"></i> <strong>Project Tags</strong>
                                <small class="d-block text-muted ml-4">Configure taxonomy & color badges</small>
                            </a>
                            <a href="{{ route('clients.create') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-user-plus text-success mr-2"></i> <strong>New Corporate Client</strong>
                                <small class="d-block text-muted ml-4">Register account and negotiated rates</small>
                            </a>
                            <a href="{{ route('reports.general-by-receivables-and-payables') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-chart-line text-danger mr-2"></i> <strong>Financial Analytics</strong>
                                <small class="d-block text-muted ml-4">Protemos general financial reports</small>
                            </a>
                        @elseif ($dashboardType === 'pm')
                            <a href="{{ route('projects.create') }}" class="btn btn-outline-primary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-plus-circle text-primary mr-2"></i> <strong>Create Project</strong>
                                <small class="d-block text-muted ml-4">Start new translation project</small>
                            </a>
                            <a href="{{ route('project-templates.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-copy text-info mr-2"></i> <strong>Use Project Template</strong>
                                <small class="d-block text-muted ml-4">Fast project creation blueprint</small>
                            </a>
                            <a href="{{ route('project-tags.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-tags text-warning mr-2"></i> <strong>Tags Taxonomy</strong>
                                <small class="d-block text-muted ml-4">Triage rush, MTPE, and sworn jobs</small>
                            </a>
                            <a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-search text-success mr-2"></i> <strong>Find Translators</strong>
                                <small class="d-block text-muted ml-4">Filter by language pair & rate</small>
                            </a>
                            <a href="{{ route('reports.project') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-chart-bar text-info mr-2"></i> <strong>Project Delivery Report</strong>
                                <small class="d-block text-muted ml-4">Analyze margin & delivery speed</small>
                            </a>
                        @elseif ($dashboardType === 'accountant')
                            <a href="{{ route('vendor-payables.index') }}" class="btn btn-outline-warning text-left py-2 px-3 shadow-none border text-dark font-weight-bold">
                                <i class="fas fa-file-invoice-dollar text-warning mr-2"></i> <strong>Vendor Payables</strong>
                                <small class="d-block text-muted ml-4 font-weight-normal">Review pending job milestones</small>
                            </a>
                            <a href="{{ route('vendor-invoices.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-file-invoice text-info mr-2"></i> <strong>Supplier Invoices</strong>
                                <small class="d-block text-muted ml-4">Verify and match bills</small>
                            </a>
                            <a href="{{ route('vendor-payments.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-money-check-alt text-success mr-2"></i> <strong>Record Payment</strong>
                                <small class="d-block text-muted ml-4">Log wire, SEPA, or PayPal payout</small>
                            </a>
                            <a href="{{ route('reports.general-by-invoices') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-calculator text-primary mr-2"></i> <strong>Invoicing Ledger Report</strong>
                                <small class="d-block text-muted ml-4">Reconcile revenue vs expenses</small>
                            </a>
                        @else
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-primary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-list text-primary mr-2"></i> <strong>All Projects</strong>
                            </a>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-building text-info mr-2"></i> <strong>Clients Directory</strong>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary text-left py-2 px-3 shadow-none border">
                                <i class="fas fa-user-shield text-success mr-2"></i> <strong>Account Settings</strong>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Upcoming Delivery Deadlines / Attention Needed --}}
            <div class="card card-outline card-danger shadow-sm mb-4">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-exclamation-circle text-danger mr-2"></i> Urgent Delivery Deadlines
                    </h3>
                </div>
                <div class="card-body p-0 mt-2">
                    <ul class="list-group list-group-flush" style="font-size: 0.88rem;">
                        @forelse ($upcomingDeadlines as $deadl)
                            @php
                                $isOver = $deadl->deadline_at && $deadl->deadline_at->isPast();
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                                <div>
                                    <a href="{{ route('projects.show', $deadl) }}" class="font-weight-bold text-dark">
                                        {{ $deadl->code }} - {{ Str::limit($deadl->name, 22) }}
                                    </a>
                                    <span class="d-block text-muted small">
                                        {{ Str::limit($deadl->client->name ?? '—', 18) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="badge {{ $isOver ? 'badge-danger' : 'badge-warning text-dark' }} px-2 py-1">
                                        {{ $deadl->deadline_at ? $deadl->deadline_at->format('d M, H:i') : '—' }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-3">
                                <i class="fas fa-check-circle text-success mr-1"></i> No impending delivery deadlines.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Production Portfolio Distribution Breakdown --}}
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-chart-pie text-info mr-2"></i> Portfolio Status Breakdown
                    </h3>
                </div>
                <div class="card-body pt-3">
                    @php
                        $totalAll = max(1, $projectCount);
                        $inProgPct = round(($inProgressProjectsCount / $totalAll) * 100);
                        $compPct = round(($completedProjectsCount / $totalAll) * 100);
                        $draftPct = round(($draftProjectsCount / $totalAll) * 100);
                    @endphp
                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-sm mb-1">
                            <span class="font-weight-600 text-primary"><i class="fas fa-spinner mr-1"></i> In Progress</span>
                            <span class="font-weight-bold">{{ $inProgressProjectsCount }} ({{ $inProgPct }}%)</span>
                        </div>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-primary" style="width: {{ $inProgPct }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-sm mb-1">
                            <span class="font-weight-600 text-success"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                            <span class="font-weight-bold">{{ $completedProjectsCount }} ({{ $compPct }}%)</span>
                        </div>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-success" style="width: {{ $compPct }}%"></div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-sm mb-1">
                            <span class="font-weight-600 text-secondary"><i class="fas fa-pencil-alt mr-1"></i> Draft</span>
                            <span class="font-weight-bold">{{ $draftProjectsCount }} ({{ $draftPct }}%)</span>
                        </div>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-secondary" style="width: {{ $draftPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
