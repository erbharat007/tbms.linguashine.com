@php($price = $price ?? null)

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="service_id" :value="__('Service')" />
            <x-select-input id="service_id" name="service_id" class="mt-1.5" required>
                <option value="">— Select —</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected(old('service_id', $price?->service_id) == $service->id)>{{ $service->name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="language_pair_id" :value="__('Language pair')" />
            <x-select-input id="language_pair_id" name="language_pair_id" class="mt-1.5" required>
                <option value="">— Select —</option>
                @foreach ($languagePairs as $pair)
                    <option value="{{ $pair->id }}" @selected(old('language_pair_id', $price?->language_pair_id) == $pair->id)>{{ $pair->label }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('language_pair_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="specialization_id" :value="__('Specialization')" />
            <x-select-input id="specialization_id" name="specialization_id" class="mt-1.5" required>
                <option value="">— Select —</option>
                @foreach ($specializations as $spec)
                    <option value="{{ $spec->id }}" @selected(old('specialization_id', $price?->specialization_id) == $spec->id)>{{ $spec->name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('specialization_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="unit_id" :value="__('Unit')" />
            <x-select-input id="unit_id" name="unit_id" class="mt-1.5" required>
                <option value="">— Select —</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" @selected(old('unit_id', $price?->unit_id) == $unit->id)>{{ $unit->name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('unit_id')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="unit_price" :value="__('Price per unit')" />
            <x-text-input id="unit_price" name="unit_price" type="number" step="0.0001" min="0" class="mt-1.5"
                          :value="old('unit_price', $price?->unit_price)" required />
            <x-input-error :messages="$errors->get('unit_price')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="currency_id" :value="__('Currency')" />
            <x-select-input id="currency_id" name="currency_id" class="mt-1.5" required>
                <option value="">— Select —</option>
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" @selected(old('currency_id', $price?->currency_id) == $currency->id)>{{ $currency->code }} - {{ $currency->name }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('currency_id')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="2"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('note', $price?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update price') : __('Create price') }}</x-primary-button>
        <a href="{{ route('client-prices.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
