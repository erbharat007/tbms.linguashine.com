<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New PDF template" subtitle="Layout for a generated PDF document." />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('pdf-templates.store') }}">
                @csrf
                @include('settings.pdf-templates._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
