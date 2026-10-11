<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit email template" subtitle="Client invoice email template." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('client-invoice-email-templates.update', $template) }}">
            @csrf
            @method('PUT')
            @include('settings.client-invoice-email-templates._form', ['isEdit' => true])
        </form>
    </x-card>
</x-app-layout>
