<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="vendor_id" :value="__('Vendor')" />
        <x-select-input id="vendor_id" name="vendor_id">
            <option value="">— All vendors (Default baseline) —</option>
            @foreach ($vendors as $id => $name)
                <option value="{{ $id }}" @selected((int) old('vendor_id', $price?->vendor_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('vendor_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="service_id" :value="__('Service')" />
        <x-select-input id="service_id" name="service_id" required>
            <option value="">— Select Service —</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" @selected((int) old('service_id', $price?->service_id) === (int) $service->id)>{{ $service->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('service_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="language_pair_id" :value="__('Language pair')" />
        <x-select-input id="language_pair_id" name="language_pair_id" required>
            <option value="">— Select Language Pair —</option>
            @foreach ($languagePairs as $pair)
                <option value="{{ $pair->id }}" @selected((int) old('language_pair_id', $price?->language_pair_id) === (int) $pair->id)>{{ $pair->label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('language_pair_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="specialization_id" :value="__('Specialization')" />
        <x-select-input id="specialization_id" name="specialization_id" required>
            <option value="">— Select Specialization —</option>
            @foreach ($specializations as $spec)
                <option value="{{ $spec->id }}" @selected((int) old('specialization_id', $price?->specialization_id) === (int) $spec->id)>{{ $spec->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('specialization_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="unit_id" :value="__('Unit')" />
        <x-select-input id="unit_id" name="unit_id" required>
            <option value="">— Select Unit —</option>
            @foreach ($units as $u)
                <option value="{{ $u->id }}" @selected((int) old('unit_id', $price?->unit_id) === (int) $u->id)>{{ $u->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('unit_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="unit_price" :value="__('Price per unit')" />
        <x-text-input id="unit_price" name="unit_price" type="number" step="0.0001" min="0" :value="old('unit_price', $price?->unit_price)" required placeholder="0.0000" />
        <x-input-error :messages="$errors->get('unit_price')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="currency_id" :value="__('Currency')" />
        <x-select-input id="currency_id" name="currency_id" required>
            <option value="">— Select Currency —</option>
            @foreach ($currencies as $curr)
                <option value="{{ $curr->id }}" @selected((int) old('currency_id', $price?->currency_id) === (int) $curr->id)>{{ $curr->code }} - {{ $curr->name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" class="form-control" rows="2">{{ old('note', $price?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ ($isEdit ?? false) ? __('Update price') : __('Create price') }}</button>
    <a href="{{ route('vendor-prices.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>

