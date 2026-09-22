<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New price" subtitle="Price for all clients." />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('client-prices.store') }}">
                @csrf
                @include('clients.prices._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
