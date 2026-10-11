@php($price = $price ?? null)

<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="service_id" :value="__('Service')" />
        <x-select-input id="service_id" name="service_id" required>
            <option value="">— Select —</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected(old('service_id', $price?->service_id) == $service->id)>{{ $service->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('service_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="language_pair_id" :value="__('Language pair')" />
        <x-select-input id="language_pair_id" name="language_pair_id" required>
            <option value="">— Select —</option>
            @foreach ($languagePairs as $pair)
                <option value="{{ $pair->id }}" @selected(old('language_pair_id', $price?->language_pair_id) == $pair->id)>{{ $pair->label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('language_pair_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="specialization_id" :value="__('Specialization')" />
        <x-select-input id="specialization_id" name="specialization_id" required>
            <option value="">— Select —</option>
            @foreach ($specializations as $spec)
                <option value="{{ $spec->id }}" @selected(old('specialization_id', $price?->specialization_id) == $spec->id)>{{ $spec->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('specialization_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="unit_id" :value="__('Unit')" />
        <x-select-input id="unit_id" name="unit_id" required>
            <option value="">— Select —</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected(old('unit_id', $price?->unit_id) == $unit->id)>{{ $unit->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('unit_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="unit_price" :value="__('Price per unit')" />
        <x-text-input id="unit_price" name="unit_price" type="number" step="0.0001" min="0" :value="old('unit_price', $price?->unit_price)" required />
        <x-input-error :messages="$errors->get('unit_price')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="currency_id" :value="__('Currency')" />
        <x-select-input id="currency_id" name="currency_id" required>
            <option value="">— Select —</option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency->id }}" @selected(old('currency_id', $price?->currency_id) == $currency->id)>{{ $currency->code }} - {{ $currency->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="2" class="form-control">{{ old('note', $price?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update price') : __('Create price') }}</button>
    <a href="{{ route('client-prices.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
