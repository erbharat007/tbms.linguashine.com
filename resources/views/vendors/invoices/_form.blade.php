<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="invoice_number" :value="__('Invoice number')" />
        <x-text-input id="invoice_number" name="invoice_number" :value="old('invoice_number', $invoice->invoice_number)" required placeholder="e.g. INV-2026-0001" />
        <x-input-error :messages="$errors->get('invoice_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="vendor_id" :value="__('Vendor')" />
        <x-select-input id="vendor_id" name="vendor_id" required>
            <option value="">— Select Vendor —</option>
            @foreach ($vendors as $id => $name)
                <option value="{{ $id }}" @selected((int) old('vendor_id', $invoice->vendor_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('vendor_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="invoice_date" :value="__('Invoice date')" />
        <x-text-input id="invoice_date" name="invoice_date" type="date" :value="old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d') ?? date('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('invoice_date')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="due_date" :value="__('Due date')" />
        <x-text-input id="due_date" name="due_date" type="date" :value="old('due_date', optional($invoice->due_date)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('due_date')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="status" :value="__('Status')" />
        <x-select-input id="status" name="status" required>
            <option value="draft" @selected(old('status', $invoice->status ?? 'received') === 'draft')>Draft</option>
            <option value="received" @selected(old('status', $invoice->status ?? 'received') === 'received')>Received</option>
            <option value="partially_paid" @selected(old('status', $invoice->status) === 'partially_paid')>Partially paid</option>
            <option value="paid" @selected(old('status', $invoice->status) === 'paid')>Paid</option>
            <option value="overdue" @selected(old('status', $invoice->status) === 'overdue')>Overdue</option>
            <option value="cancelled" @selected(old('status', $invoice->status) === 'cancelled')>Cancelled</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="currency_id" :value="__('Currency')" />
        <x-select-input id="currency_id" name="currency_id" required>
            <option value="">— Select Currency —</option>
            @foreach ($currencies as $id => $code)
                <option value="{{ $id }}" @selected((int) old('currency_id', $invoice->currency_id) === (int) $id)>{{ $code }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="subtotal" :value="__('Subtotal')" />
        <x-text-input id="subtotal" name="subtotal" type="number" step="0.01" min="0" :value="old('subtotal', $invoice->subtotal ?? 0)" required />
        <x-input-error :messages="$errors->get('subtotal')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="tax" :value="__('Tax')" />
        <x-text-input id="tax" name="tax" type="number" step="0.01" min="0" :value="old('tax', $invoice->tax ?? 0)" />
        <x-input-error :messages="$errors->get('tax')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="discount" :value="__('Discount')" />
        <x-text-input id="discount" name="discount" type="number" step="0.01" min="0" :value="old('discount', $invoice->discount ?? 0)" />
        <x-input-error :messages="$errors->get('discount')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="paid_amount" :value="__('Paid amount')" />
        <x-text-input id="paid_amount" name="paid_amount" type="number" step="0.01" min="0" :value="old('paid_amount', $invoice->paid_amount ?? 0)" />
        <x-input-error :messages="$errors->get('paid_amount')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="notes" :value="__('Notes')" />
        <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $invoice->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ ($isEdit ?? false) ? __('Update invoice') : __('Create invoice') }}</button>
    <a href="{{ route('vendor-invoices.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
