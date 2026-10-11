<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit email template" subtitle="Multi-quote email template." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('multi-quote-email-templates.update', $template) }}">
            @csrf
            @method('PUT')
            @include('settings.multi-quote-email-templates._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
