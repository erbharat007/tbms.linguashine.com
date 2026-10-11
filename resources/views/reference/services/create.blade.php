<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Service" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('services.store') }}">
            @csrf
            @include('reference.services._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
