<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor prices" subtitle="Price list agreed with vendors and freelance linguists.">
            <x-slot name="actions">
                <a href="{{ route('vendor-prices.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New price') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <select name="vendor_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All vendors</option>
                        @foreach ($vendors as $id => $name)
                            <option value="{{ $id }}" @selected($vendorId === $id)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($vendorId > 0)
                    <a href="{{ route('vendor-prices.index') }}" class="text-muted ml-2">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Vendor', 'Country', 'Service', 'Language pair', 'Specialization', 'Unit', 'Price per unit', 'Currency', 'Note', 'Updated by', 'Updated at', 'Actions']">
            @forelse ($prices as $price)
                <tr>
                    <td class="text-nowrap">
                        @if ($price->vendor)
                            <a href="{{ route('vendors.show', $price->vendor) }}">{{ $price->vendor->name }}</a>
                        @else
                            <span class="text-muted">All vendors (Default)</span>
                        @endif
                    </td>
                    <td class="text-nowrap">{{ $price->vendor?->country?->name ?? '—' }}</td>
                    <td class="text-nowrap">{{ $price->service?->name }}</td>
                    <td class="text-nowrap">{{ $price->languagePair?->label }}</td>
                    <td class="text-nowrap">{{ $price->specialization?->name }}</td>
                    <td class="text-nowrap">{{ $price->unit?->name }}</td>
                    <td class="text-nowrap font-weight-bold">{{ rtrim(rtrim(number_format((float) $price->unit_price, 4), '0'), '.') }}</td>
                    <td class="text-nowrap">{{ $price->currency?->code }}</td>
                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($price->note, 40) }}</td>
                    <td class="text-nowrap text-muted">{{ $price->updatedBy?->name ?? '—' }}</td>
                    <td class="text-nowrap text-muted">{{ $price->updated_at?->format('Y-m-d H:i') }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('vendor-prices.edit', $price) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('vendor-prices.destroy', $price)" message="Delete this price?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No vendor prices recorded</p>
                        <p class="small mb-0">Add vendor price matrix entries to speed up job assignment and cost calculation.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($prices->hasPages())
            <div class="card-body border-top">{{ $prices->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
