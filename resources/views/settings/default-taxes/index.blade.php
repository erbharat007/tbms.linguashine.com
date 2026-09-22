<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Default taxes" subtitle="Default taxes applied to new clients and vendors.">
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    <a href="{{ route('taxes.index') }}">
                        <x-secondary-button type="button">Back to taxes</x-secondary-button>
                    </a>
                    <a href="{{ route('default-taxes.create') }}">
                        <x-primary-button type="button">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            {{ __('Add default tax') }}
                        </x-primary-button>
                    </a>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div x-data="{ tab: '{{ array_key_first($objectTypes) }}' }">
        {{-- Tabs by object type --}}
        <div class="mb-4 border-b border-gray-200">
            <nav class="-mb-px flex gap-x-6 text-sm">
                @foreach ($objectTypes as $key => $label)
                    <button type="button" x-on:click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="flex items-center gap-2 border-b-2 px-1 py-3 font-medium transition">
                        {{ $label }}s
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{{ optional($grouped->get($key))->count() ?? 0 }}</span>
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Panels --}}
        @foreach ($objectTypes as $key => $label)
            @php($rows = $grouped->get($key) ?? collect())
            <div x-show="tab === '{{ $key }}'" x-cloak>
                <x-card no-padding>
                    <x-table :heads="['Tax', 'Type', 'Default value', 'Currency', 'Sort order inside invoices', 'Actions']">
                        @forelse ($rows as $default)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $default->tax?->name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $default->tax?->type_label }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ rtrim(rtrim(number_format((float) $default->amount, 2), '0'), '.') }}</td>
                                <td class="px-4 py-3 text-gray-700">
                                    @if ($default->currency)
                                        {{ $default->currency->code }} - {{ $default->currency->name }}
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $default->tax?->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-4">
                                        <a href="{{ route('default-taxes.edit', $default) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                                        <x-delete-form :action="route('default-taxes.destroy', $default)" message="Delete this default tax?" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    <p class="font-medium">No default taxes for {{ strtolower($label) }}s yet</p>
                                    <p class="mt-1 text-sm">Add a default tax to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-table>
                </x-card>
            </div>
        @endforeach
    </div>
</x-app-layout>
