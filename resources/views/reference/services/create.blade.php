<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Service" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('services.store') }}">
                @csrf
                @include('reference.services._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
