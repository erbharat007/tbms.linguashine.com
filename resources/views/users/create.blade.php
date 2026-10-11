<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add User" subtitle="Create a new team member account." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
