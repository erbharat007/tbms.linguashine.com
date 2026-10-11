@php
    $defaultTax = $defaultTax ?? null;
    $initialTaxType = old('tax_type', $defaultTax?->tax?->type ?? 'percentage');
    $initialObject = old('object_type', $defaultTax?->object_type ?? 'client');
    $initialTaxId = (string) old('tax_id', $defaultTax?->tax_id);
    $initialCurrency = (string) old('currency_id', $defaultTax?->currency_id);
@endphp

<div class="row form-grid">
    {{-- Object: Client / Vendor --}}
    <div class="form-group col-md-6">
        <x-input-label :value="__('Object')" />
        <x-select-input id="object_type" name="object_type">
            @foreach ($objectTypes as $value => $label)
                <option value="{{ $value }}" @selected($initialObject === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('object_type')" class="mt-1" />
    </div>

    {{-- Tax type: Percentage / Absolute --}}
    <div class="form-group col-md-6">
        <x-input-label :value="__('Tax type')" />
        <div class="d-flex align-items-center" style="gap: 1.5rem; height: calc(1.5em + 0.5rem + 2px);">
            @foreach ($taxTypes as $value => $label)
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input tax-type-radio" id="tax_type_{{ $value }}" name="tax_type" value="{{ $value }}"
                           @checked($initialTaxType === $value) />
                    <label class="custom-control-label" for="tax_type_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>
        <small class="form-text text-muted">Filters the Tax list and, for absolute taxes, shows a currency.</small>
    </div>

    {{-- Tax dropdown (filtered by tax type) --}}
    <div class="form-group col-md-6">
        <x-input-label for="tax_id" :value="__('Tax')" />
        <select id="tax_id" name="tax_id" class="form-control" required></select>
        <small id="tax_empty_hint" class="form-text text-warning d-none">
            No taxes of this type exist yet. Add one under Tax Configuration first.
        </small>
        <x-input-error :messages="$errors->get('tax_id')" class="mt-1" />
    </div>

    {{-- Currency (absolute only) --}}
    <div class="form-group col-md-6" id="currency_wrapper">
        <x-input-label for="currency_id" :value="__('Currency')" />
        <x-select-input id="currency_id" name="currency_id">
            <option value="">— Select —</option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency['id'] }}" @selected($initialCurrency === (string) $currency['id'])>{{ $currency['label'] }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    {{-- Default value --}}
    <div class="form-group col-md-6">
        <x-input-label for="amount" :value="__('Default value')" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" min="0"
                      :value="old('amount', $defaultTax?->amount)" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update default tax') : __('Add default tax') }}</button>
    <a href="{{ route('default-taxes.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>

@push('scripts')
<script>
    (function () {
        var taxesByType = @json($taxesByType);
        var selectedTaxId = @json($initialTaxId);
        var $taxSelect = $('#tax_id');
        var $hint = $('#tax_empty_hint');
        var $currencyWrapper = $('#currency_wrapper');

        function currentType() {
            return $('.tax-type-radio:checked').val() || 'percentage';
        }

        function rebuildTaxOptions() {
            var type = currentType();
            var list = taxesByType[type] || [];
            $taxSelect.empty();
            list.forEach(function (tax) {
                var opt = $('<option>').val(String(tax.id)).text(tax.name);
                if (String(tax.id) === String(selectedTaxId)) opt.prop('selected', true);
                $taxSelect.append(opt);
            });
            $hint.toggleClass('d-none', list.length !== 0);
            // Keep selection valid; if the stored id isn't in this type, take the first.
            if (list.length && !list.some(function (t) { return String(t.id) === String($taxSelect.val()); })) {
                $taxSelect.val(String(list[0].id));
            }
            selectedTaxId = $taxSelect.val();
            $currencyWrapper.toggle(type === 'absolute');
        }

        $('.tax-type-radio').on('change', rebuildTaxOptions);
        rebuildTaxOptions();
    })();
</script>
@endpush
