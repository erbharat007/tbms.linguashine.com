<x-app-layout>
    <x-slot name="header">
        <x-page-header title="JOB: {{ $job->code }}" subtitle="{{ $job->name }}">
            <x-slot name="actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-arrow-left mr-1"></i> Jobs list
                </a>
                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-primary btn-sm mr-1 shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Edit job
                </a>
                @if (!$job->tender)
                    <a href="{{ route('job-tenders.create', ['job_id' => $job->id]) }}" class="btn btn-outline-info btn-sm mr-1">
                        <i class="fas fa-bullhorn mr-1"></i> Create tender
                    </a>
                @endif
                <form method="POST" action="{{ route('jobs.destroy', $job) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete job {{ $job->code }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Delete
                    </button>
                </form>
            </x-slot>
        </x-page-header>
    </x-slot>

    {{-- Status Action Ribbon matching Protemos --}}
    <div class="card mb-3 shadow-sm border-0 bg-light">
        <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center mb-1 mb-md-0">
                <span class="text-muted mr-2 font-weight-500">Status:</span>
                <span class="badge {{ $job->status_badge_class }} px-3 py-1 font-size-14">{{ $job->status_label }}</span>
                @if ($job->project)
                    <span class="ml-3 text-muted">Project:</span>
                    <a href="{{ route('projects.show', $job->project) }}" class="ml-1 font-weight-bold text-primary">
                        {{ $job->project->code }} — {{ $job->project->name }}
                    </a>
                @endif
            </div>

            {{-- Status Transition Buttons --}}
            <div class="btn-group btn-group-sm">
                @if ($job->status !== 'offered')
                    <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="offered">
                        <button type="submit" class="btn btn-info btn-sm mr-1 shadow-sm px-3">Offer</button>
                    </form>
                @endif

                @if ($job->status !== 'in_progress')
                    <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="btn btn-info btn-sm mr-1 shadow-sm px-3">Start</button>
                    </form>
                @endif

                @if ($job->status !== 'completed')
                    <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success btn-sm mr-1 shadow-sm px-3">Accept</button>
                    </form>
                @endif

                @if ($job->status !== 'cancelled')
                    <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-secondary btn-sm mr-1 shadow-sm px-3">Cancel</button>
                    </form>
                @endif

                @if ($job->status !== 'draft')
                    <form method="POST" action="{{ route('jobs.update-status', $job) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="status" value="draft">
                        <button type="submit" class="btn btn-secondary btn-sm shadow-sm px-3">Draft</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Details & Volume --}}
        <div class="col-lg-8">
            {{-- Details Card --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Job Overview</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="text-muted">Job Name</th>
                                <td class="font-weight-bold text-dark">{{ $job->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Parent Project</th>
                                <td>
                                    @if ($job->project)
                                        <a href="{{ route('projects.show', $job->project) }}" class="font-weight-bold">
                                            {{ $job->project->code }} — {{ $job->project->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Service</th>
                                <td><span class="badge badge-light border">{{ $job->service?->name ?? '—' }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Language Pair</th>
                                <td class="font-weight-bold">
                                    @if ($job->languagePair)
                                        {{ $job->languagePair->sourceLanguage?->name }} → {{ $job->languagePair->targetLanguage?->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Assigned Vendor</th>
                                <td>
                                    @if ($job->vendor)
                                        <a href="{{ route('vendors.show', $job->vendor) }}" class="font-weight-bold text-primary">
                                            <i class="fas fa-user-check mr-1 text-success"></i> {{ $job->vendor->name }}
                                        </a>
                                        @if ($job->vendorContact)
                                            <span class="text-muted small ml-1">({{ $job->vendorContact->name }})</span>
                                        @endif
                                    @else
                                        <span class="text-muted font-italic">Unassigned (Ready for tender)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Primary Manager</th>
                                <td>{{ $job->primaryManager?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tags</th>
                                <td>
                                    @forelse ($job->tags as $t)
                                        <span class="badge mr-1" style="background-color: {{ $t->color }}; color: #fff;">{{ $t->name }}</span>
                                    @empty
                                        <span class="text-muted">No tags assigned</span>
                                    @endforelse
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Instructions & Description --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Instructions & Notes</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-muted font-weight-bold small text-uppercase mb-2">Instructions for Vendor</h6>
                    <div class="p-3 bg-light rounded mb-3">
                        {!! nl2br(e($job->instructions ?: 'No specific instructions provided.')) !!}
                    </div>

                    @if ($job->notes)
                        <h6 class="text-muted font-weight-bold small text-uppercase mb-2">Internal Manager Notes</h6>
                        <div class="p-3 bg-light rounded text-muted">
                            {!! nl2br(e($job->notes)) !!}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Checklist (if linked) --}}
            @if ($job->checklist)
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold mb-0 text-primary">
                            <i class="fas fa-tasks mr-1"></i> Quality Checklist: {{ $job->checklist->name }}
                        </h6>
                        <a href="{{ route('job-checklists.show', $job->checklist) }}" class="btn btn-outline-secondary btn-sm">View checklist</a>
                    </div>
                    <div class="card-body">
                        @if ($job->checklist->note_for_vendor)
                            <div class="alert alert-info py-2 mb-3 small">
                                <i class="fas fa-info-circle mr-1"></i> {{ $job->checklist->note_for_vendor }}
                            </div>
                        @endif

                        <ul class="list-group list-group-flush">
                            @foreach ($job->checklist->items ?? [] as $idx => $item)
                                <li class="list-group-item d-flex align-items-center py-2 px-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="chk_{{ $idx }}" checked disabled>
                                        <label class="custom-control-label" for="chk_{{ $idx }}">
                                            {{ is_array($item) ? ($item['text'] ?? '') : $item }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Finances, Timeline, Tender --}}
        <div class="col-lg-4">
            {{-- Finances Card --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-success">
                        <i class="fas fa-dollar-sign mr-1"></i> Payables & Volume
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">Work amount</span>
                        <span class="font-weight-bold">{{ number_format($job->work_amount, 2) }} {{ $job->unit?->name ?? 'Units' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">Price per unit</span>
                        <span class="font-weight-bold">$ {{ number_format($job->price_per_unit, 4) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">Currency</span>
                        <span class="font-weight-bold">{{ $job->currency?->code ?? 'USD' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3">
                        <span class="font-weight-bold text-dark">Total Payables:</span>
                        <h4 class="font-weight-bold text-success mb-0">$ {{ number_format($job->payables, 2) }}</h4>
                    </div>
                </div>
            </div>

            {{-- Timeline Card --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">
                        <i class="fas fa-clock mr-1"></i> Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">Started at</span>
                        <span class="font-weight-bold">{{ $job->started_at ? $job->started_at->format('M d, Y H:i') : 'Not started' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="text-muted">Deadline at</span>
                        <span class="font-weight-bold {{ $job->deadline_at && $job->deadline_at->isPast() && $job->status !== 'completed' ? 'text-danger' : '' }}">
                            {{ $job->deadline_at ? $job->deadline_at->format('M d, Y H:i') : 'No deadline' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-muted">Completed at</span>
                        <span class="font-weight-bold text-success">{{ $job->completed_at ? $job->completed_at->format('M d, Y H:i') : '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Linked Tender (if any) --}}
            @if ($job->tender)
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="font-weight-bold mb-0 text-info">
                            <i class="fas fa-bullhorn mr-1"></i> Linked Tender
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted">Tender Code</span>
                            <a href="{{ route('job-tenders.show', $job->tender) }}" class="font-weight-bold text-primary">
                                {{ $job->tender->code }}
                            </a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted">Type</span>
                            <span>{{ $job->tender->type_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <span class="text-muted">Status</span>
                            <span class="badge {{ $job->tender->status_badge_class }}">{{ $job->tender->status_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <span class="text-muted">Participating Vendors</span>
                            <span class="badge badge-info">{{ $job->tender->participating_vendors_count }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
