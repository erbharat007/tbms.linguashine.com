<x-app-layout>
    <x-slot name="header">
        <x-page-header title="NEW PROJECT" subtitle="Create a new translation, proofreading, or localization project.">
            <x-slot name="actions">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to projects
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    @if ($template)
        <div class="alert alert-info py-2 mb-3">
            <i class="fas fa-info-circle mr-1"></i> Pre-filling details from template: <strong>{{ $template->name }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        {{-- Client --}}
                        <div class="form-group row">
                            <label for="client_id" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Client <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                    <option value="">Select client...</option>
                                    @foreach ($clients as $c)
                                        <option value="{{ $c->id }}" @selected(old('client_id', $template?->client_id) == $c->id)>
                                            {{ $c->name }} ({{ $c->country?->name ?? 'International' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Client contact person --}}
                        <div class="form-group row">
                            <label for="client_contact_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Client contact person
                            </label>
                            <div class="col-md-7">
                                <select name="client_contact_id" id="client_contact_id" class="form-control @error('client_contact_id') is-invalid @enderror">
                                    <option value="">Select contact person...</option>
                                    @foreach ($clients as $c)
                                        @foreach ($c->contacts as $contact)
                                            <option value="{{ $contact->id }}" data-client-id="{{ $c->id }}" @selected(old('client_contact_id', $template?->client_contact_id) == $contact->id)>
                                                {{ $contact->first_name }} {{ $contact->last_name }} ({{ $contact->position ?? $contact->email }})
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('client_contact_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Name --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Name <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $template?->name) }}" required placeholder="e.g. Clinical Study Protocol Phase III">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Language pairs --}}
                        <div class="form-group row">
                            <label for="language_pair_ids" class="col-md-3 col-form-label text-md-right text-muted">
                                Language pairs
                            </label>
                            <div class="col-md-7">
                                <select name="language_pair_ids[]" id="language_pair_ids" class="form-control select2 @error('language_pair_ids') is-invalid @enderror" multiple style="height: 120px;">
                                    @foreach ($languagePairs as $lp)
                                        <option value="{{ $lp->id }}" @selected(in_array($lp->id, old('language_pair_ids', $template ? $template->languagePairs->pluck('id')->toArray() : [])))>
                                            {{ $lp->sourceLanguage?->name ?? 'Source' }} -> {{ $lp->targetLanguage?->name ?? 'Target' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl / Cmd to select multiple language combinations.</small>
                                @error('language_pair_ids')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Specialization --}}
                        <div class="form-group row">
                            <label for="specialization_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Specialization
                            </label>
                            <div class="col-md-7">
                                <select name="specialization_id" id="specialization_id" class="form-control @error('specialization_id') is-invalid @enderror">
                                    <option value="">Select specialization...</option>
                                    @foreach ($specializations as $s)
                                        <option value="{{ $s->id }}" @selected(old('specialization_id', $template?->specialization_id) == $s->id)>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialization_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Start at --}}
                        <div class="form-group row">
                            <label for="start_at" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Start at <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="datetime-local" name="start_at" id="start_at" class="form-control @error('start_at') is-invalid @enderror" value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                                @error('start_at')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Deadline at --}}
                        <div class="form-group row">
                            <label for="deadline_at" class="col-md-3 col-form-label text-md-right text-muted">
                                Deadline at
                            </label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="datetime-local" name="deadline_at" id="deadline_at" class="form-control @error('deadline_at') is-invalid @enderror" value="{{ old('deadline_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                                @error('deadline_at')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right text-muted">
                                Description
                            </label>
                            <div class="col-md-7">
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Project scope, client instructions, guidelines...">{{ old('description', $template?->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Internal note --}}
                        <div class="form-group row">
                            <label for="internal_note" class="col-md-3 col-form-label text-md-right text-muted">
                                Internal note
                            </label>
                            <div class="col-md-7">
                                <textarea name="internal_note" id="internal_note" rows="3" class="form-control @error('internal_note') is-invalid @enderror" placeholder="Confidential internal notes for project team only...">{{ old('internal_note', $template?->internal_note) }}</textarea>
                                @error('internal_note')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Primary manager --}}
                        <div class="form-group row">
                            <label for="primary_manager_id" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Primary manager <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <select name="primary_manager_id" id="primary_manager_id" class="form-control @error('primary_manager_id') is-invalid @enderror" required>
                                    @foreach ($managers as $m)
                                        <option value="{{ $m->id }}" @selected(old('primary_manager_id', $template?->primary_manager_id ?? auth()->id()) == $m->id)>
                                            {{ $m->name }} ({{ $m->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('primary_manager_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Secondary manager --}}
                        <div class="form-group row">
                            <label for="secondary_manager_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Secondary manager
                            </label>
                            <div class="col-md-7">
                                <select name="secondary_manager_id" id="secondary_manager_id" class="form-control @error('secondary_manager_id') is-invalid @enderror">
                                    <option value="">None</option>
                                    @foreach ($managers as $m)
                                        <option value="{{ $m->id }}" @selected(old('secondary_manager_id', $template?->secondary_manager_id) == $m->id)>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('secondary_manager_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Legal Entity --}}
                        <div class="form-group row">
                            <label for="legal_entity_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Legal entity
                            </label>
                            <div class="col-md-7">
                                <select name="legal_entity_id" id="legal_entity_id" class="form-control">
                                    @foreach ($legalEntities as $le)
                                        <option value="{{ $le->id }}" @selected(old('legal_entity_id') == $le->id)>
                                            {{ $le->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Tags --}}
                        @if ($tags->isNotEmpty())
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-md-right text-muted">
                                    Tags
                                </label>
                                <div class="col-md-7 pt-2">
                                    @foreach ($tags as $tag)
                                        <div class="custom-control custom-checkbox custom-control-inline">
                                            <input type="checkbox" name="tag_ids[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}" class="custom-control-input" @checked(in_array($tag->id, old('tag_ids', [])))>
                                            <label class="custom-control-label font-weight-normal" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Financial Estimates --}}
                        <div class="form-group row">
                            <label for="total_to_receive" class="col-md-3 col-form-label text-md-right text-muted">
                                Total to receive (USD)
                            </label>
                            <div class="col-md-3">
                                <input type="number" step="0.01" min="0" name="total_to_receive" id="total_to_receive" class="form-control" value="{{ old('total_to_receive', '0.00') }}" placeholder="0.00">
                            </div>
                            <label for="total_to_pay" class="col-md-2 col-form-label text-md-right text-muted">
                                Total to pay (USD)
                            </label>
                            <div class="col-md-2">
                                <input type="number" step="0.01" min="0" name="total_to_pay" id="total_to_pay" class="form-control" value="{{ old('total_to_pay', '0.00') }}" placeholder="0.00">
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-white border-top py-3">
                        <div class="row">
                            <div class="col-md-7 offset-md-3">
                                <p class="small text-muted mb-2">Fields marked with <span class="text-danger font-weight-bold">*</span> are mandatory.</p>
                                <button type="submit" class="btn btn-primary px-4 mr-2">
                                    Create
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-default border">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clientSelect = document.getElementById('client_id');
            const contactSelect = document.getElementById('client_contact_id');

            function filterContacts() {
                const clientId = clientSelect.value;
                const options = contactSelect.querySelectorAll('option');

                options.forEach(opt => {
                    if (!opt.value) {
                        opt.style.display = '';
                        return;
                    }
                    const optClientId = opt.getAttribute('data-client-id');
                    if (!clientId || optClientId === clientId) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                        if (opt.selected) {
                            contactSelect.value = '';
                        }
                    }
                });
            }

            if (clientSelect && contactSelect) {
                clientSelect.addEventListener('change', filterContacts);
                filterContacts();
            }
        });
    </script>
</x-app-layout>
