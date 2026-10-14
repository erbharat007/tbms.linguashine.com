<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="vendor_id" :value="__('Vendor')" />
        <x-select-input id="vendor_id" name="vendor_id" required>
            <option value="">— Select Vendor —</option>
            @foreach ($vendors as $id => $name)
                <option value="{{ $id }}" @selected((int) old('vendor_id', $payment->vendor_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('vendor_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="payment_date" :value="__('Payment date')" />
        <x-text-input id="payment_date" name="payment_date" type="date" :value="old('payment_date', optional($payment->payment_date)->format('Y-m-d') ?? date('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('payment_date')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="amount" :value="__('Amount')" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" min="0.01" :value="old('amount', $payment->amount)" required placeholder="0.00" />
        <x-input-error :messages="$errors->get('amount')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="currency_id" :value="__('Currency')" />
        <x-select-input id="currency_id" name="currency_id" required>
            <option value="">— Select Currency —</option>
            @foreach ($currencies as $id => $code)
                <option value="{{ $id }}" @selected((int) old('currency_id', $payment->currency_id) === (int) $id)>{{ $code }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="payment_method_id" :value="__('Payment method')" />
        <x-select-input id="payment_method_id" name="payment_method_id">
            <option value="">— Select Method —</option>
            @foreach ($paymentMethods as $id => $name)
                <option value="{{ $id }}" @selected((int) old('payment_method_id', $payment->payment_method_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('payment_method_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="reference_number" :value="__('Reference / Transaction ID')" />
        <x-text-input id="reference_number" name="reference_number" :value="old('reference_number', $payment->reference_number)" placeholder="Bank transfer, PayPal ID, check #..." />
        <x-input-error :messages="$errors->get('reference_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="status" :value="__('Status')" />
        <x-select-input id="status" name="status" required>
            <option value="paid" @selected(old('status', $payment->status ?? 'paid') === 'paid')>Paid</option>
            <option value="cancelled" @selected(old('status', $payment->status) === 'cancelled')>Cancelled</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="notes" :value="__('Notes')" />
        <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $payment->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ ($isEdit ?? false) ? __('Update payment') : __('Record payment') }}</button>
    <a href="{{ route('vendor-payments.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
