{{--
    Shared legal entity form, mirroring Protemos.
    Expects: $legalEntity, $countries (id => name), $isEdit (bool)
--}}

<div class="space-y-8">
    {{-- Details --}}
    <div>
        <h3 class="mb-4 text-base font-semibold text-gray-900">Details</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1.5"
                              :value="old('name', $legalEntity->name)" required autofocus />
                <p class="mt-1 text-xs text-gray-400">Company or brand name for use in invoices.</p>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="website" :value="__('Website')" />
                <x-text-input id="website" name="website" type="text" class="mt-1.5"
                              :value="old('website', $legalEntity->website)" />
                <x-input-error :messages="$errors->get('website')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1.5"
                              :value="old('phone', $legalEntity->phone)" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tax_number" :value="__('Tax number')" />
                <x-text-input id="tax_number" name="tax_number" type="text" class="mt-1.5"
                              :value="old('tax_number', $legalEntity->tax_number)" />
                <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="vat_number" :value="__('VAT number')" />
                <x-text-input id="vat_number" name="vat_number" type="text" class="mt-1.5"
                              :value="old('vat_number', $legalEntity->vat_number)" />
                <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Address --}}
    <div class="border-t border-gray-100 pt-6">
        <h3 class="mb-4 text-base font-semibold text-gray-900">Address</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="country_id" :value="__('Country')" />
                <div class="mt-1.5">
                    <x-searchable-select name="country_id"
                                         :value="old('country_id', $legalEntity->country_id)"
                                         :options="$countries"
                                         placeholder="Search country" />
                </div>
                <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="region" :value="__('Region / State')" />
                <x-text-input id="region" name="region" type="text" class="mt-1.5"
                              :value="old('region', $legalEntity->region)" />
                <x-input-error :messages="$errors->get('region')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1.5"
                              :value="old('city', $legalEntity->city)" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="postal_code" :value="__('Postal / Zip code')" />
                <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1.5"
                              :value="old('postal_code', $legalEntity->postal_code)" />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" name="address" type="text" class="mt-1.5"
                              :value="old('address', $legalEntity->address)" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Logo --}}
    <div class="border-t border-gray-100 pt-6">
        <h3 class="mb-4 text-base font-semibold text-gray-900">Logo</h3>
        <div class="flex items-center gap-4">
            @if ($legalEntity->logo_url)
                <img src="{{ $legalEntity->logo_url }}" alt="Current logo" class="h-16 w-16 rounded border border-gray-200 object-contain p-1">
            @endif
            <div class="flex-1">
                <input type="file" name="logo" accept="image/*"
                       class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-400">PNG, JPG, SVG up to 2&nbsp;MB.@if ($legalEntity->logo_url) Uploading a new file replaces the current logo.@endif</p>
                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Invoice numbering --}}
    <div class="border-t border-gray-100 pt-6">
        <h3 class="mb-4 text-base font-semibold text-gray-900">Invoice numbering</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="first_client_invoice_number" :value="__('First client invoice number')" />
                <x-text-input id="first_client_invoice_number" name="first_client_invoice_number" type="number" min="1" class="mt-1.5"
                              :value="old('first_client_invoice_number', $legalEntity->first_client_invoice_number ?? 1)" required />
                <x-input-error :messages="$errors->get('first_client_invoice_number')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="first_vendor_invoice_number" :value="__('First vendor invoice number')" />
                <x-text-input id="first_vendor_invoice_number" name="first_vendor_invoice_number" type="number" min="1" class="mt-1.5"
                              :value="old('first_vendor_invoice_number', $legalEntity->first_vendor_invoice_number ?? 1)" required />
                <x-input-error :messages="$errors->get('first_vendor_invoice_number')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="client_invoice_prefix" :value="__('Code prefix for client invoices')" />
                <x-text-input id="client_invoice_prefix" name="client_invoice_prefix" type="text" class="mt-1.5"
                              :value="old('client_invoice_prefix', $legalEntity->client_invoice_prefix)" />
                <x-input-error :messages="$errors->get('client_invoice_prefix')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="vendor_invoice_prefix" :value="__('Code prefix for vendor invoices')" />
                <x-text-input id="vendor_invoice_prefix" name="vendor_invoice_prefix" type="text" class="mt-1.5"
                              :value="old('vendor_invoice_prefix', $legalEntity->vendor_invoice_prefix)" />
                <x-input-error :messages="$errors->get('vendor_invoice_prefix')" class="mt-2" />
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update Legal Entity') : __('Create') }}</x-primary-button>
        <a href="{{ route('legal-entities.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
