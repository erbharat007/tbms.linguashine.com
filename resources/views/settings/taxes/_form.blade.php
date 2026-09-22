@php($tax = $tax ?? null)

<div class="space-y-6">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $tax?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label :value="__('Type')" />
        <div class="mt-1.5 flex items-center gap-6">
            @foreach (\App\Models\Tax::types() as $value => $label)
                <label class="flex items-center gap-2">
                    <input type="radio" name="type" value="{{ $value }}"
                           class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           @checked(old('type', $tax?->type ?? 'percentage') === $value) />
                    <span class="text-sm text-gray-700">{{ $label }}</span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sort_order" :value="__('Sort order inside invoices')" />
        <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999" class="mt-1.5 w-28"
                      :value="old('sort_order', $tax?->sort_order ?? 0)" required />
        <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update tax') : __('Create tax') }}</x-primary-button>
        <a href="{{ route('taxes.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
