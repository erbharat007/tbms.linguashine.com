<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Client portal settings" subtitle="Defaults applied to client invoices and the client portal.">
            <x-slot name="actions">
                <a href="{{ route('client-portal.edit') }}" class="btn btn-primary btn-sm">Update</a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding class="detail-rows-wide">
        <x-detail-row label="Default payment method for client invoices">
            {{ $settings->defaultPaymentMethod?->name ?? '—' }}
        </x-detail-row>
        <x-detail-row label="Default payment terms for client invoices">
            {{ $settings->default_payment_terms }} days
        </x-detail-row>
        <x-detail-row label="Allow clients to create projects">
            {{ $settings->allow_clients_create_projects ? 'Yes' : 'No' }}
        </x-detail-row>
        <x-detail-row label="Default client manager">
            {{ $settings->defaultClientManager?->name ?? '—' }}
        </x-detail-row>
        <x-detail-row label="Recipient of client invoice related notifications">
            {{ $settings->notificationRecipient?->name ?? '—' }}
        </x-detail-row>
    </x-card>
</x-app-layout>
