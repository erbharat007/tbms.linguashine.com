<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New tax" subtitle="Tax configuration." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('taxes.store') }}">
            @csrf
            @include('settings.taxes._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
