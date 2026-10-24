<x-app-layout>
    <x-slot name="header">
        <x-page-header title="TENDER: {{ $jobTender->code }}" subtitle="Tender management and vendor selection.">
            <x-slot name="actions">
                <a href="{{ route('job-tenders.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-arrow-left mr-1"></i> Back to tenders
                </a>
                <form method="POST" action="{{ route('job-tenders.destroy', $jobTender) }}" class="d-inline" onsubmit="return confirm('Delete tender {{ $jobTender->code }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Delete
                    </button>
                </form>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold mb-0 text-primary">Tender Information</h6>
                    <span class="badge {{ $jobTender->status_badge_class }} px-2 py-1">{{ $jobTender->status_label }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;" class="text-muted">Tender Code</th>
                                <td class="font-weight-bold">{{ $jobTender->code }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Associated Job</th>
                                <td>
                                    @if ($jobTender->job)
                                        <a href="{{ route('jobs.show', $jobTender->job) }}" class="font-weight-bold text-primary">
                                            {{ $jobTender->job->code }} — {{ $jobTender->job->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Service</th>
                                <td><span class="badge badge-light border">{{ $jobTender->job?->service?->name ?? '—' }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Language Pair</th>
                                <td>
                                    @if ($jobTender->job?->languagePair)
                                        {{ $jobTender->job->languagePair->sourceLanguage?->name }} → {{ $jobTender->job->languagePair->targetLanguage?->name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Tender Type</th>
                                <td class="font-weight-bold">{{ $jobTender->type_label }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Volume & Payables</th>
                                <td>
                                    {{ number_format($jobTender->job?->work_amount ?? 0, 2) }} {{ $jobTender->job?->unit?->name ?? 'units' }} 
                                    at ${{ number_format($jobTender->job?->price_per_unit ?? 0, 4) }}/unit 
                                    (<strong>Total: ${{ number_format($jobTender->job?->payables ?? 0, 2) }}</strong>)
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Participating Vendors</th>
                                <td>
                                    <span class="badge badge-info font-size-14">{{ $jobTender->participating_vendors_count }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Notes for Vendors</th>
                                <td>{{ $jobTender->notes ?: 'None' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Status & Deadlines</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('job-tenders.update', $jobTender) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="font-weight-500">Status</label>
                            <select name="status" class="form-control">
                                <option value="active" @selected($jobTender->status === 'active')>Active</option>
                                <option value="completed" @selected($jobTender->status === 'completed')>Completed</option>
                                <option value="cancelled" @selected($jobTender->status === 'cancelled')>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-500">Application Deadline</label>
                            <input type="datetime-local" name="deadline_at" class="form-control" value="{{ $jobTender->deadline_at?->format('Y-m-d\TH:i') }}">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block shadow-sm">
                            <i class="fas fa-save mr-1"></i> Update Tender
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
