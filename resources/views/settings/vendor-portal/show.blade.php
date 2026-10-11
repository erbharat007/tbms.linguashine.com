<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor portal settings" subtitle="Defaults applied to vendor invoices and the public vendor application form.">
            <x-slot name="actions">
                <a href="{{ route('vendor-portal.edit') }}" class="btn btn-primary btn-sm">Update</a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding class="detail-rows-wide">
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
            <div class="d-flex flex-column flex-sm-row align-items-sm-center" style="gap: 0.75rem;">
                <a href="{{ $settings->application_url }}" target="_blank" rel="noopener" class="text-break">{{ $settings->application_url }}</a>
                <div class="d-flex align-items-center text-nowrap" style="gap: 0.75rem;">
                    <button type="button" class="btn btn-link btn-sm p-0 copy-url" data-url="{{ $settings->application_url }}">Copy</button>
                    <form method="POST" action="{{ route('vendor-portal.regenerate-token') }}" class="d-inline"
                          onsubmit="return confirm('Regenerate the application URL? The current link will stop working.')">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm p-0 text-muted">Regenerate</button>
                    </form>
                </div>
            </div>
        </x-detail-row>
        <x-detail-row label="Recipient of notifications about new vendor applications">
            {{ $settings->applicationNotificationRecipient?->name ?? '—' }}
        </x-detail-row>
    </x-card>

    {{-- Vendor agreement --}}
    <x-card title="Vendor agreement" no-padding class="mt-4">
        <x-slot name="actions">
            @if ($settings->hasAgreement())
                <a href="{{ route('vendor-agreement.edit') }}" class="btn btn-outline-secondary btn-sm">Update</a>
                <x-delete-form :action="route('vendor-agreement.destroy')" message="Delete the vendor agreement?" />
            @else
                <a href="{{ route('vendor-agreement.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New agreement') }}
                </a>
            @endif
        </x-slot>

        @if ($settings->hasAgreement())
            <div class="p-3">
                <p class="text-muted small">Vendor agreement must be accepted by the vendors during their registration process.</p>

                <div class="detail-rows-wide border rounded">
                    <x-detail-row label="URL">
                        <a href="{{ $settings->agreement_url }}" target="_blank" rel="noopener" class="text-break">{{ $settings->agreement_url }}</a>
                    </x-detail-row>
                    <x-detail-row label="Last updated at">{{ $settings->agreement_updated_at?->format('Y-m-d H:i') ?? '—' }}</x-detail-row>
                    <x-detail-row label="Last updated by">{{ $settings->agreementUpdatedBy?->name ?? '—' }}</x-detail-row>
                </div>

                <h4 class="h6 mt-4">Vendor agreement text</h4>
                <div class="mt-2 small text-muted" style="white-space: pre-line;">{{ $settings->agreement_text }}</div>
            </div>
        @else
            <div class="p-3">
                <p class="text-muted small mb-0">Vendors registration is not restricted by acceptance of Vendor agreement.</p>
            </div>
        @endif
    </x-card>

    @push('scripts')
    <script>
        $(function () {
            $('.copy-url').on('click', function () {
                var btn = this;
                navigator.clipboard.writeText($(btn).data('url')).then(function () {
                    var original = btn.textContent;
                    btn.textContent = 'Copied';
                    setTimeout(function () { btn.textContent = original; }, 2000);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
