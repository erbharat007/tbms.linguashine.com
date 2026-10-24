<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'PROJECT ' . $project->code" :subtitle="$project->name">
            <x-slot name="actions">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to projects
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    {{-- Tabs matching Protemos --}}
    <ul class="nav nav-pills mb-3" id="projectTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active font-weight-bold" id="details-tab" data-toggle="pill" href="#details" role="tab">
                <i class="fas fa-info-circle mr-1"></i> Details
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="finances-tab" data-toggle="pill" href="#finances" role="tab">
                <i class="fas fa-coins mr-1"></i> Finances
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="jobs-tab" data-toggle="pill" href="#jobs" role="tab">
                <i class="fas fa-tasks mr-1"></i> Jobs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="workflow-tab" data-toggle="pill" href="#workflow" role="tab">
                <i class="fas fa-project-diagram mr-1"></i> Workflow
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="files-tab" data-toggle="pill" href="#files" role="tab">
                <i class="fas fa-folder mr-1"></i> Files
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="cat-logs-tab" data-toggle="pill" href="#cat-logs" role="tab">
                <i class="fas fa-layer-group mr-1"></i> CAT logs
            </a>
        </li>
    </ul>

    <div class="tab-content" id="projectTabsContent">
        {{-- DETAILS TAB --}}
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <div class="row">
                {{-- Left details card --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                            <h5 class="mb-0 font-weight-bold text-dark">
                                PROJECT {{ $project->code }}
                            </h5>
                            <div class="btn-group btn-group-sm">
                                <form action="{{ route('projects.save-as-template', $project) }}" method="POST" class="d-inline mr-1">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        Save as template
                                    </button>
                                </form>
                                <form action="{{ route('projects.clone', $project) }}" method="POST" class="d-inline mr-1">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm">
                                        Clone
                                    </button>
                                </form>
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary btn-sm mr-1">
                                    Update
                                </a>
                                <x-delete-form :action="route('projects.destroy', $project)" message="Delete project {{ $project->code }}?" />
                            </div>
                        </div>

                        <div class="card-body p-0">
                            {{-- Client Section --}}
                            <div class="bg-light px-3 py-1 font-weight-bold text-info small border-top border-bottom">
                                Client
                            </div>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th style="width: 28%;" class="border-top-0 text-muted pl-3">Client</th>
                                    <td class="border-top-0 font-weight-bold">
                                        @if ($project->client)
                                            <a href="{{ route('clients.show', $project->client) }}" class="text-primary">
                                                {{ $project->client->name }}
                                            </a>
                                            <span class="text-muted small">({{ $project->client->country?->name ?? 'International' }})</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Client contact person</th>
                                    <td>
                                        @if ($project->clientContact)
                                            {{ $project->clientContact->first_name }} {{ $project->clientContact->last_name }}
                                            @if ($project->clientContact->position)
                                                <span class="text-muted small">({{ $project->clientContact->position }})</span>
                                            @endif
                                            @if ($project->clientContact->email)
                                                <br><small class="text-muted"><i class="fas fa-envelope mr-1"></i>{{ $project->clientContact->email }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            {{-- Requirements Section --}}
                            <div class="bg-light px-3 py-1 font-weight-bold text-info small border-top border-bottom">
                                Requirements
                            </div>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th style="width: 28%;" class="border-top-0 text-muted pl-3">Name</th>
                                    <td class="border-top-0 font-weight-bold">{{ $project->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Language pairs</th>
                                    <td>
                                        @forelse ($project->languagePairs as $lp)
                                            <span class="badge badge-light border text-dark mr-1 mb-1">
                                                {{ $lp->sourceLanguage?->name ?? 'Source' }} -> {{ $lp->targetLanguage?->name ?? 'Target' }}
                                            </span>
                                        @empty
                                            <span class="text-muted">—</span>
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Specialization</th>
                                    <td>
                                        @if ($project->specialization)
                                            <span class="badge badge-light border">{{ $project->specialization->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Start at</th>
                                    <td>
                                        {{ $project->start_at ? $project->start_at->format('Y-m-d H:i') : '—' }}
                                        @if ($project->start_at)
                                            <small class="text-muted ml-2">({{ $project->start_at->diffForHumans() }})</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Deadline at</th>
                                    <td>
                                        @if ($project->deadline_at)
                                            <span class="{{ $project->deadline_at->isPast() && $project->status !== 'completed' ? 'text-danger font-weight-bold' : '' }}">
                                                {{ $project->deadline_at->format('Y-m-d H:i') }}
                                            </span>
                                            <small class="text-muted ml-2">({{ $project->deadline_at->diffForHumans() }})</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Description</th>
                                    <td>{{ $project->description ?: '—' }}</td>
                                </tr>
                            </table>

                            {{-- Other details Section --}}
                            <div class="bg-light px-3 py-1 font-weight-bold text-info small border-top border-bottom">
                                Other details
                            </div>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th style="width: 28%;" class="border-top-0 text-muted pl-3">Legal entity</th>
                                    <td class="border-top-0">{{ $project->legalEntity?->name ?? 'Master Entity' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Internal note</th>
                                    <td>{{ $project->internal_note ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Tags</th>
                                    <td>
                                        @forelse ($project->tags as $tag)
                                            <span class="badge badge-secondary mr-1">{{ $tag->name }}</span>
                                        @empty
                                            <span class="text-muted">—</span>
                                        @endforelse
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Created at</th>
                                    <td>
                                        {{ $project->created_at ? $project->created_at->format('Y-m-d H:i') : '—' }}
                                        @if ($project->createdBy)
                                            | <span class="text-muted">{{ $project->createdBy->name }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Right Managers & Status Card --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 font-weight-bold text-uppercase text-muted">
                                MANAGERS & STATUS
                            </h6>
                            <div class="btn-group btn-group-sm">
                                @if ($project->status !== 'completed')
                                    <form action="{{ route('projects.complete', $project) }}" method="POST" class="d-inline mr-1">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Complete</button>
                                    </form>
                                @endif
                                @if ($project->status !== 'cancelled')
                                    <form action="{{ route('projects.cancel', $project) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th style="width: 45%;" class="border-top-0 text-muted pl-3">Primary manager</th>
                                    <td class="border-top-0 font-weight-bold">
                                        {{ $project->primaryManager?->name ?? '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Secondary manager</th>
                                    <td>{{ $project->secondaryManager?->name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Status</th>
                                    <td>
                                        <span class="badge {{ $project->status_badge_class }} px-2 py-1">
                                            {{ $project->status_label }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted pl-3">Deadline</th>
                                    <td>
                                        @if ($project->deadline_at)
                                            <span class="{{ $project->deadline_at->isPast() && $project->status !== 'completed' ? 'text-danger font-weight-bold' : 'text-dark' }}">
                                                {{ $project->deadline_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- Mini Financial Glance --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-2">
                            <h6 class="mb-0 font-weight-bold text-muted text-uppercase">
                                Financial Snapshot
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Receivables:</span>
                                <span class="font-weight-bold text-primary">$ {{ number_format($project->total_to_receive, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Payables:</span>
                                <span class="font-weight-bold text-danger">$ {{ number_format($project->total_to_pay, 2) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Gross Profit:</span>
                                <span class="font-weight-bold text-success">$ {{ number_format($project->profit, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Profit Margin:</span>
                                <span class="badge badge-success px-2 py-1">{{ number_format($project->profit_margin, 1) }} %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FINANCES TAB --}}
        <div class="tab-pane fade" id="finances" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-2">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-file-invoice-dollar mr-1 text-primary"></i> Project Financial Ledger
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light text-center">
                                <p class="text-muted mb-1 small text-uppercase font-weight-bold">Total Receivables</p>
                                <h3 class="font-weight-bold text-primary mb-0">$ {{ number_format($project->total_to_receive, 2) }}</h3>
                                <small class="text-muted">Client billing obligation</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light text-center">
                                <p class="text-muted mb-1 small text-uppercase font-weight-bold">Total Payables</p>
                                <h3 class="font-weight-bold text-danger mb-0">$ {{ number_format($project->total_to_pay, 2) }}</h3>
                                <small class="text-muted">Vendor & task costs</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light text-center">
                                <p class="text-muted mb-1 small text-uppercase font-weight-bold">Gross Profit</p>
                                <h3 class="font-weight-bold text-success mb-0">$ {{ number_format($project->profit, 2) }}</h3>
                                <small class="text-muted">Net realized spread</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light text-center">
                                <p class="text-muted mb-1 small text-uppercase font-weight-bold">Profit Margin</p>
                                <h3 class="font-weight-bold text-info mb-0">{{ number_format($project->profit_margin, 1) }} %</h3>
                                <small class="text-muted">Operating profit ratio</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- JOBS TAB --}}
        <div class="tab-pane fade" id="jobs" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-tasks mr-1 text-warning"></i> Production Jobs
                    </h5>
                    <a href="{{ route('jobs.create', ['project_id' => $project->id]) }}" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus mr-1"></i> New job
                    </a>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Job Code</th>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Language Pair</th>
                                <th>Assigned Vendor</th>
                                <th>Work amount</th>
                                <th class="text-right">Payables</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($project->jobs as $j)
                                <tr>
                                    <td class="font-weight-bold">
                                        <a href="{{ route('jobs.show', $j) }}" class="text-primary font-weight-bold">
                                            {{ $j->code }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('jobs.show', $j) }}" class="text-dark">
                                            {{ $j->name }}
                                        </a>
                                    </td>
                                    <td><span class="badge badge-light border">{{ $j->service?->name ?? '—' }}</span></td>
                                    <td>
                                        @if ($j->languagePair)
                                            {{ $j->languagePair->sourceLanguage?->name }} → {{ $j->languagePair->targetLanguage?->name }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($j->vendor)
                                            <a href="{{ route('vendors.show', $j->vendor) }}" class="text-dark">
                                                {{ $j->vendor->name }}
                                            </a>
                                        @else
                                            <span class="text-muted font-italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($j->work_amount, 2) }} {{ $j->unit?->name ?? '' }}</td>
                                    <td class="text-right font-weight-bold">$ {{ number_format($j->payables, 2) }}</td>
                                    <td><span class="badge {{ $j->status_badge_class }}">{{ $j->status_label }}</span></td>
                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('jobs.show', $j) }}" class="btn btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('jobs.edit', $j) }}" class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        No jobs created for this project yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- WORKFLOW TAB --}}
        <div class="tab-pane fade" id="workflow" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-2">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-project-diagram mr-1 text-info"></i> Project Lifecycle & Quality Milestones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                                <h6 class="font-weight-bold">1. Project Kick-off</h6>
                                <span class="badge badge-success">Complete</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-language fa-2x text-info mb-2"></i>
                                <h6 class="font-weight-bold">2. Translation</h6>
                                <span class="badge badge-success">Complete</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-spell-check fa-2x text-warning mb-2"></i>
                                <h6 class="font-weight-bold">3. Proofreading & LQA</h6>
                                <span class="badge badge-info">In Progress</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h6 class="font-weight-bold">4. Client Delivery</h6>
                                <span class="badge badge-secondary">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILES TAB --}}
        <div class="tab-pane fade" id="files" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-folder mr-1 text-warning"></i> Project Assets & Deliverables
                    </h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="alert('Select files to upload...')">
                        <i class="fas fa-upload mr-1"></i> Upload file
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Filename</th>
                                <th>Folder Category</th>
                                <th>Size</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="far fa-file-word text-primary mr-2"></i>Clinical_Protocol_Phase_III_EN.docx</td>
                                <td><span class="badge badge-info">Source Files</span></td>
                                <td>1.4 MB</td>
                                <td>{{ $project->primaryManager?->name ?? 'Manager' }}</td>
                                <td>{{ $project->created_at?->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td><i class="far fa-file-pdf text-danger mr-2"></i>Study_Specifications_and_Glossary.pdf</td>
                                <td><span class="badge badge-secondary">Reference</span></td>
                                <td>820 KB</td>
                                <td>{{ $project->primaryManager?->name ?? 'Manager' }}</td>
                                <td>{{ $project->created_at?->format('d M Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CAT LOGS TAB --}}
        <div class="tab-pane fade" id="cat-logs" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-layer-group mr-1 text-secondary"></i> CAT Log Analysis (Trados / MemoQ)
                    </h5>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="alert('Importing CAT analysis log...')">
                        <i class="fas fa-file-import mr-1"></i> Import log
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm text-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Match Category</th>
                                <th class="text-right">Words</th>
                                <th class="text-right">Percentage %</th>
                                <th class="text-right">Applied Rate %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Repetitions</td>
                                <td class="text-right font-weight-bold">1,250</td>
                                <td class="text-right">14.7 %</td>
                                <td class="text-right">30 %</td>
                            </tr>
                            <tr>
                                <td>100% Context Matches</td>
                                <td class="text-right font-weight-bold">2,400</td>
                                <td class="text-right">28.2 %</td>
                                <td class="text-right">30 %</td>
                            </tr>
                            <tr>
                                <td>95% - 99% Fuzzy</td>
                                <td class="text-right font-weight-bold">850</td>
                                <td class="text-right">10.0 %</td>
                                <td class="text-right">60 %</td>
                            </tr>
                            <tr>
                                <td>New / No Match</td>
                                <td class="text-right font-weight-bold">4,000</td>
                                <td class="text-right">47.1 %</td>
                                <td class="text-right">100 %</td>
                            </tr>
                            <tr class="thead-light font-weight-bold">
                                <td>Total Volume</td>
                                <td class="text-right">8,500 Words</td>
                                <td class="text-right">100.0 %</td>
                                <td class="text-right">—</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
