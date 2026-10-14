<x-app-layout>
    <x-slot name="header">
        <x-page-header :title="'Edit payable ' . $payable->payable_number" subtitle="Update vendor payable details." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-payables.update', $payable) }}">
            @csrf
            @method('PUT')
            @include('vendors.payables._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
