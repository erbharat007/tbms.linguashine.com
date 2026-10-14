<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Payments" subtitle="Outgoing payments and remittances to vendors.">
            <a href="{{ route('vendor-payments.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Record payment
            </a>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('vendor-payments.index') }}" class="form-row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <x-text-input name="search" :value="$search" placeholder="Search payment #, ref #, vendor..." class="form-control-sm" />
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <x-select-input name="vendor_id" class="form-control-sm">
                        <option value="">All vendors</option>
                        @foreach ($vendors as $id => $name)
                            <option value="{{ $id }}" @selected($vendorId === (int) $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-primary btn-sm mr-2">Filter</button>
                    @if ($search || $vendorId)
                        <a href="{{ route('vendor-payments.index') }}" class="btn btn-link btn-sm text-muted p-0">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <x-table :heads="['Payment #', 'Vendor', 'Date', 'Amount', 'Payment Method', 'Reference #', 'Status', 'Actions']">
            @forelse ($payments as $payment)
                <tr>
                    <td class="font-weight-600">
                        <a href="{{ route('vendor-payments.edit', $payment) }}">{{ $payment->payment_number }}</a>
                    </td>
                    <td>
                        @if ($payment->vendor)
                            <a href="{{ route('vendors.show', $payment->vendor) }}">{{ $payment->vendor->name }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-muted small">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td class="font-weight-bold text-success">
                        {{ number_format($payment->amount, 2) }} {{ $payment->currency?->code }}
                    </td>
                    <td>{{ $payment->paymentMethod?->name ?? '—' }}</td>
                    <td>{{ $payment->reference_number ?: '—' }}</td>
                    <td>
                        @if ($payment->status === 'paid')
                            <span class="badge badge-success">Paid</span>
                        @else
                            <span class="badge badge-secondary">Cancelled</span>
                        @endif
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('vendor-payments.edit', $payment) }}" class="action-icon text-secondary" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <x-delete-form :action="route('vendor-payments.destroy', $payment)" message="Delete this payment record?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No vendor payments recorded.</td>
                </tr>
            @endforelse
        </x-table>

        @if ($payments->hasPages())
            <div class="card-footer py-2">
                {{ $payments->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
