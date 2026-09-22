<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Unit" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('units.store') }}">
                @csrf
                @include('reference.units._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
