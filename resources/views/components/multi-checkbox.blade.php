@props(['name', 'options' => [], 'selected' => [], 'placeholder' => 'Search...'])

{{--
    A searchable multi-select rendered as a scrollable checkbox list.
    Posts an array of selected ids as {name}[].
    $options: [id => label]. $selected: array of pre-checked ids.
--}}
<div x-data="{
        search: '',
        matches(label) { return this.search === '' || label.toLowerCase().includes(this.search.toLowerCase()); }
     }"
     class="rounded-lg border border-gray-300">
    <div class="border-b border-gray-100 p-2">
        <input type="text" x-model="search" placeholder="{{ $placeholder }}"
               class="block w-full rounded-md border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div class="max-h-56 overflow-auto p-2">
        @forelse ($options as $id => $label)
            <label x-show="matches(@js($label))" class="flex items-center gap-2 rounded px-2 py-1.5 hover:bg-gray-50">
                <input type="checkbox" name="{{ $name }}[]" value="{{ $id }}"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       @checked(in_array($id, old($name, $selected)))>
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
        @empty
            <p class="px-2 py-1.5 text-sm text-gray-400">No options available.</p>
        @endforelse
    </div>
</div>
