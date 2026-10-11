<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Client prices" subtitle="Price list used for client quotes and invoices.">
            <x-slot name="actions">
                <a href="{{ route('client-prices.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New price') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Client', 'Client country', 'Service', 'Language pair', 'Specialization', 'Unit', 'Price per unit', 'Currency', 'Note', 'Updated by', 'Updated at', 'Actions']">
            @forelse ($prices as $price)
                <tr>
                    <td class="text-nowrap">
                        @if ($price->client)
                            {{ $price->client->name }}
                        @else
                            <span class="text-muted">All clients</span>
                        @endif
                    </td>
                    <td class="text-nowrap">{{ $price->client?->country?->name ?? '—' }}</td>
                    <td class="text-nowrap">{{ $price->service?->name }}</td>
                    <td class="text-nowrap">{{ $price->languagePair?->label }}</td>
                    <td class="text-nowrap">{{ $price->specialization?->name }}</td>
                    <td class="text-nowrap">{{ $price->unit?->name }}</td>
                    <td class="text-nowrap">{{ rtrim(rtrim(number_format((float) $price->unit_price, 4), '0'), '.') }}</td>
                    <td class="text-nowrap">{{ $price->currency?->code }}</td>
                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($price->note, 40) }}</td>
                    <td class="text-nowrap text-muted">{{ $price->updatedBy?->name ?? '—' }}</td>
                    <td class="text-nowrap text-muted">{{ $price->updated_at?->format('Y-m-d H:i') }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('client-prices.edit', $price) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('client-prices.destroy', $price)" message="Delete this price?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No prices yet</p>
                        <p class="small mb-0">Add your first price to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($prices->hasPages())
            <div class="card-body border-top">{{ $prices->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
