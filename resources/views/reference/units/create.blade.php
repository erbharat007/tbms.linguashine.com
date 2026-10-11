<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Unit" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('units.store') }}">
            @csrf
            @include('reference.units._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
