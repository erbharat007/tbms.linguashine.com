<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New client" subtitle="Add a client and its primary contact." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('clients.store') }}">
            @csrf
            @include('clients._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
