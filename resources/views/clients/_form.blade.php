{{--
    Shared client form (General info + Primary user), mirroring Protemos.
    Expects: $client (Client), $contact (ClientContact|null),
             $legalEntities, $statuses, $sources (id => name), $isEdit (bool)
--}}

<div class="space-y-8">
    {{-- General info --}}
    <div>
        <h3 class="mb-4 text-base font-semibold text-gray-900">General info</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1.5"
                              :value="old('name', $client->name)" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="type" :value="__('Type')" />
                <x-select-input id="type" name="type" class="mt-1.5">
                    <option value="company" @selected(old('type', $client->type) === 'company')>Company</option>
                    <option value="individual" @selected(old('type', $client->type) === 'individual')>Individual</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('type')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="legal_entity_id" :value="__('Default legal entity')" />
                <x-select-input id="legal_entity_id" name="legal_entity_id" class="mt-1.5">
                    <option value="">— Select —</option>
                    @foreach ($legalEntities as $id => $name)
                        <option value="{{ $id }}" @selected((int) old('legal_entity_id', $client->legal_entity_id) === (int) $id)>{{ $name }}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('legal_entity_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="client_status_id" :value="__('Status')" />
                <x-select-input id="client_status_id" name="client_status_id" class="mt-1.5">
                    <option value="">— Select —</option>
                    @foreach ($statuses as $id => $name)
                        <option value="{{ $id }}" @selected((int) old('client_status_id', $client->client_status_id) === (int) $id)>{{ $name }}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('client_status_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="client_source_id" :value="__('Source')" />
                <x-select-input id="client_source_id" name="client_source_id" class="mt-1.5">
                    <option value="">— Select —</option>
                    @foreach ($sources as $id => $name)
                        <option value="{{ $id }}" @selected((int) old('client_source_id', $client->client_source_id) === (int) $id)>{{ $name }}</option>
                    @endforeach
                </x-select-input>
                <x-input-error :messages="$errors->get('client_source_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="country_id" :value="__('Country')" />
                <div class="mt-1.5">
                    <x-searchable-select name="country_id"
                                         :value="old('country_id', $client->country_id)"
                                         :options="$countries"
                                         placeholder="Search country" />
                </div>
                <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="region" :value="__('Region / State')" />
                <x-text-input id="region" name="region" type="text" class="mt-1.5"
                              :value="old('region', $client->region)" />
                <x-input-error :messages="$errors->get('region')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="postal_code" :value="__('Postal / Zip code')" />
                <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1.5"
                              :value="old('postal_code', $client->postal_code)" />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1.5"
                              :value="old('city', $client->city)" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" name="address" type="text" class="mt-1.5"
                              :value="old('address', $client->address)" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tax_number" :value="__('Tax number')" />
                <x-text-input id="tax_number" name="tax_number" type="text" class="mt-1.5"
                              :value="old('tax_number', $client->tax_number)" />
                <x-input-error :messages="$errors->get('tax_number')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="vat_number" :value="__('VAT number')" />
                <x-text-input id="vat_number" name="vat_number" type="text" class="mt-1.5"
                              :value="old('vat_number', $client->vat_number)" />
                <x-input-error :messages="$errors->get('vat_number')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="website" :value="__('Website')" />
                <x-text-input id="website" name="website" type="text" class="mt-1.5"
                              :value="old('website', $client->website)" />
                <x-input-error :messages="$errors->get('website')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <x-input-label for="note" :value="__('Note')" />
                <textarea id="note" name="note" rows="3"
                          class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('note', $client->note) }}</textarea>
                <x-input-error :messages="$errors->get('note')" class="mt-2" />
            </div>
        </div>
    </div>

    {{-- Primary user --}}
    <div class="border-t border-gray-100 pt-6">
        <h3 class="mb-4 text-base font-semibold text-gray-900">Primary user</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <x-input-label for="first_name" :value="__('First name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1.5"
                              :value="old('first_name', $contact?->first_name)" required />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1.5"
                              :value="old('last_name', $contact?->last_name)" required />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1.5"
                              :value="old('email', $contact?->email)" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="timezone_id" :value="__('Time zone')" />
                <div class="mt-1.5">
                    <x-searchable-select name="timezone_id"
                                         :value="old('timezone_id', $contact?->timezone_id)"
                                         :options="$timezones"
                                         placeholder="Search time zone" />
                </div>
                <x-input-error :messages="$errors->get('timezone_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <x-select-input id="gender" name="gender" class="mt-1.5">
                    <option value="">— Select —</option>
                    <option value="male" @selected(old('gender', $contact?->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $contact?->gender) === 'female')>Female</option>
                </x-select-input>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1.5"
                              :value="old('phone', $contact?->phone)" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="skype" :value="__('Skype')" />
                <x-text-input id="skype" name="skype" type="text" class="mt-1.5"
                              :value="old('skype', $contact?->skype)" />
                <x-input-error :messages="$errors->get('skype')" class="mt-2" />
            </div>

            <div class="sm:col-span-2">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="can_login" value="0">
                    <input type="checkbox" name="can_login" value="1"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           @checked(old('can_login', $contact?->can_login ?? false)) />
                    <span class="text-sm text-gray-700">Can log in</span>
                </label>
                <p class="mt-1 text-xs text-gray-400">If checked, the system will send an invitation email with the registration link.</p>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update Client') : __('Create') }}</x-primary-button>
        <a href="{{ route('clients.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
