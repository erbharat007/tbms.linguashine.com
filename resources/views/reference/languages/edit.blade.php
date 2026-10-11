<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Language" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('languages.update', $language) }}">
            @csrf
            @method('PUT')
            @include('reference.languages._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
