@props(['name', 'value' => '', 'options' => [], 'placeholder' => 'Search...', 'disabled' => false])

{{--
    A searchable dropdown powered by Alpine. Works like a native select but
    lets the user type to filter the options. Stores the selected value in a
    hidden input so it posts like a normal form field.

    Usage:
    <x-searchable-select name="country" :value="old('country', $model->country)"
                         :options="$countries" placeholder="Select country" />

    $options should be a simple array (flat list) or an assoc array [value => label].
--}}

<div x-data="{
        open: false,
        search: '',
        value: @js((string) ($value ?? '')),
        label: '',
        options: @js(collect($options)->map(fn ($label, $key) => ['value' => (string) $key, 'label' => $label])->values()->all()),
        get filtered() {
            if (this.search === '') return this.options;
            const s = this.search.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().includes(s));
        },
        select(option) {
            this.value = option.value;
            this.label = option.label;
            this.search = option.label;
            this.open = false;
        },
        init() {
            const found = this.options.find(o => o.value === this.value);
            if (found) { this.label = found.label; this.search = found.label; }
        },
        clear() {
            this.value = '';
            this.label = '';
            this.search = '';
        }
     }"
     x-init="init()"
     @click.outside="open = false; if (!value) search = ''; else search = label;"
     class="relative"
>
    <input type="hidden" name="{{ $name }}" :value="value">

    <div class="relative">
        <input type="text"
               x-model="search"
               @focus="open = true"
               @input="open = true"
               @keydown.escape="open = false"
               placeholder="{{ $placeholder }}"
               {{ $disabled ? 'disabled' : '' }}
               autocomplete="off"
               {{ $attributes->merge(['class' => 'block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 pr-8']) }}
        >
        <button type="button"
                x-show="value"
                @click="clear(); $refs?.searchInput?.focus()"
                class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600"
                tabindex="-1">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div x-show="open"
         x-transition.opacity.duration.150ms
         class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-lg border border-gray-200 bg-white py-1 text-sm shadow-lg"
         x-cloak>
        <template x-for="option in filtered" :key="option.value">
            <button type="button"
                    @click="select(option)"
                    class="flex w-full items-center px-3 py-2 text-left text-gray-700 transition hover:bg-indigo-50 hover:text-indigo-700"
                    :class="option.value === value ? 'bg-indigo-50 font-medium text-indigo-700' : ''">
                <span x-text="option.label"></span>
            </button>
        </template>
        <div x-show="filtered.length === 0" class="px-3 py-2 text-gray-400">No results</div>
    </div>
</div>
