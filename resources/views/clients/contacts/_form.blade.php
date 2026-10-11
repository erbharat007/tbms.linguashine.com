@php($contact = $contact ?? null)

<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="first_name" :value="__('First name')" />
        <x-text-input id="first_name" name="first_name" type="text" :value="old('first_name', $contact?->first_name)" required autofocus />
        <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="last_name" :value="__('Last name')" />
        <x-text-input id="last_name" name="last_name" type="text" :value="old('last_name', $contact?->last_name)" />
        <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
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
        <x-input-label for="position" :value="__('Position')" />
        <x-text-input id="position" name="position" type="text" :value="old('position', $contact?->position)" />
        <x-input-error :messages="$errors->get('position')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $contact?->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
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

    <div class="form-group col-12">
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="2" class="form-control">{{ old('note', $contact?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <input type="hidden" name="is_active" value="0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                   @checked(old('is_active', $contact?->is_active ?? true)) />
            <label class="custom-control-label" for="is_active">Is active</label>
        </div>

        <input type="hidden" name="is_invoicing" value="0">
        <div class="custom-control custom-checkbox mt-2">
            <input type="checkbox" class="custom-control-input" id="is_invoicing" name="is_invoicing" value="1"
                   @checked(old('is_invoicing', $contact?->is_invoicing ?? false)) />
            <label class="custom-control-label" for="is_invoicing">Is invoicing</label>
        </div>
        <small class="form-text text-muted">Invoicing contacts receive client invoice emails.</small>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update contact person') : __('Create contact person') }}</button>
    <a href="{{ route('clients.contacts.index', $client) }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
