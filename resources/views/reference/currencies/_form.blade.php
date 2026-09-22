@php($currency = $currency ?? null)

<div class="space-y-6">
    <div>
        <x-input-label for="code" :value="__('Code')" />
        <x-text-input id="code" name="code" type="text" class="mt-1.5 uppercase"
                      :value="old('code', $currency?->code)" required autofocus maxlength="3" />
        <p class="mt-1 text-xs text-gray-400">ISO 4217 code, e.g. USD, EUR, INR.</p>
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $currency?->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="symbol" :value="__('Symbol')" />
        <x-text-input id="symbol" name="symbol" type="text" class="mt-1.5"
                      :value="old('symbol', $currency?->symbol)" />
        <x-input-error :messages="$errors->get('symbol')" class="mt-2" />
    </div>

    <label class="flex items-center gap-2">
        <input type="hidden" name="is_enabled" value="0">
        <input type="checkbox" name="is_enabled" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               @checked(old('is_enabled', $currency?->is_enabled ?? true)) />
        <span class="text-sm text-gray-700">Enabled</span>
    </label>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update Currency') : __('Create Currency') }}</x-primary-button>
        <a href="{{ route('currencies.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
