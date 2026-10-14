<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Invoices" subtitle="Incoming supplier invoices and bills from vendors.">
            <a href="{{ route('vendor-invoices.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> New invoice
            </a>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('vendor-invoices.index') }}" class="form-row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <x-text-input name="search" :value="$search" placeholder="Search invoice # or vendor..." class="form-control-sm" />
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <x-select-input name="vendor_id" class="form-control-sm">
                        <option value="">All vendors</option>
                        @foreach ($vendors as $id => $name)
                            <option value="{{ $id }}" @selected($vendorId === (int) $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <x-select-input name="status" class="form-control-sm">
                        <option value="">All statuses</option>
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                        <option value="received" @selected($status === 'received')>Received</option>
                        <option value="partially_paid" @selected($status === 'partially_paid')>Partially paid</option>
                        <option value="paid" @selected($status === 'paid')>Paid</option>
                        <option value="overdue" @selected($status === 'overdue')>Overdue</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                    </x-select-input>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-primary btn-sm mr-2">Filter</button>
                    @if ($search || $status || $vendorId)
                        <a href="{{ route('vendor-invoices.index') }}" class="btn btn-link btn-sm text-muted p-0">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <x-table :heads="['Invoice #', 'Vendor', 'Date', 'Due Date', 'Total', 'Paid', 'Balance', 'Status', 'Actions']">
            @forelse ($invoices as $invoice)
                @php
                    $balance = max(0, $invoice->total - $invoice->paid_amount);
                @endphp
                <tr>
                    <td class="font-weight-600">
                        <a href="{{ route('vendor-invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                    </td>
                    <td>
                        @if ($invoice->vendor)
                            <a href="{{ route('vendors.show', $invoice->vendor) }}">{{ $invoice->vendor->name }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-muted small">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    <td class="text-muted small">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '—' }}</td>
                    <td class="font-weight-bold">{{ number_format($invoice->total, 2) }} {{ $invoice->currency?->code }}</td>
                    <td class="text-success">{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td class="{{ $balance > 0 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                        {{ number_format($balance, 2) }}
                    </td>
                    <td>
                        @if ($invoice->status === 'paid')
                            <span class="badge badge-success">Paid</span>
                        @elseif ($invoice->status === 'partially_paid')
                            <span class="badge badge-info">Partially paid</span>
                        @elseif ($invoice->status === 'received')
                            <span class="badge badge-primary">Received</span>
                        @elseif ($invoice->status === 'overdue')
                            <span class="badge badge-danger">Overdue</span>
                        @elseif ($invoice->status === 'draft')
                            <span class="badge badge-secondary">Draft</span>
                        @else
                            <span class="badge badge-dark">Cancelled</span>
                        @endif
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('vendor-invoices.show', $invoice) }}" class="action-icon text-primary" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('vendor-invoices.edit', $invoice) }}" class="action-icon text-secondary" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <x-delete-form :action="route('vendor-invoices.destroy', $invoice)" message="Delete this invoice?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">No vendor invoices found.</td>
                </tr>
            @endforelse
        </x-table>

        @if ($invoices->hasPages())
            <div class="card-footer py-2">
                {{ $invoices->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
