<x-app-layout>
    <x-slot name="header">
        <x-page-header title="NEW JOB" subtitle="Create and assign a linguist job for translation, editing, or proofreading.">
            <x-slot name="actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to jobs
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Job Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('jobs.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Project <span class="text-danger">*</span></label>
                                <select name="project_id" id="project_id" class="form-control @error('project_id') is-invalid @enderror" required>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}" @selected(old('project_id', $selectedProject?->id) == $proj->id) data-currency="{{ $proj->currency_id }}" data-manager="{{ $proj->primary_manager_id }}">
                                            {{ $proj->code }} — {{ $proj->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Job Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Translation of English to Spanish" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Service <span class="text-danger">*</span></label>
                                <select name="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                                    <option value="">Select Service</option>
                                    @foreach ($services as $srv)
                                        <option value="{{ $srv->id }}" @selected(old('service_id') == $srv->id)>{{ $srv->name }}</option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Language Pair <span class="text-danger">*</span></label>
                                <select name="language_pair_id" class="form-control @error('language_pair_id') is-invalid @enderror" required>
                                    <option value="">Select Language Pair</option>
                                    @foreach ($languagePairs as $lp)
                                        <option value="{{ $lp->id }}" @selected(old('language_pair_id') == $lp->id)>
                                            {{ $lp->sourceLanguage?->name }} → {{ $lp->targetLanguage?->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('language_pair_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Assigned Vendor</label>
                                <select name="vendor_id" class="form-control @error('vendor_id') is-invalid @enderror">
                                    <option value="">Select Vendor (Leave empty to tender / unassigned)</option>
                                    @foreach ($vendors as $v)
                                        <option value="{{ $v->id }}" @selected(old('vendor_id') == $v->id)>{{ $v->name }}</option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Primary Project Manager</label>
                                <select name="primary_manager_id" id="primary_manager_id" class="form-control @error('primary_manager_id') is-invalid @enderror">
                                    @foreach ($managers as $mgr)
                                        <option value="{{ $mgr->id }}" @selected(old('primary_manager_id', auth()->id()) == $mgr->id)>
                                            {{ $mgr->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('primary_manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card bg-light border-0 mb-3 p-3">
                            <h6 class="font-weight-bold text-dark mb-3">Finances & Volume</h6>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="font-weight-500">Unit</label>
                                    <select name="unit_id" class="form-control">
                                        <option value="">Select Unit</option>
                                        @foreach ($units as $u)
                                            <option value="{{ $u->id }}" @selected(old('unit_id') == $u->id)>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label class="font-weight-500">Work Amount</label>
                                    <input type="number" step="0.01" min="0" name="work_amount" id="work_amount" class="form-control" value="{{ old('work_amount', '0.00') }}" oninput="calculateTotal()">
                                </div>

                                <div class="col-md-3 form-group">
                                    <label class="font-weight-500">Price per unit</label>
                                    <input type="number" step="0.0001" min="0" name="price_per_unit" id="price_per_unit" class="form-control" value="{{ old('price_per_unit', '0.0000') }}" oninput="calculateTotal()">
                                </div>

                                <div class="col-md-3 form-group">
                                    <label class="font-weight-500">Currency</label>
                                    <select name="currency_id" id="currency_id" class="form-control">
                                        @foreach ($currencies as $curr)
                                            <option value="{{ $curr->id }}" @selected(old('currency_id') == $curr->id)>{{ $curr->code }} ({{ $curr->symbol }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center mt-2">
                                <span class="text-muted mr-2 font-weight-bold">Payables:</span>
                                <h4 class="font-weight-bold text-success mb-0" id="payablesDisplay">$ 0.00</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="font-weight-500">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                    <option value="draft" @selected(old('status') === 'draft')>Draft</option>
                                    <option value="offered" @selected(old('status') === 'offered')>Offered</option>
                                    <option value="in_progress" @selected(old('status') === 'in_progress')>In progress</option>
                                    <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                    <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-500">Started At</label>
                                <input type="datetime-local" name="started_at" class="form-control" value="{{ old('started_at') }}">
                            </div>

                            <div class="col-md-4 form-group">
                                <label class="font-weight-500">Deadline At</label>
                                <input type="datetime-local" name="deadline_at" class="form-control" value="{{ old('deadline_at') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Job Checklist</label>
                                <select name="checklist_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($checklists as $chk)
                                        <option value="{{ $chk->id }}" @selected(old('checklist_id') == $chk->id)>
                                            {{ $chk->name }} ({{ $chk->number_of_items }} items)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="font-weight-500">Tags</label>
                                <div class="d-flex flex-wrap pt-1">
                                    @foreach ($tags as $t)
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input type="checkbox" name="tags[]" value="{{ $t->id }}" class="custom-control-input" id="tag_{{ $t->id }}" @checked(in_array($t->id, old('tags', [])))>
                                            <label class="custom-control-label" for="tag_{{ $t->id }}">
                                                <span class="badge" style="background-color: {{ $t->color }}; color: #fff;">{{ $t->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-500">Instructions for Vendor</label>
                            <textarea name="instructions" rows="3" class="form-control" placeholder="Specify terminology preferences, formatting rules, CAT tool link, etc.">{{ old('instructions') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-500">Internal Description / Notes</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Internal manager notes">{{ old('notes') }}</textarea>
                        </div>

                        <div class="border-top pt-3 mt-4 d-flex justify-content-between">
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-1"></i> Create Job
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function calculateTotal() {
            const amount = parseFloat(document.getElementById('work_amount').value) || 0;
            const rate = parseFloat(document.getElementById('price_per_unit').value) || 0;
            const total = (amount * rate).toFixed(2);
            document.getElementById('payablesDisplay').textContent = '$ ' + total;
        }

        document.getElementById('project_id')?.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const currencyId = selected.getAttribute('data-currency');
            const managerId = selected.getAttribute('data-manager');

            if (currencyId) {
                document.getElementById('currency_id').value = currencyId;
            }
            if (managerId) {
                document.getElementById('primary_manager_id').value = managerId;
            }
        });
    </script>
    @endpush
</x-app-layout>
