<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="vendor_id" :value="__('Vendor')" />
        <x-select-input id="vendor_id" name="vendor_id" required>
            <option value="">— Select Vendor —</option>
            @foreach ($vendors as $id => $name)
                <option value="{{ $id }}" @selected((int) old('vendor_id', $contact->vendor_id) === (int) $id)>{{ $name }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('vendor_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="designation" :value="__('Designation / Role')" />
        <x-text-input id="designation" name="designation" type="text" :value="old('designation', $contact->designation)" placeholder="e.g. Lead Translator, Project Coordinator" />
        <x-input-error :messages="$errors->get('designation')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="first_name" :value="__('First name')" />
        <x-text-input id="first_name" name="first_name" type="text" :value="old('first_name', $contact->first_name)" required autofocus />
        <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="last_name" :value="__('Last name')" />
        <x-text-input id="last_name" name="last_name" type="text" :value="old('last_name', $contact->last_name)" required />
        <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="gender" :value="__('Gender')" />
        <x-select-input id="gender" name="gender">
            <option value="">— Select —</option>
            <option value="male" @selected(old('gender', $contact->gender) === 'male')>Male</option>
            <option value="female" @selected(old('gender', $contact->gender) === 'female')>Female</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('gender')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $contact->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input id="phone" name="phone" type="text" :value="old('phone', $contact->phone)" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="skype" :value="__('Skype / Instant messenger')" />
        <x-text-input id="skype" name="skype" type="text" :value="old('skype', $contact->skype)" />
        <x-input-error :messages="$errors->get('skype')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="timezone_id" :value="__('Time zone')" />
        <x-select-input id="timezone_id" name="timezone_id">
            <option value="">— Select —</option>
            @foreach ($timezones as $id => $label)
                <option value="{{ $id }}" @selected((int) old('timezone_id', $contact->timezone_id) === (int) $id)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('timezone_id')" class="mt-1" />
    </div>

    <div class="form-group col-md-6 d-flex align-items-center mt-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_primary" name="is_primary" value="1" @checked(old('is_primary', $contact->is_primary))>
            <label class="custom-control-label" for="is_primary">Set as primary contact person</label>
        </div>
    </div>
</div>
