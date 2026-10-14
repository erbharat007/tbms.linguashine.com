<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New price" subtitle="Vendor price / rate list." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-prices.store') }}">
            @csrf
            @include('vendors.prices._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
