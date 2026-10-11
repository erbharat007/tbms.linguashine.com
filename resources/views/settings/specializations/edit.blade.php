<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit specialization" :subtitle="$specialization->name" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('specializations.update', $specialization) }}">
            @csrf
            @method('PUT')
            @include('settings.specializations._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
