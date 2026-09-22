<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit Currency" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('currencies.update', $currency) }}">
                @csrf
                @method('PUT')
                @include('reference.currencies._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
