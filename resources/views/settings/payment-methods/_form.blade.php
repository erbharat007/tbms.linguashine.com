@php
    $paymentMethod = $paymentMethod ?? null;
    $details = old('details', $paymentMethod?->details ?? []);
    $currencies = \App\Models\Currency::where('is_enabled', true)->orderBy('code')->pluck('code');
@endphp

<div class="space-y-6">
    {{-- Name (all types) --}}
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $paymentMethod?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    @if ($type === 'bank_transfer')
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-input-label for="bank_name" :value="__('Bank name')" />
                <x-text-input id="bank_name" name="details[bank_name]" type="text" class="mt-1.5"
                              :value="data_get($details, 'bank_name')" required />
                <x-input-error :messages="$errors->get('details.bank_name')" class="mt-2" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="bank_address" :value="__('Bank address')" />
                <x-text-input id="bank_address" name="details[bank_address]" type="text" class="mt-1.5"
                              :value="data_get($details, 'bank_address')" />
            </div>
            <div>
                <x-input-label for="swift" :value="__('SWIFT')" />
                <x-text-input id="swift" name="details[swift]" type="text" class="mt-1.5"
                              :value="data_get($details, 'swift')" />
            </div>
            <div>
                <x-input-label for="iban" :value="__('IBAN')" />
                <x-text-input id="iban" name="details[iban]" type="text" class="mt-1.5"
                              :value="data_get($details, 'iban')" />
            </div>
            <div>
                <x-input-label for="sort_code" :value="__('Sort code')" />
                <x-text-input id="sort_code" name="details[sort_code]" type="text" class="mt-1.5"
                              :value="data_get($details, 'sort_code')" />
            </div>
            <div>
                <x-input-label for="account_number" :value="__('Account number')" />
                <x-text-input id="account_number" name="details[account_number]" type="text" class="mt-1.5"
                              :value="data_get($details, 'account_number')" />
            </div>
            <div>
                <x-input-label for="account_currency" :value="__('Account currency')" />
                <x-select-input id="account_currency" name="details[account_currency]" class="mt-1.5">
                    <option value="">— Select —</option>
                    @foreach ($currencies as $code)
                        <option value="{{ $code }}" @selected(data_get($details, 'account_currency') === $code)>{{ $code }}</option>
                    @endforeach
                </x-select-input>
            </div>
            <div>
                <x-input-label for="beneficiary_name" :value="__('Account beneficiary name')" />
                <x-text-input id="beneficiary_name" name="details[beneficiary_name]" type="text" class="mt-1.5"
                              :value="data_get($details, 'beneficiary_name')" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="beneficiary_address" :value="__('Account beneficiary address')" />
                <x-text-input id="beneficiary_address" name="details[beneficiary_address]" type="text" class="mt-1.5"
                              :value="data_get($details, 'beneficiary_address')" />
            </div>
        </div>
    @elseif ($type === 'paypal' || $type === 'skrill')
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="details[email]" type="email" class="mt-1.5"
                          :value="data_get($details, 'email')" required />
            <x-input-error :messages="$errors->get('details.email')" class="mt-2" />
        </div>
    @elseif ($type === 'custom')
        <div>
            <x-input-label for="custom_type" :value="__('Custom type')" />
            <x-text-input id="custom_type" name="details[custom_type]" type="text" class="mt-1.5"
                          :value="data_get($details, 'custom_type')" required />
            <p class="mt-1 text-xs text-gray-400">e.g. Cash, Cheque, Money order.</p>
            <x-input-error :messages="$errors->get('details.custom_type')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="custom_details" :value="__('Details')" />
            <textarea id="custom_details" name="details[details]" rows="3"
                      class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500"
                      required>{{ data_get($details, 'details') }}</textarea>
            <x-input-error :messages="$errors->get('details.details')" class="mt-2" />
        </div>
    @endif

    {{-- Note (all types) --}}
    <div>
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="2"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('note', $paymentMethod?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-2" />
    </div>

    <label class="flex items-center gap-2">
        <input type="hidden" name="is_enabled" value="0">
        <input type="checkbox" name="is_enabled" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               @checked(old('is_enabled', $paymentMethod?->is_enabled ?? true)) />
        <span class="text-sm text-gray-700">Enabled</span>
    </label>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update payment method') : __('Create payment method') }}</x-primary-button>
        <a href="{{ route('payment-methods.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
