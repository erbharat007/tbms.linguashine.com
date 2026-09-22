<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Role" subtitle="Name the role and choose which screens it can access." />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                @include('roles._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
