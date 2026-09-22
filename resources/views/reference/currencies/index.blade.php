<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Currencies" subtitle="Currencies available for client and vendor rates.">
            <x-slot name="actions">
                <a href="{{ route('currencies.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('Add currency') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Currency', 'ISO code', 'Symbol', 'Is enabled', 'Is system currency', 'Actions']">
            @forelse ($currencies as $currency)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $currency->name }}</td>
                    <td class="px-4 py-3"><x-badge>{{ $currency->code }}</x-badge></td>
                    <td class="px-4 py-3 text-gray-600">{{ $currency->symbol ?: '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($currency->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($currency->is_system)
                            <x-badge color="blue">Yes</x-badge>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            @unless ($currency->is_system)
                                <form method="POST" action="{{ route('currencies.set-system', $currency) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Set as system</button>
                                </form>
                                <form method="POST" action="{{ route('currencies.toggle', $currency) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                        {{ $currency->is_enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            @endunless
                            <a href="{{ route('currencies.edit', $currency) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            @unless ($currency->is_system)
                                <x-delete-form :action="route('currencies.destroy', $currency)" message="Remove this currency?" />
                            @endunless
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No currencies yet</p>
                        <p class="mt-1 text-sm">Add your first currency to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($currencies->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $currencies->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
