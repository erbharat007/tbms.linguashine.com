<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'UPDATE TEMPLATE: ' . $projectTemplate->name" subtitle="Modify template details and language combinations.">
            <x-slot name="actions">
                <a href="{{ route('project-templates.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to templates
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <form action="{{ route('project-templates.update', $projectTemplate) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        {{-- Client --}}
                        <div class="form-group row">
                            <label for="client_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Client
                            </label>
                            <div class="col-md-7">
                                <select name="client_id" id="client_id" class="form-control">
                                    <option value="">Any client / Universal template</option>
                                    @foreach ($clients as $c)
                                        <option value="{{ $c->id }}" @selected(old('client_id', $projectTemplate->client_id) == $c->id)>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Template Name --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right font-weight-bold">
                                Name <span class="text-danger">*</span>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $projectTemplate->name) }}" required>
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
                                        <option value="{{ $lp->id }}" @selected(in_array($lp->id, old('language_pair_ids', $projectTemplate->languagePairs->pluck('id')->toArray())))>
                                            {{ $lp->sourceLanguage?->name }} -> {{ $lp->targetLanguage?->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                        <option value="{{ $s->id }}" @selected(old('specialization_id', $projectTemplate->specialization_id) == $s->id)>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label for="description" class="col-md-3 col-form-label text-md-right text-muted">
                                Description
                            </label>
                            <div class="col-md-7">
                                <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $projectTemplate->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Internal note --}}
                        <div class="form-group row">
                            <label for="internal_note" class="col-md-3 col-form-label text-md-right text-muted">
                                Internal note
                            </label>
                            <div class="col-md-7">
                                <textarea name="internal_note" id="internal_note" rows="3" class="form-control">{{ old('internal_note', $projectTemplate->internal_note) }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Primary manager --}}
                        <div class="form-group row">
                            <label for="primary_manager_id" class="col-md-3 col-form-label text-md-right text-muted">
                                Primary manager
                            </label>
                            <div class="col-md-7">
                                <select name="primary_manager_id" id="primary_manager_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($managers as $m)
                                        <option value="{{ $m->id }}" @selected(old('primary_manager_id', $projectTemplate->primary_manager_id) == $m->id)>
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
                                        <option value="{{ $m->id }}" @selected(old('secondary_manager_id', $projectTemplate->secondary_manager_id) == $m->id)>
                                            {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top py-3">
                        <div class="row">
                            <div class="col-md-7 offset-md-3">
                                <button type="submit" class="btn btn-primary px-4 mr-2">
                                    Update
                                </button>
                                <a href="{{ route('project-templates.index') }}" class="btn btn-default border">
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
