<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit User" subtitle="Update this team member's details." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('users._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
