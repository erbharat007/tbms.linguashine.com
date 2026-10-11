{{--
    Shared legal entity form, mirroring Protemos.
    Expects: $legalEntity, $countries (id => name), $isEdit (bool)
--}}

{{-- Details --}}
<h3 class="h5 mb-3">Details</h3>
<div class="row form-grid">
    <div class="form-group col-12">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $legalEntity->name)" required autofocus />
        <small class="form-text text-muted">Company or brand name for use in invoices.</small>
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="website" :value="__('Website')" />
        <x-text-input id="website" name="website" type="text" :value="old('website', $legalEntity->website)" />
        <x-input-error :messages="$errors->get('website')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input id="phone" name="phone" type="text" :value="old('phone', $legalEntity->phone)" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="tax_number" :value="__('Tax number')" />
        <x-text-input id="tax_number" name="tax_number" type="text" :value="old('tax_number', $legalEntity->tax_number)" />
        <x-input-error :messages="$errors->get('tax_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="vat_number" :value="__('VAT number')" />
        <x-text-input id="vat_number" name="vat_number" type="text" :value="old('vat_number', $legalEntity->vat_number)" />
        <x-input-error :messages="$errors->get('vat_number')" class="mt-1" />
    </div>
</div>

{{-- Address --}}
<hr class="my-4">
<h3 class="h5 mb-3">Address</h3>
<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="country_id" :value="__('Country')" />
        <x-searchable-select name="country_id"
                             :value="old('country_id', $legalEntity->country_id)"
                             :options="$countries"
                             placeholder="Search country" />
        <x-input-error :messages="$errors->get('country_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="region" :value="__('Region / State')" />
        <x-text-input id="region" name="region" type="text" :value="old('region', $legalEntity->region)" />
        <x-input-error :messages="$errors->get('region')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="city" :value="__('City')" />
        <x-text-input id="city" name="city" type="text" :value="old('city', $legalEntity->city)" />
        <x-input-error :messages="$errors->get('city')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="postal_code" :value="__('Postal / Zip code')" />
        <x-text-input id="postal_code" name="postal_code" type="text" :value="old('postal_code', $legalEntity->postal_code)" />
        <x-input-error :messages="$errors->get('postal_code')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="address" :value="__('Address')" />
        <x-text-input id="address" name="address" type="text" :value="old('address', $legalEntity->address)" />
        <x-input-error :messages="$errors->get('address')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="logo" :value="__('Logo')" />
        <div class="d-flex align-items-center">
            @if ($legalEntity->logo_url)
                <img src="{{ $legalEntity->logo_url }}" alt="Current logo" class="rounded border p-1 mr-3" style="height: 38px; width: 38px; object-fit: contain;">
            @endif
            <div class="custom-file flex-fill">
                <input type="file" name="logo" id="logo" accept="image/*" class="custom-file-input">
                <label class="custom-file-label" for="logo">Choose file</label>
            </div>
        </div>
        <small class="form-text text-muted">PNG, JPG, SVG up to 2&nbsp;MB.@if ($legalEntity->logo_url) Uploading a new file replaces the current logo.@endif</small>
        <x-input-error :messages="$errors->get('logo')" class="mt-1" />
    </div>
</div>

{{-- Invoice numbering --}}
<hr class="my-4">
<h3 class="h5 mb-3">Invoice numbering</h3>
<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="first_client_invoice_number" :value="__('First client invoice number')" />
        <x-text-input id="first_client_invoice_number" name="first_client_invoice_number" type="number" min="1"
                      :value="old('first_client_invoice_number', $legalEntity->first_client_invoice_number ?? 1)" required />
        <x-input-error :messages="$errors->get('first_client_invoice_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="first_vendor_invoice_number" :value="__('First vendor invoice number')" />
        <x-text-input id="first_vendor_invoice_number" name="first_vendor_invoice_number" type="number" min="1"
                      :value="old('first_vendor_invoice_number', $legalEntity->first_vendor_invoice_number ?? 1)" required />
        <x-input-error :messages="$errors->get('first_vendor_invoice_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="client_invoice_prefix" :value="__('Code prefix for client invoices')" />
        <x-text-input id="client_invoice_prefix" name="client_invoice_prefix" type="text" :value="old('client_invoice_prefix', $legalEntity->client_invoice_prefix)" />
        <x-input-error :messages="$errors->get('client_invoice_prefix')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="vendor_invoice_prefix" :value="__('Code prefix for vendor invoices')" />
        <x-text-input id="vendor_invoice_prefix" name="vendor_invoice_prefix" type="text" :value="old('vendor_invoice_prefix', $legalEntity->vendor_invoice_prefix)" />
        <x-input-error :messages="$errors->get('vendor_invoice_prefix')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update Legal Entity') : __('Create') }}</button>
    <a href="{{ route('legal-entities.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>

@push('scripts')
<script>
    // Show the chosen filename in the Bootstrap custom-file label
    $(function () {
        $('#logo').on('change', function () {
            var name = this.files && this.files.length ? this.files[0].name : 'Choose file';
            $(this).next('.custom-file-label').text(name);
        });
    });
</script>
@endpush
