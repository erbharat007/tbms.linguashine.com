<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add Language" />
    </x-slot>

    <div class="max-w-xl">
        <x-card>
            <form method="POST" action="{{ route('languages.store') }}">
                @csrf
                @include('reference.languages._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
