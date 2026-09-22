@php($contact = $contact ?? null)

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1.5"
                          :value="old('first_name', $contact?->first_name)" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1.5"
                          :value="old('last_name', $contact?->last_name)" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
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
            <x-input-label for="position" :value="__('Position')" />
            <x-text-input id="position" name="position" type="text" class="mt-1.5"
                          :value="old('position', $contact?->position)" />
            <x-input-error :messages="$errors->get('position')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5"
                          :value="old('email', $contact?->email)" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
    </div>

    <div>
        <x-input-label for="note" :value="__('Note')" />
        <textarea id="note" name="note" rows="2"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('note', $contact?->note) }}</textarea>
        <x-input-error :messages="$errors->get('note')" class="mt-2" />
    </div>

    <div class="flex flex-col gap-3">
        <label class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                   @checked(old('is_active', $contact?->is_active ?? true)) />
            <span class="text-sm text-gray-700">Is active</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="hidden" name="is_invoicing" value="0">
            <input type="checkbox" name="is_invoicing" value="1"
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                   @checked(old('is_invoicing', $contact?->is_invoicing ?? false)) />
            <span class="text-sm text-gray-700">Is invoicing</span>
        </label>
        <p class="-mt-1 ml-6 text-xs text-gray-400">Invoicing contacts receive client invoice emails.</p>
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update contact person') : __('Create contact person') }}</x-primary-button>
        <a href="{{ route('clients.contacts.index', $client) }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
