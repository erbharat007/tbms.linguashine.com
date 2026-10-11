<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit PDF template" subtitle="Layout for a generated PDF document." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('pdf-templates.update', $template) }}">
            @csrf
            @method('PUT')
            @include('settings.pdf-templates._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
