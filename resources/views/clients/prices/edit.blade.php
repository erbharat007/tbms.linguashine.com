<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit price" subtitle="Client price." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('client-prices.update', $price) }}">
            @csrf
            @method('PUT')
            @include('clients.prices._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
