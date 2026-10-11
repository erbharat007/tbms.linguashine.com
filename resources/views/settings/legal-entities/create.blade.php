<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New legal entity" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('legal-entities.store') }}" enctype="multipart/form-data">
            @csrf
            @include('settings.legal-entities._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
