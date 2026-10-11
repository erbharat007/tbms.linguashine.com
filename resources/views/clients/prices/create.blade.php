<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New price" subtitle="Price for all clients." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('client-prices.store') }}">
            @csrf
            @include('clients.prices._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
