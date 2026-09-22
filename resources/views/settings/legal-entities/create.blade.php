<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New legal entity" />
    </x-slot>

    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('legal-entities.store') }}" enctype="multipart/form-data">
                @csrf
                @include('settings.legal-entities._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
