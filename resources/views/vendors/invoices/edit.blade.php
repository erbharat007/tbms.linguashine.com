<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Edit invoice ' . $invoice->invoice_number" subtitle="Update vendor invoice." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-invoices.update', $invoice) }}">
            @csrf
            @method('PUT')
            @include('vendors.invoices._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
