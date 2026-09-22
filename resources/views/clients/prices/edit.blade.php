<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit price" subtitle="Client price." />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('client-prices.update', $price) }}">
                @csrf
                @method('PUT')
                @include('clients.prices._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
