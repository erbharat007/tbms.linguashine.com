<x-app-layout>
    <x-slot name="header">
        <ol class="breadcrumb bg-transparent p-0 mb-1 small">
            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clients.contacts.index', $client) }}">Contact persons</a></li>
            <li class="breadcrumb-item active">{{ $contact->full_name }}</li>
        </ol>
        <h1 class="m-0 h3">Edit contact person</h1>
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('clients.contacts.update', [$client, $contact]) }}">
            @csrf
            @method('PUT')
            @include('clients.contacts._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
