<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Role" subtitle="Update the role name and its screen access." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')
            @include('roles._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
