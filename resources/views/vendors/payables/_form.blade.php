<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="vendor_id" :value="__('Vendor')" />
        <x-select-input id="vendor_id" name="vendor_id" required>
            <option value="">— Select Vendor —</option>
            @foreach ($vendors as $id => $name)
                <option value="{{ $id }}" @selected((int) old('vendor_id', $payable->vendor_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('vendor_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="status" :value="__('Status')" />
        <x-select-input id="status" name="status" required>
            <option value="open" @selected(old('status', $payable->status ?? 'open') === 'open')>Open</option>
            <option value="invoiced" @selected(old('status', $payable->status) === 'invoiced')>Invoiced</option>
            <option value="paid" @selected(old('status', $payable->status) === 'paid')>Paid</option>
            <option value="cancelled" @selected(old('status', $payable->status) === 'cancelled')>Cancelled</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="job_code" :value="__('Job code')" />
        <x-text-input id="job_code" name="job_code" :value="old('job_code', $payable->job_code)" placeholder="e.g. JOB-2026-0042" />
        <x-input-error :messages="$errors->get('job_code')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="project_name" :value="__('Project name')" />
        <x-text-input id="project_name" name="project_name" :value="old('project_name', $payable->project_name)" placeholder="Project description or name" />
        <x-input-error :messages="$errors->get('project_name')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="quantity" :value="__('Quantity')" />
        <x-text-input id="quantity" name="quantity" type="number" step="0.0001" min="0" :value="old('quantity', $payable->quantity ?? 1)" required />
        <x-input-error :messages="$errors->get('quantity')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="unit_id" :value="__('Unit')" />
        <x-select-input id="unit_id" name="unit_id">
            <option value="">— Select Unit —</option>
            @foreach ($units as $id => $name)
                <option value="{{ $id }}" @selected((int) old('unit_id', $payable->unit_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('unit_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="rate" :value="__('Rate per unit')" />
        <x-text-input id="rate" name="rate" type="number" step="0.0001" min="0" :value="old('rate', $payable->rate ?? 0)" required />
        <x-input-error :messages="$errors->get('rate')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="currency_id" :value="__('Currency')" />
        <x-select-input id="currency_id" name="currency_id" required>
            <option value="">— Select Currency —</option>
            @foreach ($currencies as $id => $code)
                <option value="{{ $id }}" @selected((int) old('currency_id', $payable->currency_id) === (int) $id)>{{ $code }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="due_date" :value="__('Due date')" />
        <x-text-input id="due_date" name="due_date" type="date" :value="old('due_date', optional($payable->due_date)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('due_date')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="description" :value="__('Description / Notes')" />
        <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $payable->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ ($isEdit ?? false) ? __('Update payable') : __('Create payable') }}</button>
    <a href="{{ route('vendor-payables.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
