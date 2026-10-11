<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Payment methods" subtitle="Bank accounts and other payment methods shown on client invoices.">
            <x-slot name="actions">
                <a href="{{ route('client-portal.show') }}" class="btn btn-outline-secondary btn-sm">{{ __('Set default payment method') }}</a>

                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-plus mr-1"></i> {{ __('New payment method') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach ($types as $key => $label)
                            <a href="{{ route('payment-methods.create', ['type' => $key]) }}" class="dropdown-item">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Type', 'Details', 'Note', 'Is enabled', 'Is default', 'Actions']">
            @forelse ($paymentMethods as $method)
                <tr>
                    <td>{{ $method->name }}</td>
                    <td>{{ $method->type_label }}</td>
                    <td class="text-muted">{{ $method->details_summary }}</td>
                    <td class="text-muted">{{ $method->note }}</td>
                    <td>
                        @if ($method->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td>
                        @if ($method->is_default)
                            <x-badge color="blue">Yes</x-badge>
                        @else
                            <span class="text-muted">No</span>
                        @endif
                    </td>
                    <td class="text-right text-nowrap">
                        @unless ($method->is_default)
                            <form method="POST" action="{{ route('payment-methods.toggle', $method) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-icon" title="{{ $method->is_enabled ? 'Disable' : 'Enable' }}">
                                    <i class="fas {{ $method->is_enabled ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                        @endunless
                        <a href="{{ route('payment-methods.edit', $method) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        @unless ($method->is_default)
                            <x-delete-form :action="route('payment-methods.destroy', $method)" message="Delete this payment method?" />
                        @endunless
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No payment methods yet</p>
                        <p class="small mb-0">Add your first payment method to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($paymentMethods->hasPages())
            <div class="card-body border-top">{{ $paymentMethods->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
