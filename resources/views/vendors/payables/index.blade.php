<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Payables" subtitle="Vendor payables and receivables across translation jobs.">
            <a href="{{ route('vendor-payables.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> New payable
            </a>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('vendor-payables.index') }}" class="form-row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <x-text-input name="search" :value="$search" placeholder="Search number, job, vendor..." class="form-control-sm" />
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <x-select-input name="vendor_id" class="form-control-sm">
                        <option value="">All vendors</option>
                        @foreach ($vendors as $id => $name)
                            <option value="{{ $id }}" @selected($vendorId === (int) $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <x-select-input name="status" class="form-control-sm">
                        <option value="">All statuses</option>
                        <option value="open" @selected($status === 'open')>Open</option>
                        <option value="invoiced" @selected($status === 'invoiced')>Invoiced</option>
                        <option value="paid" @selected($status === 'paid')>Paid</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                    </x-select-input>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-primary btn-sm mr-2">Filter</button>
                    @if ($search || $status || $vendorId)
                        <a href="{{ route('vendor-payables.index') }}" class="btn btn-link btn-sm text-muted p-0">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <x-table :heads="['Payable #', 'Vendor', 'Job / Project', 'Quantity', 'Rate', 'Total Amount', 'Due date', 'Status', 'Actions']">
            @forelse ($payables as $payable)
                <tr>
                    <td class="font-weight-600">
                        <a href="{{ route('vendor-payables.edit', $payable) }}">{{ $payable->payable_number }}</a>
                    </td>
                    <td>
                        @if ($payable->vendor)
                            <a href="{{ route('vendors.show', $payable->vendor) }}">{{ $payable->vendor->name }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <div class="font-weight-600">{{ $payable->job_code ?: '—' }}</div>
                        <div class="small text-muted">{{ $payable->project_name }}</div>
                    </td>
                    <td>{{ number_format($payable->quantity, 2) }} {{ $payable->unit?->name }}</td>
                    <td>{{ number_format($payable->rate, 2) }}</td>
                    <td class="font-weight-bold">
                        {{ number_format($payable->amount, 2) }} {{ $payable->currency?->code }}
                    </td>
                    <td class="text-muted small">
                        {{ $payable->due_date ? $payable->due_date->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        @if ($payable->status === 'paid')
                            <span class="badge badge-success">Paid</span>
                        @elseif ($payable->status === 'invoiced')
                            <span class="badge badge-primary">Invoiced</span>
                        @elseif ($payable->status === 'open')
                            <span class="badge badge-info">Open</span>
                        @else
                            <span class="badge badge-secondary">Cancelled</span>
                        @endif
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('vendor-payables.edit', $payable) }}" class="action-icon text-secondary" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <x-delete-form :action="route('vendor-payables.destroy', $payable)" message="Delete this payable?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">No payables found.</td>
                </tr>
            @endforelse
        </x-table>

        @if ($payables->hasPages())
            <div class="card-footer py-2">
                {{ $payables->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
