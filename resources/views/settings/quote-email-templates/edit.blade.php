<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit email template" subtitle="Quote email template." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('quote-email-templates.update', $template) }}">
            @csrf
            @method('PUT')
            @include('settings.quote-email-templates._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
