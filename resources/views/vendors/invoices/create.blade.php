<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New invoice" subtitle="Record an invoice from a vendor." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-invoices.store') }}">
            @csrf
            @include('vendors.invoices._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
