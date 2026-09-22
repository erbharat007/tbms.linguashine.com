<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Unit" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('units.update', $unit) }}">
                @csrf
                @method('PUT')
                @include('reference.units._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
