<x-app-layout>
    <x-slot name="header">
        <x-page-header title="New email template" subtitle="Client invoice email template." />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('client-invoice-email-templates.store') }}">
                @csrf
                @include('settings.client-invoice-email-templates._form', ['isEdit' => false])
            </form>
        </x-card>
    </div>
</x-app-layout>
