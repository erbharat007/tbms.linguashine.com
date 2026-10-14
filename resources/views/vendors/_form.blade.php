{{--
    Shared vendor form (General info + Primary contact person), mirroring Protemos.
    Expects: $vendor (Vendor), $contact (VendorContact|null),
             $legalEntities, $countries, $currencies, $timezones, $managers, $tags, $selectedTags (array), $isEdit (bool)
--}}

{{-- General info --}}
<h3 class="h5 mb-3">General info</h3>
<div class="row form-grid">
    <div class="form-group col-md-8">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $vendor->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="type" :value="__('Type')" />
        <x-select-input id="type" name="type">
            <option value="individual" @selected(old('type', $vendor->type) === 'individual')>Individual / Freelancer</option>
            <option value="company" @selected(old('type', $vendor->type) === 'company')>Company / Agency</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('type')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="status" :value="__('Status')" />
        <x-select-input id="status" name="status">
            <option value="active" @selected(old('status', $vendor->status) === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $vendor->status) === 'inactive')>Inactive</option>
            <option value="pending" @selected(old('status', $vendor->status) === 'pending')>Pending</option>
            <option value="blocked" @selected(old('status', $vendor->status) === 'blocked')>Blocked</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="legal_entity_id" :value="__('Default legal entity')" />
        <x-select-input id="legal_entity_id" name="legal_entity_id">
            <option value="">— Select —</option>
            @foreach ($legalEntities as $id => $name)
                <option value="{{ $id }}" @selected((int) old('legal_entity_id', $vendor->legal_entity_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('legal_entity_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="country_id" :value="__('Country')" />
        <x-searchable-select name="country_id"
                             :value="old('country_id', $vendor->country_id)"
                             :options="$countries"
                             placeholder="Search country" />
        <x-input-error :messages="$errors->get('country_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="region" :value="__('Region / State')" />
        <x-text-input id="region" name="region" type="text" :value="old('region', $vendor->region)" />
        <x-input-error :messages="$errors->get('region')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="city" :value="__('City')" />
        <x-text-input id="city" name="city" type="text" :value="old('city', $vendor->city)" />
        <x-input-error :messages="$errors->get('city')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="postal_code" :value="__('Postal / Zip code')" />
        <x-text-input id="postal_code" name="postal_code" type="text" :value="old('postal_code', $vendor->postal_code)" />
        <x-input-error :messages="$errors->get('postal_code')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="address" :value="__('Address')" />
        <x-text-input id="address" name="address" type="text" :value="old('address', $vendor->address)" />
        <x-input-error :messages="$errors->get('address')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="tax_number" :value="__('Tax number')" />
        <x-text-input id="tax_number" name="tax_number" type="text" :value="old('tax_number', $vendor->tax_number)" />
        <x-input-error :messages="$errors->get('tax_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="vat_number" :value="__('VAT number')" />
        <x-text-input id="vat_number" name="vat_number" type="text" :value="old('vat_number', $vendor->vat_number)" />
        <x-input-error :messages="$errors->get('vat_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="currency_id" :value="__('Default currency')" />
        <x-select-input id="currency_id" name="currency_id">
            <option value="">— Select —</option>
            @foreach ($currencies as $id => $label)
                <option value="{{ $id }}" @selected((int) old('currency_id', $vendor->currency_id) === (int) $id)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('currency_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="payment_terms" :value="__('Payment terms (days)')" />
        <x-text-input id="payment_terms" name="payment_terms" type="number" min="0" :value="old('payment_terms', $vendor->payment_terms ?? 30)" />
        <x-input-error :messages="$errors->get('payment_terms')" class="mt-1" />
    </div>

    <div class="form-group col-md-4">
        <x-input-label for="manager_id" :value="__('Account manager')" />
        <x-select-input id="manager_id" name="manager_id">
            <option value="">— Select —</option>
            @foreach ($managers as $id => $name)
                <option value="{{ $id }}" @selected((int) old('manager_id', $vendor->manager_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('manager_id')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="website" :value="__('Website / Portfolio')" />
        <x-text-input id="website" name="website" type="url" :value="old('website', $vendor->website)" placeholder="https://..." />
        <x-input-error :messages="$errors->get('website')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label :value="__('Tags')" />
        <div class="d-flex flex-wrap" style="gap: 10px;">
            @foreach ($tags as $tag)
                <div class="custom-control custom-checkbox mr-3">
                    <input type="checkbox" class="custom-control-input" id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                           @checked(in_array($tag->id, old('tags', $selectedTags ?? [])))>
                    <label class="custom-control-label" for="tag_{{ $tag->id }}">
                        <span class="badge badge-{{ $tag->color }}">{{ $tag->name }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="form-group col-12">
        <x-input-label for="note" :value="__('Note / Special instructions')" />
        <textarea id="note" name="note" class="form-control" rows="3">{{ old('note', $vendor->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-1" />
    </div>
</div>

<hr class="my-4">

{{-- Primary user / contact person --}}
<h3 class="h5 mb-3">Primary contact person</h3>
<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="first_name" :value="__('First name')" />
        <x-text-input id="first_name" name="first_name" type="text" :value="old('first_name', $contact?->first_name)" required />
        <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="last_name" :value="__('Last name')" />
        <x-text-input id="last_name" name="last_name" type="text" :value="old('last_name', $contact?->last_name)" required />
        <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="designation" :value="__('Designation / Role')" />
        <x-text-input id="designation" name="designation" type="text" :value="old('designation', $contact?->designation)" placeholder="e.g. Lead Translator, Vendor Manager" />
        <x-input-error :messages="$errors->get('designation')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="gender" :value="__('Gender')" />
        <x-select-input id="gender" name="gender">
            <option value="">— Select —</option>
            <option value="male" @selected(old('gender', $contact?->gender) === 'male')>Male</option>
            <option value="female" @selected(old('gender', $contact?->gender) === 'female')>Female</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('gender')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $contact?->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="timezone_id" :value="__('Time zone')" />
        <x-select-input id="timezone_id" name="timezone_id">
            <option value="">— Select —</option>
            @foreach ($timezones as $id => $label)
                <option value="{{ $id }}" @selected((int) old('timezone_id', $contact?->timezone_id) === (int) $id)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('timezone_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input id="phone" name="phone" type="text" :value="old('phone', $contact?->phone)" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="skype" :value="__('Skype / Instant messenger')" />
        <x-text-input id="skype" name="skype" type="text" :value="old('skype', $contact?->skype)" />
        <x-input-error :messages="$errors->get('skype')" class="mt-1" />
    </div>
</div>
