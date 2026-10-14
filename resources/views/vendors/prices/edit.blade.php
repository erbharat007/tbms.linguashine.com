<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit price" subtitle="Vendor price / rate list." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-prices.update', $price) }}">
            @csrf
            @method('PUT')
            @include('vendors.prices._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
