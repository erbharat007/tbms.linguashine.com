<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor portal settings" subtitle="Defaults applied to vendor invoices and the public vendor application form." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('vendor-portal.update') }}" class="form-horizontal-tbms" style="max-width: 760px;">
            @csrf
            @method('PUT')

            <div class="form-group row">
                <label for="default_payment_terms" class="col-sm-5 col-form-label">Default payment terms for vendor invoices</label>
                <div class="col-sm-7">
                    <div class="input-group input-group-sm" style="max-width: 180px;">
                        <input id="default_payment_terms" name="default_payment_terms" type="number" min="0" max="365"
                               class="form-control" value="{{ old('default_payment_terms', $settings->default_payment_terms) }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">days</span>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('default_payment_terms')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="invoice_notification_recipient_id" class="col-sm-5 col-form-label">Recipient of vendor invoice related notifications</label>
                <div class="col-sm-7">
                    <x-select-input id="invoice_notification_recipient_id" name="invoice_notification_recipient_id" required>
                        <option value="">— Select —</option>
                        @foreach ($managers as $id => $name)
                            <option value="{{ $id }}" @selected(old('invoice_notification_recipient_id', $settings->invoice_notification_recipient_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('invoice_notification_recipient_id')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <label for="application_notification_recipient_id" class="col-sm-5 col-form-label">Recipient of notifications about new vendor applications</label>
                <div class="col-sm-7">
                    <x-select-input id="application_notification_recipient_id" name="application_notification_recipient_id" required>
                        <option value="">— Select —</option>
                        @foreach ($managers as $id => $name)
                            <option value="{{ $id }}" @selected(old('application_notification_recipient_id', $settings->application_notification_recipient_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('application_notification_recipient_id')" class="mt-1" />
                </div>
            </div>

            <div class="form-group row">
                <span class="col-sm-5 col-form-label">Allow vendors to create invoices</span>
                <div class="col-sm-7 d-flex align-items-center">
                    <input type="hidden" name="allow_vendors_create_invoices" value="0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="allow_vendors_create_invoices"
                               name="allow_vendors_create_invoices" value="1"
                               @checked(old('allow_vendors_create_invoices', $settings->allow_vendors_create_invoices)) />
                        <label class="custom-control-label" for="allow_vendors_create_invoices"></label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <span class="col-sm-5 col-form-label">Vendor application form is enabled</span>
                <div class="col-sm-7">
                    <input type="hidden" name="application_form_enabled" value="0">
                    <div class="custom-control custom-checkbox mt-2">
                        <input type="checkbox" class="custom-control-input" id="application_form_enabled"
                               name="application_form_enabled" value="1"
                               @checked(old('application_form_enabled', $settings->application_form_enabled)) />
                        <label class="custom-control-label" for="application_form_enabled"></label>
                    </div>
                    <small class="form-text text-muted">When disabled, the public application URL returns "not found".</small>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                <a href="{{ route('vendor-portal.show') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
