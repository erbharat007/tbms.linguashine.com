@php
    $defaultTax = $defaultTax ?? null;
    // Initial tax type: from old input, else the edited row's tax type, else percentage.
    $initialTaxType = old('tax_type', $defaultTax?->tax?->type ?? 'percentage');
    $initialObject = old('object_type', $defaultTax?->object_type ?? 'client');
    $initialTaxId = old('tax_id', $defaultTax?->tax_id);
    $initialCurrency = old('currency_id', $defaultTax?->currency_id);
@endphp

<div
    x-data="{
        object: @js($initialObject),
        taxType: @js($initialTaxType),
        taxId: @js((string) $initialTaxId),
        taxesByType: @js($taxesByType),
        get availableTaxes() { return this.taxesByType[this.taxType] ?? []; },
        onTaxTypeChange() {
            // Reset tax selection when switching type so a stale id isn't submitted.
            const ids = this.availableTaxes.map(t => String(t.id));
            if (! ids.includes(String(this.taxId))) { this.taxId = ids[0] ?? ''; }
        },
    }"
    x-init="onTaxTypeChange()"
    class="space-y-6"
>
    {{-- Object toggle: Client / Vendor --}}
    <div>
        <x-input-label :value="__('Object')" />
        <input type="hidden" name="object_type" :value="object">
        <div class="mt-1.5 inline-flex rounded-lg border border-gray-300 p-0.5">
            @foreach ($objectTypes as $value => $label)
                <button type="button"
                        x-on:click="object = @js($value)"
                        :class="object === @js($value) ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-800'"
                        class="rounded-md px-4 py-1.5 text-sm font-medium transition">
                    {{ $label }}
                </button>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('object_type')" class="mt-2" />
    </div>

    {{-- Tax type radio: Percentage / Absolute --}}
    <div>
        <x-input-label :value="__('Tax type')" />
        <div class="mt-1.5 flex items-center gap-6">
            @foreach ($taxTypes as $value => $label)
                <label class="flex items-center gap-2">
                    <input type="radio" name="tax_type" value="{{ $value }}"
                           x-model="taxType" x-on:change="onTaxTypeChange()"
                           class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                    <span class="text-sm text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <p class="mt-1 text-xs text-gray-400">Filters the Tax list and, for absolute taxes, shows a currency.</p>
    </div>

    {{-- Tax dropdown (filtered by tax type) --}}
    <div>
        <x-input-label for="tax_id" :value="__('Tax')" />
        <select id="tax_id" name="tax_id" x-model="taxId" required
                class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
            <template x-for="tax in availableTaxes" :key="tax.id">
                <option :value="String(tax.id)" x-text="tax.name" :selected="String(tax.id) === String(taxId)"></option>
            </template>
        </select>
        <p x-show="availableTaxes.length === 0" x-cloak class="mt-1 text-xs text-amber-600">
            No taxes of this type exist yet. Add one under Tax Configuration first.
        </p>
        <x-input-error :messages="$errors->get('tax_id')" class="mt-2" />
    </div>

    {{-- Currency (absolute taxes only) --}}
    <div x-show="taxType === 'absolute'" x-cloak>
        <x-input-label for="currency_id" :value="__('Currency')" />
        <select id="currency_id" name="currency_id"
                class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">— Select —</option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency['id'] }}" @selected((string) $initialCurrency === (string) $currency['id'])>{{ $currency['label'] }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-2" />
    </div>

    {{-- Default value --}}
    <div>
        <x-input-label for="amount" :value="__('Default value')" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="mt-1.5 w-40"
                      :value="old('amount', $defaultTax?->amount)" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update default tax') : __('Add default tax') }}</x-primary-button>
        <a href="{{ route('default-taxes.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
