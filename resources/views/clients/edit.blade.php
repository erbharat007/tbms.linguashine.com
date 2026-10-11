<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit client" :subtitle="$client->name" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')
            @include('clients._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
