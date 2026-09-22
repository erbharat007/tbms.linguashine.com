@php($language = $language ?? null)

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $language?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="code" :value="__('Code')" />
        <x-text-input id="code" name="code" type="text" class="mt-1.5"
                      :value="old('code', $language?->code)" required />
        <p class="mt-1 text-xs text-gray-400">ISO 639-1 code, e.g. en, fr, de.</p>
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update Language') : __('Create Language') }}</x-primary-button>
        <a href="{{ route('languages.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
