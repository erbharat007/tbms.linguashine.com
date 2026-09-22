<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Role" subtitle="Update the role name and its screen access." />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')
                @include('roles._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
