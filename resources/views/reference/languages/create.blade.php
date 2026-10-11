<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Language" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('languages.store') }}">
            @csrf
            @include('reference.languages._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
