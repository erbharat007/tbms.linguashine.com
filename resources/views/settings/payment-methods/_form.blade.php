@php
    $paymentMethod = $paymentMethod ?? null;
    $details = old('details', $paymentMethod?->details ?? []);
    $currencies = \App\Models\Currency::where('is_enabled', true)->orderBy('code')->pluck('code');
@endphp

@php
    // Name pairs on one row with the following field for the single-field types
    // (PayPal/Skrill: Name + Email). For bank transfer and custom (which pair their
    // own fields together), Name sits on its own full-width row.
    $nameCol = in_array($type, ['paypal', 'skrill'], true) ? 'col-md-6' : 'col-12';
@endphp

<div class="row form-grid">
    {{-- Name (all types) --}}
    <div class="form-group {{ $nameCol }}">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $paymentMethod?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    @if ($type === 'bank_transfer')
        <div class="form-group col-md-6">
            <x-input-label for="bank_name" :value="__('Bank name')" />
            <x-text-input id="bank_name" name="details[bank_name]" type="text" :value="data_get($details, 'bank_name')" required />
            <x-input-error :messages="$errors->get('details.bank_name')" class="mt-1" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="bank_address" :value="__('Bank address')" />
            <x-text-input id="bank_address" name="details[bank_address]" type="text" :value="data_get($details, 'bank_address')" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="swift" :value="__('SWIFT')" />
            <x-text-input id="swift" name="details[swift]" type="text" :value="data_get($details, 'swift')" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="iban" :value="__('IBAN')" />
            <x-text-input id="iban" name="details[iban]" type="text" :value="data_get($details, 'iban')" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="sort_code" :value="__('Sort code')" />
            <x-text-input id="sort_code" name="details[sort_code]" type="text" :value="data_get($details, 'sort_code')" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="account_number" :value="__('Account number')" />
            <x-text-input id="account_number" name="details[account_number]" type="text" :value="data_get($details, 'account_number')" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="account_currency" :value="__('Account currency')" />
            <x-select-input id="account_currency" name="details[account_currency]">
                <option value="">— Select —</option>
                @foreach ($currencies as $code)
                    <option value="{{ $code }}" @selected(data_get($details, 'account_currency') === $code)>{{ $code }}</option>
                @endforeach
            </x-select-input>
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="beneficiary_name" :value="__('Account beneficiary name')" />
            <x-text-input id="beneficiary_name" name="details[beneficiary_name]" type="text" :value="data_get($details, 'beneficiary_name')" />
        </div>
        <div class="form-group col-12">
            <x-input-label for="beneficiary_address" :value="__('Account beneficiary address')" />
            <x-text-input id="beneficiary_address" name="details[beneficiary_address]" type="text" :value="data_get($details, 'beneficiary_address')" />
        </div>
    @elseif ($type === 'paypal' || $type === 'skrill')
        <div class="form-group col-md-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="details[email]" type="email" :value="data_get($details, 'email')" required />
            <x-input-error :messages="$errors->get('details.email')" class="mt-1" />
        </div>
    @elseif ($type === 'custom')
        <div class="form-group col-md-6">
            <x-input-label for="custom_type" :value="__('Custom type')" />
            <x-text-input id="custom_type" name="details[custom_type]" type="text" :value="data_get($details, 'custom_type')" required />
            <small class="form-text text-muted">e.g. Cash, Cheque, Money order.</small>
            <x-input-error :messages="$errors->get('details.custom_type')" class="mt-1" />
        </div>
        <div class="form-group col-md-6">
            <x-input-label for="custom_details" :value="__('Details')" />
            <textarea id="custom_details" name="details[details]" rows="3" class="form-control" required>{{ data_get($details, 'details') }}</textarea>
            <x-input-error :messages="$errors->get('details.details')" class="mt-1" />
        </div>
    @endif

    {{-- Note (all types) --}}
    <div class="form-group col-12">
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="2" class="form-control">{{ old('note', $paymentMethod?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <input type="hidden" name="is_enabled" value="0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_enabled" name="is_enabled" value="1"
                   @checked(old('is_enabled', $paymentMethod?->is_enabled ?? true)) />
            <label class="custom-control-label" for="is_enabled">Enabled</label>
        </div>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update payment method') : __('Create payment method') }}</button>
    <a href="{{ route('payment-methods.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
