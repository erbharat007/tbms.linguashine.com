<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Invoice ' . $invoice->invoice_number" :subtitle="'From ' . ($invoice->vendor?->name ?? 'Vendor')">
            <div class="d-flex align-items-center">
                <a href="{{ route('vendor-invoices.index') }}" class="btn btn-outline-secondary btn-sm mr-2">
                    <i class="fas fa-arrow-left mr-1"></i> Back to invoices
                </a>
                <a href="{{ route('vendor-payments.create', ['vendor_id' => $invoice->vendor_id, 'invoice_id' => $invoice->id]) }}" class="btn btn-success btn-sm mr-2">
                    <i class="fas fa-money-bill-wave mr-1"></i> Record payment
                </a>
                <a href="{{ route('vendor-invoices.edit', $invoice) }}" class="btn btn-outline-primary btn-sm mr-2">
                    <i class="fas fa-pen mr-1"></i> Edit
                </a>
                <x-delete-form :action="route('vendor-invoices.destroy', $invoice)" message="Delete this invoice?" />
            </div>
        </x-page-header>
    </x-slot>

    @php
        $balance = max(0, $invoice->total - $invoice->paid_amount);
    @endphp

    <div class="row">
        <div class="col-lg-8">
            <x-card title="Invoice Overview">
                <x-detail-row label="Invoice Number">{{ $invoice->invoice_number }}</x-detail-row>
                <x-detail-row label="Vendor">
                    @if ($invoice->vendor)
                        <a href="{{ route('vendors.show', $invoice->vendor) }}">{{ $invoice->vendor->name }}</a>
                    @else
                        —
                    @endif
                </x-detail-row>
                <x-detail-row label="Invoice Date">{{ $invoice->invoice_date->format('M d, Y') }}</x-detail-row>
                <x-detail-row label="Due Date">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '—' }}</x-detail-row>
                <x-detail-row label="Notes">{{ $invoice->notes ?: '—' }}</x-detail-row>
            </x-card>

            @if ($invoice->payables && $invoice->payables->count())
                <x-card title="Associated Payables" no-padding class="mt-4">
                    <x-table :heads="['Payable #', 'Job Code', 'Project', 'Amount', 'Status']">
                        @foreach ($invoice->payables as $payable)
                            <tr>
                                <td>
                                    <a href="{{ route('vendor-payables.edit', $payable) }}">{{ $payable->payable_number }}</a>
                                </td>
                                <td>{{ $payable->job_code ?: '—' }}</td>
                                <td>{{ $payable->project_name }}</td>
                                <td class="font-weight-bold">{{ number_format($payable->amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-light border text-capitalize">{{ $payable->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                </x-card>
            @endif
        </div>

        <div class="col-lg-4">
            <x-card title="Financials & Payment">
                <x-detail-row label="Status">
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
                </x-detail-row>
                <x-detail-row label="Currency">{{ $invoice->currency?->code ?? '—' }}</x-detail-row>
                <x-detail-row label="Subtotal">{{ number_format($invoice->subtotal, 2) }}</x-detail-row>
                <x-detail-row label="Tax">{{ number_format($invoice->tax, 2) }}</x-detail-row>
                <x-detail-row label="Discount">{{ number_format($invoice->discount, 2) }}</x-detail-row>
                <x-detail-row label="Total Amount" class="font-weight-bold text-dark h6">
                    {{ number_format($invoice->total, 2) }} {{ $invoice->currency?->code }}
                </x-detail-row>
                <x-detail-row label="Amount Paid" class="text-success">
                    {{ number_format($invoice->paid_amount, 2) }}
                </x-detail-row>
                <x-detail-row label="Remaining Balance" class="{{ $balance > 0 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                    {{ number_format($balance, 2) }} {{ $invoice->currency?->code }}
                </x-detail-row>
            </x-card>
        </div>
    </div>
</x-app-layout>
