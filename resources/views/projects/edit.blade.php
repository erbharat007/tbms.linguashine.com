<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'UPDATE PROJECT ' . $project->code" :subtitle="'Editing project: ' . $project->name">
            <x-slot name="actions">
                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to project
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        {{-- Status & Code --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right text-muted">
                                Project Code
                            </label>
                            <div class="col-md-7">
                                <span class="badge badge-light border px-3 py-2 font-weight-bold" style="font-size: 1rem;">
                                    {{ $project->code }}
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="status" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Status <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <select name="status" id="status" class="form-control" required>
                                    <option value="draft" @selected(old('status', $project->status) === 'draft')>Draft</option>
                                    <option value="in_progress" @selected(old('status', $project->status) === 'in_progress')>In progress</option>
                                    <option value="completed" @selected(old('status', $project->status) === 'completed')>Completed</option>
                                    <option value="cancelled" @selected(old('status', $project->status) === 'cancelled')>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        {{-- Client --}}
                        <div class="form-group row">
                            <label for="client_id" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Client <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                    @foreach ($clients as $c)
                                        <option value="{{ $c->id }}" @selected(old('client_id', $project->client_id) == $c->id)>
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
                                <select name="client_contact_id" id="client_contact_id" class="form-control">
                                    <option value="">Select contact person...</option>
                                    @foreach ($clients as $c)
                                        @foreach ($c->contacts as $contact)
                                            <option value="{{ $contact->id }}" data-client-id="{{ $c->id }}" @selected(old('client_contact_id', $project->client_contact_id) == $contact->id)>
                                                {{ $contact->first_name }} {{ $contact->last_name }} ({{ $contact->position ?? $contact->email }})
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Name --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Name <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $project->name) }}" required>
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
                                <select name="language_pair_ids[]" id="language_pair_ids" class="form-control" multiple style="height: 120px;">
                                    @foreach ($languagePairs as $lp)
                                        <option value="{{ $lp->id }}" @selected(in_array($lp->id, old('language_pair_ids', $project->languagePairs->pluck('id')->toArray())))>
                                            {{ $lp->sourceLanguage?->name ?? 'Source' }} -> {{ $lp->targetLanguage?->name ?? 'Target' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Hold Ctrl / Cmd to select multiple language combinations.</small>
                            </div>
                        </div>

                        {{-- Specialization --}}
                        <div class="form-group row">
                            <label for="specialization_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Specialization
                            </label>
                            <div class="col-md-7">
                                <select name="specialization_id" id="specialization_id" class="form-control">
                                    <option value="">Select specialization...</option>
                                    @foreach ($specializations as $s)
                                        <option value="{{ $s->id }}" @selected(old('specialization_id', $project->specialization_id) == $s->id)>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Start at --}}
                        <div class="form-group row">
                            <label for="start_at" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Start at <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <input type="datetime-local" name="start_at" id="start_at" class="form-control" value="{{ old('start_at', $project->start_at?->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>

                        {{-- Deadline at --}}
                        <div class="form-group row">
                            <label for="deadline_at" class="col-md-3 col-form-label text-md-right text-muted">
                                Deadline at
                            </label>
                            <div class="col-md-7">
                                <input type="datetime-local" name="deadline_at" id="deadline_at" class="form-control" value="{{ old('deadline_at', $project->deadline_at?->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right text-muted">
                                Description
                            </label>
                            <div class="col-md-7">
                                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $project->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Internal note --}}
                        <div class="form-group row">
                            <label for="internal_note" class="col-md-3 col-form-label text-md-right text-muted">
                                Internal note
                            </label>
                            <div class="col-md-7">
                                <textarea name="internal_note" id="internal_note" rows="3" class="form-control">{{ old('internal_note', $project->internal_note) }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Primary manager --}}
                        <div class="form-group row">
                            <label for="primary_manager_id" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Primary manager <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <select name="primary_manager_id" id="primary_manager_id" class="form-control" required>
                                    @foreach ($managers as $m)
                                        <option value="{{ $m->id }}" @selected(old('primary_manager_id', $project->primary_manager_id) == $m->id)>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Secondary manager --}}
                        <div class="form-group row">
                            <label for="secondary_manager_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Secondary manager
                            </label>
                            <div class="col-md-7">
                                <select name="secondary_manager_id" id="secondary_manager_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($managers as $m)
                                        <option value="{{ $m->id }}" @selected(old('secondary_manager_id', $project->secondary_manager_id) == $m->id)>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Legal entity --}}
                        <div class="form-group row">
                            <label for="legal_entity_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Legal entity
                            </label>
                            <div class="col-md-7">
                                <select name="legal_entity_id" id="legal_entity_id" class="form-control">
                                    @foreach ($legalEntities as $le)
                                        <option value="{{ $le->id }}" @selected(old('legal_entity_id', $project->legal_entity_id) == $le->id)>
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
                                            <input type="checkbox" name="tag_ids[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}" class="custom-control-input" @checked(in_array($tag->id, old('tag_ids', $project->tags->pluck('id')->toArray())))>
                                            <label class="custom-control-label font-weight-normal" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Financials --}}
                        <div class="form-group row">
                            <label for="total_to_receive" class="col-md-3 col-form-label text-md-right text-muted">
                                Total to receive (USD)
                            </label>
                            <div class="col-md-3">
                                <input type="number" step="0.01" min="0" name="total_to_receive" id="total_to_receive" class="form-control" value="{{ old('total_to_receive', $project->total_to_receive) }}">
                            </div>
                            <label for="total_to_pay" class="col-md-2 col-form-label text-md-right text-muted">
                                Total to pay (USD)
                            </label>
                            <div class="col-md-2">
                                <input type="number" step="0.01" min="0" name="total_to_pay" id="total_to_pay" class="form-control" value="{{ old('total_to_pay', $project->total_to_pay) }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3">
                        <div class="row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-primary px-4 mr-2">
                                    Update
                                </button>
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-default border">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
