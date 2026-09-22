@php($unit = $unit ?? null)

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $unit?->name)" required autofocus />
        <p class="mt-1 text-xs text-gray-400">e.g. Word, Page, Hour, Flat rate.</p>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <label class="flex items-center gap-2">
        <input type="hidden" name="is_word" value="0">
        <input type="checkbox" name="is_word" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               @checked(old('is_word', $unit?->is_word ?? false)) />
        <span class="text-sm text-gray-700">Is word</span>
    </label>
    <p class="-mt-4 ml-6 text-xs text-gray-400">Mark the unit used for word-count based pricing.</p>

    <label class="flex items-center gap-2">
        <input type="hidden" name="is_enabled" value="0">
        <input type="checkbox" name="is_enabled" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               @checked(old('is_enabled', $unit?->is_enabled ?? true)) />
        <span class="text-sm text-gray-700">Enabled</span>
    </label>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update Unit') : __('Create Unit') }}</x-primary-button>
        <a href="{{ route('units.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
