<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Currencies" subtitle="Currencies available for client and vendor rates.">
            <x-slot name="actions">
                <a href="{{ route('currencies.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add currency') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Currency', 'ISO code', 'Symbol', 'Is enabled', 'Is system currency', 'Actions']">
            @forelse ($currencies as $currency)
                <tr>
                    <td>{{ $currency->name }}</td>
                    <td>{{ $currency->code }}</td>
                    <td>{{ $currency->symbol ?: '—' }}</td>
                    <td>
                        @if ($currency->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td>{{ $currency->is_system ? 'Yes' : 'No' }}</td>
                    <td class="text-right text-nowrap">
                        @unless ($currency->is_system)
                            <form method="POST" action="{{ route('currencies.set-system', $currency) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-icon" title="Set as system currency"><i class="fas fa-star"></i></button>
                            </form>
                            <form method="POST" action="{{ route('currencies.toggle', $currency) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-icon" title="{{ $currency->is_enabled ? 'Disable' : 'Enable' }}">
                                    <i class="fas {{ $currency->is_enabled ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                        @endunless
                        <a href="{{ route('currencies.edit', $currency) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        @unless ($currency->is_system)
                            <x-delete-form :action="route('currencies.destroy', $currency)" message="Remove this currency?" />
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No currencies yet</p>
                        <p class="small mb-0">Add your first currency to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($currencies->hasPages())
            <div class="card-body border-top">{{ $currencies->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
