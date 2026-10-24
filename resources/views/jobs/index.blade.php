<x-app-layout>
    <x-slot name="header">
        <x-page-header title="JOBS" subtitle="Manage linguist jobs, assignment status, volume delivery, and vendor payables.">
            <x-slot name="actions">
                <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm shadow-sm mr-1">
                    <i class="fas fa-plus mr-1"></i> New job
                </a>
                <a href="{{ route('job-tenders.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-bullhorn mr-1"></i> Tenders
                </a>
                <a href="{{ route('job-chain-templates.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-link mr-1"></i> Chain templates
                </a>
                <a href="{{ route('job-checklists.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-tasks mr-1"></i> Checklists
                </a>
                <a href="{{ route('job-tags.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-tags mr-1"></i> Tags
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    {{-- Filter Accordion --}}
    @php
        $activeFiltersCount = collect(request()->only(['status', 'project_id', 'vendor_id', 'service_id', 'language_pair_id', 'primary_manager_id', 'search']))->filter(fn($v) => !empty($v) && $v !== 'all')->count();
    @endphp
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header bg-white py-2 px-3" style="cursor: pointer;" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="{{ $activeFiltersCount > 0 ? 'true' : 'false' }}">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted font-weight-500">
                    <i class="fas fa-filter mr-1"></i>
                    @if ($activeFiltersCount > 0)
                        <span class="badge badge-info mr-1">{{ $activeFiltersCount }}</span> Filter applied
                    @else
                        No filter applied
                    @endif
                </span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>
        <div id="filterCollapse" class="collapse {{ $activeFiltersCount > 0 ? 'show' : '' }}">
            <div class="card-body py-3 border-top bg-light">
                <form method="GET" action="{{ route('jobs.index') }}">
                    <div class="form-row">
                        <div class="col-md-2 col-sm-6 mb-2">
                            <label class="small text-muted mb-1">Status</label>
                            <select name="status" class="form-control form-control-sm">
                                <option value="all">All statuses</option>
                                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                                <option value="offered" @selected(request('status') === 'offered')>Offered</option>
                                <option value="in_progress" @selected(request('status') === 'in_progress')>In progress</option>
                                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-2">
                            <label class="small text-muted mb-1">Project</label>
                            <select name="project_id" class="form-control form-control-sm">
                                <option value="">All projects</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}" @selected(request('project_id') == $proj->id)>{{ $proj->code }} - {{ $proj->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-2">
                            <label class="small text-muted mb-1">Vendor</label>
                            <select name="vendor_id" class="form-control form-control-sm">
                                <option value="">All vendors</option>
                                @foreach ($vendors as $v)
                                    <option value="{{ $v->id }}" @selected(request('vendor_id') == $v->id)>{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-2">
                            <label class="small text-muted mb-1">Service</label>
                            <select name="service_id" class="form-control form-control-sm">
                                <option value="">All services</option>
                                @foreach ($services as $srv)
                                    <option value="{{ $srv->id }}" @selected(request('service_id') == $srv->id)>{{ $srv->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-2">
                            <label class="small text-muted mb-1">Manager</label>
                            <select name="primary_manager_id" class="form-control form-control-sm">
                                <option value="">All managers</option>
                                @foreach ($managers as $mgr)
                                    <option value="{{ $mgr->id }}" @selected(request('primary_manager_id') == $mgr->id)>{{ $mgr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6 mb-2">
                            <label class="small text-muted mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search code, name...">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm mr-2">Reset</a>
                        <button type="submit" class="btn btn-primary btn-sm">Apply filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Main Table Card with Protemos Header Actions --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="text-uppercase font-weight-bold mb-0" style="color: #009688; letter-spacing: 0.5px;">JOBS</h5>
                
                {{-- Action Buttons --}}
                <div class="d-flex align-items-center flex-wrap mt-2 mt-sm-0">
                    <button type="button" class="btn btn-info btn-sm mr-1 shadow-sm px-3" onclick="submitBulk('offer')">
                        Offer
                    </button>
                    <button type="button" class="btn btn-info btn-sm mr-1 shadow-sm px-3" onclick="submitBulk('start')">
                        Start
                    </button>
                    <button type="button" class="btn btn-success btn-sm mr-1 shadow-sm px-3" onclick="submitBulk('accept')">
                        Accept
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm mr-1 shadow-sm px-3" onclick="submitBulk('cancel')">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm mr-2 shadow-sm px-3" onclick="submitBulk('draft')">
                        Draft
                    </button>
                    <span class="text-muted mr-2 font-weight-light">|</span>
                    <a href="{{ route('jobs.index', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-file-excel mr-1 text-success"></i> Export to Excel
                    </a>
                </div>
            </div>
        </div>

        <form id="bulkActionForm" method="POST" action="{{ route('jobs.bulk-action') }}">
            @csrf
            <input type="hidden" name="action" id="bulkActionInput" value="">

            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'code', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                    Code ⇅
                                </a>
                            </th>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Language pair</th>
                            <th>Deadline at</th>
                            <th>Vendor</th>
                            <th>Status</th>
                            <th>Started at</th>
                            <th>Completed at</th>
                            <th>Tags</th>
                            <th>Work amount</th>
                            <th>Payables, USD</th>
                            <th>Project code</th>
                            <th>Primary project manager</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="job_ids[]" value="{{ $job->id }}" class="job-checkbox">
                                </td>
                                <td class="font-weight-bold">
                                    <a href="{{ route('jobs.show', $job) }}" class="text-primary font-weight-bold">
                                        {{ $job->code }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('jobs.show', $job) }}" class="text-dark">
                                        {{ $job->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $job->service?->name ?? '—' }}</span>
                                </td>
                                <td>
                                    @if ($job->languagePair)
                                        {{ $job->languagePair->sourceLanguage?->name }} → {{ $job->languagePair->targetLanguage?->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($job->deadline_at)
                                        <span class="{{ $job->deadline_at->isPast() && $job->status !== 'completed' ? 'text-danger font-weight-bold' : '' }}">
                                            {{ $job->deadline_at->format('M d, Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($job->vendor)
                                        <a href="{{ route('vendors.show', $job->vendor) }}" class="text-dark">
                                            {{ $job->vendor->name }}
                                        </a>
                                    @else
                                        <span class="text-muted font-italic">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $job->status_badge_class }} px-2 py-1">
                                        {{ $job->status_label }}
                                    </span>
                                </td>
                                <td>
                                    {{ $job->started_at ? $job->started_at->format('M d, Y H:i') : '—' }}
                                </td>
                                <td>
                                    {{ $job->completed_at ? $job->completed_at->format('M d, Y H:i') : '—' }}
                                </td>
                                <td>
                                    @forelse ($job->tags as $t)
                                        <span class="badge" style="background-color: {{ $t->color }}; color: #fff;">
                                            {{ $t->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </td>
                                <td>
                                    {{ number_format($job->work_amount, 2) }} {{ $job->unit?->name ?? '' }}
                                </td>
                                <td class="font-weight-bold text-dark">
                                    $ {{ number_format($job->payables, 2) }}
                                </td>
                                <td>
                                    @if ($job->project)
                                        <a href="{{ route('projects.show', $job->project) }}" class="badge badge-light border">
                                            {{ $job->project->code }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $job->primaryManager?->name ?? '—' }}
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-info" title="View details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline-secondary" title="Edit job">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center py-4 text-muted">
                                    <i class="fas fa-briefcase fa-2x mb-2 d-block text-secondary"></i>
                                    No results found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if ($jobs->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.job-checkbox');

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                });
            }
        });

        function submitBulk(action) {
            const checked = document.querySelectorAll('.job-checkbox:checked');
            if (checked.length === 0) {
                alert('Please select at least one job to apply ' + action + '.');
                return;
            }

            if (confirm('Are you sure you want to change ' + checked.length + ' job(s) to ' + action.toUpperCase() + '?')) {
                document.getElementById('bulkActionInput').value = action;
                document.getElementById('bulkActionForm').submit();
            }
        }
    </script>
    @endpush
</x-app-layout>
