<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add User" subtitle="Create a new team member account." />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                @include('users._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
