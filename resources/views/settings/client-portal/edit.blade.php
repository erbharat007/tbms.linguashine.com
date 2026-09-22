<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Client portal settings" subtitle="Defaults applied to client invoices and the client portal." />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('client-portal.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <x-input-label for="default_payment_method_id" :value="__('Default payment method for client invoices')" />
                        <x-select-input id="default_payment_method_id" name="default_payment_method_id" class="mt-1.5">
                            <option value="">— None —</option>
                            @foreach ($paymentMethods as $id => $name)
                                <option value="{{ $id }}" @selected(old('default_payment_method_id', $settings->default_payment_method_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('default_payment_method_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="default_payment_terms" :value="__('Default payment terms for client invoices')" />
                        <div class="mt-1.5 flex items-center gap-2">
                            <x-text-input id="default_payment_terms" name="default_payment_terms" type="number" min="0" max="365" class="w-28"
                                          :value="old('default_payment_terms', $settings->default_payment_terms)" required />
                            <span class="text-sm text-gray-500">days</span>
                        </div>
                        <x-input-error :messages="$errors->get('default_payment_terms')" class="mt-2" />
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="hidden" name="allow_clients_create_projects" value="0">
                        <input type="checkbox" name="allow_clients_create_projects" value="1"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                               @checked(old('allow_clients_create_projects', $settings->allow_clients_create_projects)) />
                        <span class="text-sm text-gray-700">Allow clients to create projects</span>
                    </label>

                    <div>
                        <x-input-label for="default_client_manager_id" :value="__('Default client manager')" />
                        <x-select-input id="default_client_manager_id" name="default_client_manager_id" class="mt-1.5" required>
                            <option value="">— Select —</option>
                            @foreach ($managers as $id => $name)
                                <option value="{{ $id }}" @selected(old('default_client_manager_id', $settings->default_client_manager_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('default_client_manager_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notification_recipient_id" :value="__('Recipient of client invoice related notifications')" />
                        <x-select-input id="notification_recipient_id" name="notification_recipient_id" class="mt-1.5" required>
                            <option value="">— Select —</option>
                            @foreach ($managers as $id => $name)
                                <option value="{{ $id }}" @selected(old('notification_recipient_id', $settings->notification_recipient_id) == $id)>{{ $name }}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('notification_recipient_id')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                        <a href="{{ route('client-portal.show') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
