<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit legal entity" :subtitle="$legalEntity->name" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('legal-entities.update', $legalEntity) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('settings.legal-entities._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
