<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor portal settings" subtitle="Defaults applied to vendor invoices and the public vendor application form.">
            <x-slot name="actions">
                <a href="{{ route('vendor-portal.edit') }}">
                    <x-primary-button type="button">Update</x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-detail-row label="Default payment terms for vendor invoices">
            {{ $settings->default_payment_terms }} days
        </x-detail-row>
        <x-detail-row label="Allow vendors to create invoices">
            {{ $settings->allow_vendors_create_invoices ? 'Yes' : 'No' }}
        </x-detail-row>
        <x-detail-row label="Recipient of vendor invoice related notifications">
            {{ $settings->invoiceNotificationRecipient?->name ?? '—' }}
        </x-detail-row>
        <x-detail-row label="Vendor application form is enabled">
            @if ($settings->application_form_enabled)
                <x-badge color="green">Yes</x-badge>
            @else
                <x-badge color="gray">No</x-badge>
            @endif
        </x-detail-row>
        <x-detail-row label="Vendor application form URL">
            <div x-data="{ copied: false }" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                <a href="{{ $settings->application_url }}" target="_blank" rel="noopener"
                   class="break-all text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                    {{ $settings->application_url }}
                </a>
                <div class="flex items-center gap-3">
                    <button type="button"
                            x-on:click="navigator.clipboard.writeText('{{ $settings->application_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="text-xs font-medium text-gray-500 transition hover:text-gray-700">
                        <span x-show="! copied">Copy</span>
                        <span x-show="copied" x-cloak class="text-green-600">Copied</span>
                    </button>
                    <form method="POST" action="{{ route('vendor-portal.regenerate-token') }}"
                          onsubmit="return confirm('Regenerate the application URL? The current link will stop working.')">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-gray-500 transition hover:text-gray-700">Regenerate</button>
                    </form>
                </div>
            </div>
        </x-detail-row>
        <x-detail-row label="Recipient of notifications about new vendor applications">
            {{ $settings->applicationNotificationRecipient?->name ?? '—' }}
        </x-detail-row>
    </x-card>

    {{-- Vendor agreement --}}
    <x-card class="mt-6" no-padding>
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <h3 class="text-sm font-semibold text-gray-900">Vendor agreement</h3>
            <div class="flex items-center gap-4">
                @if ($settings->hasAgreement())
                    <a href="{{ route('vendor-agreement.edit') }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Update</a>
                    <x-delete-form :action="route('vendor-agreement.destroy')" message="Delete the vendor agreement?" />
                @else
                    <a href="{{ route('vendor-agreement.create') }}">
                        <x-primary-button type="button">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            {{ __('New agreement') }}
                        </x-primary-button>
                    </a>
                @endif
            </div>
        </div>

        @if ($settings->hasAgreement())
            <div class="px-4 py-4">
                <p class="text-sm text-gray-500">Vendor agreement must be accepted by the vendors during their registration process.</p>

                <dl class="mt-4 divide-y divide-gray-100 rounded-lg border border-gray-100">
                    <div class="flex flex-col gap-1 px-4 py-3 sm:flex-row sm:items-center">
                        <dt class="w-48 shrink-0 text-sm text-gray-500">URL</dt>
                        <dd class="text-sm">
                            <a href="{{ $settings->agreement_url }}" target="_blank" rel="noopener"
                               class="break-all font-medium text-indigo-600 transition hover:text-indigo-800">{{ $settings->agreement_url }}</a>
                        </dd>
                    </div>
                    <div class="flex flex-col gap-1 px-4 py-3 sm:flex-row sm:items-center">
                        <dt class="w-48 shrink-0 text-sm text-gray-500">Last updated at</dt>
                        <dd class="text-sm text-gray-900">{{ $settings->agreement_updated_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                    </div>
                    <div class="flex flex-col gap-1 px-4 py-3 sm:flex-row sm:items-center">
                        <dt class="w-48 shrink-0 text-sm text-gray-500">Last updated by</dt>
                        <dd class="text-sm text-gray-900">{{ $settings->agreementUpdatedBy?->name ?? '—' }}</dd>
                    </div>
                </dl>

                <h4 class="mt-6 text-sm font-semibold text-gray-900">Vendor agreement text</h4>
                <div class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $settings->agreement_text }}</div>
            </div>
        @else
            <div class="px-4 py-4">
                <p class="text-sm text-gray-500">Vendors registration is not restricted by acceptance of Vendor agreement.</p>
            </div>
        @endif
    </x-card>
</x-app-layout>
