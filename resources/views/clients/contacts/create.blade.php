<x-app-layout>
    <x-slot name="header">
        <ol class="breadcrumb bg-transparent p-0 mb-1 small">
            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clients.contacts.index', $client) }}">Contact persons</a></li>
            <li class="breadcrumb-item active">New</li>
        </ol>
        <h1 class="m-0 h3">New contact person</h1>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('clients.contacts.store', $client) }}">
            @csrf
            @include('clients.contacts._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
