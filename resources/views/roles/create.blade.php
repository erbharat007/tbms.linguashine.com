<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Role" subtitle="Name the role and choose which screens it can access." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            @include('roles._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
