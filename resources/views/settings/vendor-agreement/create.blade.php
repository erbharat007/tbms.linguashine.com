<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New vendor agreement" subtitle="Text vendors must accept during registration." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-agreement.store') }}">
            @csrf
            @include('settings.vendor-agreement._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
