<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Currency" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('currencies.store') }}">
                @csrf
                @include('reference.currencies._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
