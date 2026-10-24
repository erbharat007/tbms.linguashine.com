<x-app-layout>
    <x-slot name="header">
        <x-page-header title="JOB TENDERS" subtitle="Broadcast jobs to candidate linguists and manage vendor applications.">
            <x-slot name="actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-briefcase mr-1"></i> Jobs
                </a>
                <a href="{{ route('job-tenders.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus mr-1"></i> New tender
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    {{-- Filter bar --}}
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-white py-2 px-3" style="cursor: pointer;" data-toggle="collapse" data-target="#tenderFilterCollapse">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted font-weight-500">
                    <i class="fas fa-filter mr-1"></i> Filter options
                </span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>
        <div id="tenderFilterCollapse" class="collapse">
            <div class="card-body py-3 border-top bg-light">
                <form method="GET" action="{{ route('job-tenders.index') }}">
                    <div class="form-row">
                        <div class="col-md-3 mb-2">
                            <label class="small text-muted mb-1">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="all">All statuses</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="small text-muted mb-1">Type</label>
                            <select name="type" class="form-control form-control-sm">
                                <option value="all">All types</option>
                                <option value="first_claimer" @selected(request('type') === 'first_claimer')>First claimer</option>
                                <option value="manager_selection_fixed" @selected(request('type') === 'manager_selection_fixed')>Manager selection (fixed)</option>
                                <option value="manager_selection_negotiable" @selected(request('type') === 'manager_selection_negotiable')>Manager selection (negotiable)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small text-muted mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tender code or job name...">
                        </div>
                        <div class="col-md-2 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm btn-block">Apply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Table matching Protemos columns --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 px-3">
            <h5 class="text-uppercase font-weight-bold mb-0" style="color: #009688; letter-spacing: 0.5px;">JOB TENDERS</h5>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped mb-0 text-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th>Code ⇅</th>
                        <th>Job</th>
                        <th>Job service</th>
                        <th>Job language pair</th>
                        <th>Type</th>
                        <th>Unit</th>
                        <th>Unit amount</th>
                        <th>Currency</th>
                        <th>Price per unit</th>
                        <th>Total</th>
                        <th>Number of participating vendors</th>
                        <th>Status</th>
                        <th>Started at</th>
                        <th>Vendor application deadline at</th>
                        <th>Ended at</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenders as $tender)
                        <tr>
                            <td class="font-weight-bold">
                                <a href="{{ route('job-tenders.show', $tender) }}" class="text-primary font-weight-bold">
                                    {{ $tender->code }}
                                </a>
                            </td>
                            <td>
                                @if ($tender->job)
                                    <a href="{{ route('jobs.show', $tender->job) }}" class="text-dark">
                                        {{ $tender->job->code }} — {{ $tender->job->name }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light border">{{ $tender->job?->service?->name ?? '—' }}</span>
                            </td>
                            <td>
                                @if ($tender->job?->languagePair)
                                    {{ $tender->job->languagePair->sourceLanguage?->name }} → {{ $tender->job->languagePair->targetLanguage?->name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $tender->type_label }}</td>
                            <td>{{ $tender->job?->unit?->name ?? '—' }}</td>
                            <td>{{ $tender->job ? number_format($tender->job->work_amount, 2) : '—' }}</td>
                            <td>{{ $tender->job?->currency?->code ?? 'USD' }}</td>
                            <td>{{ $tender->job ? number_format($tender->job->price_per_unit, 4) : '—' }}</td>
                            <td class="font-weight-bold text-dark">
                                $ {{ $tender->job ? number_format($tender->job->payables, 2) : '0.00' }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary px-2">{{ $tender->participating_vendors_count }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $tender->status_badge_class }} px-2 py-1">
                                    {{ $tender->status_label }}
                                </span>
                            </td>
                            <td>{{ $tender->started_at ? $tender->started_at->format('M d, Y H:i') : '—' }}</td>
                            <td>{{ $tender->deadline_at ? $tender->deadline_at->format('M d, Y H:i') : '—' }}</td>
                            <td>{{ $tender->ended_at ? $tender->ended_at->format('M d, Y H:i') : '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('job-tenders.show', $tender) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="text-center py-4 text-muted">
                                <i class="fas fa-bullhorn fa-2x mb-2 d-block text-secondary"></i>
                                No results found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tenders->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $tenders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
