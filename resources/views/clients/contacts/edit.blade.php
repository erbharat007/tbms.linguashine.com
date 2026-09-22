<x-app-layout>
    <x-slot name="header">
        <nav class="mb-1 text-sm text-gray-500">
            <a href="{{ route('clients.index') }}" class="hover:text-indigo-600">Clients</a>
            <span class="mx-1">/</span>
            <a href="{{ route('clients.show', $client) }}" class="hover:text-indigo-600">{{ $client->name }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('clients.contacts.index', $client) }}" class="hover:text-indigo-600">Contact persons</a>
            <span class="mx-1">/</span>
            <span class="text-gray-700">{{ $contact->full_name }}</span>
        </nav>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit contact person</h2>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('clients.contacts.update', [$client, $contact]) }}">
                @csrf
                @method('PUT')
                @include('clients.contacts._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
