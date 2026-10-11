<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New email template" subtitle="Multi-quote email template." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('multi-quote-email-templates.store') }}">
            @csrf
            @include('settings.multi-quote-email-templates._form', ['isEdit' => false])
        </form>
    </x-card>
</x-app-layout>
