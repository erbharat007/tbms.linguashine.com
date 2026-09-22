<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit email template" subtitle="Client invoice email template." />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('client-invoice-email-templates.update', $template) }}">
                @csrf
                @method('PUT')
                @include('settings.client-invoice-email-templates._form', ['isEdit' => true])
            </form>
        </x-card>
    </div>
</x-app-layout>
