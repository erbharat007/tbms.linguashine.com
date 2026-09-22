<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor portal settings" subtitle="Defaults applied to vendor invoices and the public vendor application form." />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('vendor-portal.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <x-input-label for="default_payment_terms" :value="__('Default payment terms for vendor invoices')" />
                        <div class="mt-1.5 flex items-center gap-2">
                            <x-text-input id="default_payment_terms" name="default_payment_terms" type="number" min="0" max="365" class="w-28"
                                          :value="old('default_payment_terms', $settings->default_payment_terms)" required />
                            <span class="text-sm text-gray-500">days</span>
                        </div>
                        <x-input-error :messages="$errors->get('default_payment_terms')" class="mt-2" />
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="hidden" name="allow_vendors_create_invoices" value="0">
                        <input type="checkbox" name="allow_vendors_create_invoices" value="1"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               @checked(old('allow_vendors_create_invoices', $settings->allow_vendors_create_invoices)) />
                        <span class="text-sm text-gray-700">Allow vendors to create invoices</span>
                    </label>

                    <div>
                        <x-input-label for="invoice_notification_recipient_id" :value="__('Recipient of vendor invoice related notifications')" />
                        <x-select-input id="invoice_notification_recipient_id" name="invoice_notification_recipient_id" class="mt-1.5" required>
                            <option value="">— Select —</option>
                            @foreach ($managers as $id => $name)
                                <option value="{{ $id }}" @selected(old('invoice_notification_recipient_id', $settings->invoice_notification_recipient_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('invoice_notification_recipient_id')" class="mt-2" />
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="hidden" name="application_form_enabled" value="0">
                        <input type="checkbox" name="application_form_enabled" value="1"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               @checked(old('application_form_enabled', $settings->application_form_enabled)) />
                        <span class="text-sm text-gray-700">Vendor application form is enabled</span>
                    </label>
                    <p class="-mt-4 ml-6 text-xs text-gray-400">When disabled, the public application URL returns "not found".</p>

                    <div>
                        <x-input-label for="application_notification_recipient_id" :value="__('Recipient of notifications about new vendor applications')" />
                        <x-select-input id="application_notification_recipient_id" name="application_notification_recipient_id" class="mt-1.5" required>
                            <option value="">— Select —</option>
                            @foreach ($managers as $id => $name)
                                <option value="{{ $id }}" @selected(old('application_notification_recipient_id', $settings->application_notification_recipient_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('application_notification_recipient_id')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                        <a href="{{ route('vendor-portal.show') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
