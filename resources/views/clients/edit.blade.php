<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit client" :subtitle="$client->name" />
    </x-slot>

    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('clients.update', $client) }}">
                @csrf
                @method('PUT')
                @include('clients._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
