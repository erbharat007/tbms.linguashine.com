{{--
    Shared client form (General info + Primary user), mirroring Protemos.
    Expects: $client (Client), $contact (ClientContact|null),
             $legalEntities, $statuses, $sources (id => name), $isEdit (bool)
--}}

{{-- General info --}}
<h3 class="h5 mb-3">General info</h3>
<div class="row form-grid">
    <div class="form-group col-12">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $client->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="type" :value="__('Type')" />
        <x-select-input id="type" name="type">
            <option value="company" @selected(old('type', $client->type) === 'company')>Company</option>
            <option value="individual" @selected(old('type', $client->type) === 'individual')>Individual</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('type')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="legal_entity_id" :value="__('Default legal entity')" />
        <x-select-input id="legal_entity_id" name="legal_entity_id">
            <option value="">— Select —</option>
            @foreach ($legalEntities as $id => $name)
                <option value="{{ $id }}" @selected((int) old('legal_entity_id', $client->legal_entity_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('legal_entity_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="client_status_id" :value="__('Status')" />
        <x-select-input id="client_status_id" name="client_status_id">
            <option value="">— Select —</option>
            @foreach ($statuses as $id => $name)
                <option value="{{ $id }}" @selected((int) old('client_status_id', $client->client_status_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('client_status_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="client_source_id" :value="__('Source')" />
        <x-select-input id="client_source_id" name="client_source_id">
            <option value="">— Select —</option>
            @foreach ($sources as $id => $name)
                <option value="{{ $id }}" @selected((int) old('client_source_id', $client->client_source_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('client_source_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="country_id" :value="__('Country')" />
        <x-searchable-select name="country_id"
                             :value="old('country_id', $client->country_id)"
                             :options="$countries"
                             placeholder="Search country" />
        <x-input-error :messages="$errors->get('country_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="region" :value="__('Region / State')" />
        <x-text-input id="region" name="region" type="text" :value="old('region', $client->region)" />
        <x-input-error :messages="$errors->get('region')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="postal_code" :value="__('Postal / Zip code')" />
        <x-text-input id="postal_code" name="postal_code" type="text" :value="old('postal_code', $client->postal_code)" />
        <x-input-error :messages="$errors->get('postal_code')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="city" :value="__('City')" />
        <x-text-input id="city" name="city" type="text" :value="old('city', $client->city)" />
        <x-input-error :messages="$errors->get('city')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="tax_number" :value="__('Tax number')" />
        <x-text-input id="tax_number" name="tax_number" type="text" :value="old('tax_number', $client->tax_number)" />
        <x-input-error :messages="$errors->get('tax_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="vat_number" :value="__('VAT number')" />
        <x-text-input id="vat_number" name="vat_number" type="text" :value="old('vat_number', $client->vat_number)" />
        <x-input-error :messages="$errors->get('vat_number')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="website" :value="__('Website')" />
        <x-text-input id="website" name="website" type="text" :value="old('website', $client->website)" />
        <x-input-error :messages="$errors->get('website')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="address" :value="__('Address')" />
        <x-text-input id="address" name="address" type="text" :value="old('address', $client->address)" />
        <x-input-error :messages="$errors->get('address')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="3" class="form-control">{{ old('note', $client->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-1" />
    </div>
</div>

{{-- Primary user --}}
<hr class="my-4">
<h3 class="h5 mb-3">Primary user</h3>
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
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $contact?->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="timezone_id" :value="__('Time zone')" />
        <x-searchable-select name="timezone_id"
                             :value="old('timezone_id', $contact?->timezone_id)"
                             :options="$timezones"
                             placeholder="Search time zone" />
        <x-input-error :messages="$errors->get('timezone_id')" class="mt-1" />
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
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input id="phone" name="phone" type="text" :value="old('phone', $contact?->phone)" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="skype" :value="__('Skype')" />
        <x-text-input id="skype" name="skype" type="text" :value="old('skype', $contact?->skype)" />
        <x-input-error :messages="$errors->get('skype')" class="mt-1" />
    </div>

    {{-- Keep posting can_login=0 so the field is always submitted; the visible toggle is
         hidden for now and can be re-enabled later. --}}
    <input type="hidden" name="can_login" value="0">
    {{--
    <div class="form-group col-12">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="can_login" name="can_login" value="1"
                   @checked(old('can_login', $contact?->can_login ?? false)) />
            <label class="custom-control-label" for="can_login">Can log in</label>
        </div>
        <small class="form-text text-muted">If checked, the system will send an invitation email with the registration link.</small>
    </div>
    --}}
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update Client') : __('Create') }}</button>
    <a href="{{ route('clients.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
