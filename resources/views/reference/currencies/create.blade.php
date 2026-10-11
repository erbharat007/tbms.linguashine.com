<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Currency" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('currencies.store') }}">
            @csrf
            @include('reference.currencies._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
