<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit vendor agreement" subtitle="Text vendors must accept during registration." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-agreement.update') }}">
            @csrf
            @method('PUT')
            @include('settings.vendor-agreement._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
