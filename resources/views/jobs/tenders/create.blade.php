<x-app-layout>
    <x-slot name="header">
        <x-page-header title="NEW JOB TENDER" subtitle="Launch a new competitive tender for available vendor assignments.">
            <x-slot name="actions">
                <a href="{{ route('job-tenders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to tenders
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Tender Parameters</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('job-tenders.store') }}">
                        @csrf

                        <div class="form-group">
                            <label class="font-weight-500">Job to Tender <span class="text-danger">*</span></label>
                            <select name="project_job_id" class="form-control @error('project_job_id') is-invalid @enderror" required>
                                <option value="">Select Eligible Job</option>
                                @foreach ($eligibleJobs as $jb)
                                    <option value="{{ $jb->id }}" @selected(old('project_job_id', $selectedJobId) == $jb->id)>
                                        {{ $jb->code }} — {{ $jb->name }} ({{ $jb->service?->name }}, {{ $jb->languagePair?->sourceLanguage?->name }} → {{ $jb->languagePair?->targetLanguage?->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('project_job_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Only unassigned or draft/offered jobs can be launched into tender.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Tender Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="first_claimer" @selected(old('type') === 'first_claimer')>First claimer (First vendor to accept wins)</option>
                                    <option value="manager_selection_fixed" @selected(old('type') === 'manager_selection_fixed')>Manager selection (Fixed price)</option>
                                    <option value="manager_selection_negotiable" @selected(old('type') === 'manager_selection_negotiable')>Manager selection (Negotiable price)</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Application Deadline</label>
                                <input type="datetime-local" name="deadline_at" class="form-control" value="{{ old('deadline_at') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-500">Notes for Vendors</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Specify any specific instructions or requirements for applying vendors...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="border-top pt-3 mt-4 d-flex justify-content-between">
                            <a href="{{ route('job-tenders.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-bullhorn mr-1"></i> Launch Tender
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
