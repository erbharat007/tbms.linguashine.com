<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit User" subtitle="Update this team member's details." />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('users._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
