<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Payment methods" subtitle="Bank accounts and other payment methods shown on client invoices.">
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    <a href="{{ route('client-portal.show') }}">
                        <x-secondary-button type="button">{{ __('Set default payment method') }}</x-secondary-button>
                    </a>

                    <div x-data="{ open: false }" class="relative">
                        <x-primary-button type="button" x-on:click="open = ! open">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            {{ __('New payment method') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                        </x-primary-button>

                        <div x-show="open"
                             x-on:click.outside="open = false"
                             x-transition
                             class="absolute right-0 z-20 mt-2 w-48 rounded-md border border-gray-200 bg-white py-1 shadow-lg"
                             style="display: none;">
                            @foreach ($types as $key => $label)
                                <a href="{{ route('payment-methods.create', ['type' => $key]) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Type', 'Details', 'Note', 'Is enabled', 'Is default', 'Actions']">
            @forelse ($paymentMethods as $method)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $method->name }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $method->type_label }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $method->details_summary }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $method->note }}</td>
                    <td class="px-4 py-3">
                        @if ($method->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($method->is_default)
                            <x-badge color="blue">Yes</x-badge>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            @unless ($method->is_default)
                                <form method="POST" action="{{ route('payment-methods.toggle', $method) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                        {{ $method->is_enabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            @endunless
                            <a href="{{ route('payment-methods.edit', $method) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            @unless ($method->is_default)
                                <x-delete-form :action="route('payment-methods.destroy', $method)" message="Delete this payment method?" />
                            @endunless
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No payment methods yet</p>
                        <p class="mt-1 text-sm">Add your first payment method to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($paymentMethods->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $paymentMethods->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
