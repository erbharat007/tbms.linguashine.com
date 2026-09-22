<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Client prices" subtitle="Price list used for client quotes and invoices.">
            <x-slot name="actions">
                <a href="{{ route('client-prices.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New price') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="overflow-x-auto">
            <x-table :heads="['Client', 'Client country', 'Service', 'Language pair', 'Specialization', 'Unit', 'Price per unit', 'Currency', 'Note', 'Updated by', 'Updated at', 'Actions']">
                @forelse ($prices as $price)
                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                            @if ($price->client)
                                {{ $price->client->name }}
                            @else
                                <span class="text-gray-400">All clients</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $price->client?->country?->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $price->service?->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $price->languagePair?->label }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $price->specialization?->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $price->unit?->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-900">{{ rtrim(rtrim(number_format((float) $price->unit_price, 4), '0'), '.') }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $price->currency?->code }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ \Illuminate\Support\Str::limit($price->note, 40) }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-500">{{ $price->updatedBy?->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-500">{{ $price->updated_at?->format('Y-m-d H:i') }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('client-prices.edit', $price) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                                <x-delete-form :action="route('client-prices.destroy', $price)" message="Delete this price?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="px-4 py-12 text-center text-gray-500">
                            <p class="font-medium">No prices yet</p>
                            <p class="mt-1 text-sm">Add your first price to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        @if ($prices->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $prices->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
