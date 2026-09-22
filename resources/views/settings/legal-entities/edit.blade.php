<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit legal entity" :subtitle="$legalEntity->name" />
    </x-slot>

    <div class="max-w-4xl">
        <x-card>
            <form method="POST" action="{{ route('legal-entities.update', $legalEntity) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('settings.legal-entities._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
