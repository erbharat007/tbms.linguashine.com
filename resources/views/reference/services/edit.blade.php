<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Service" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('services.update', $service) }}">
                @csrf
                @method('PUT')
                @include('reference.services._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
