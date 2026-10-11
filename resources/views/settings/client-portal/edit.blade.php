<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Client portal settings" subtitle="Defaults applied to client invoices and the client portal." />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('client-portal.update') }}">
            @csrf
            @method('PUT')

            <div class="row form-grid">
                <div class="form-group col-md-6">
                    <x-input-label for="default_payment_method_id" :value="__('Default payment method for client invoices')" />
                    <x-select-input id="default_payment_method_id" name="default_payment_method_id">
                        <option value="">— None —</option>
                        @foreach ($paymentMethods as $id => $name)
                            <option value="{{ $id }}" @selected(old('default_payment_method_id', $settings->default_payment_method_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('default_payment_method_id')" class="mt-1" />
                </div>

                <div class="form-group col-md-6">
                    <x-input-label for="default_payment_terms" :value="__('Default payment terms for client invoices')" />
                    <div class="input-group input-group-sm" style="max-width: 180px;">
                        <input id="default_payment_terms" name="default_payment_terms" type="number" min="0" max="365"
                               class="form-control" value="{{ old('default_payment_terms', $settings->default_payment_terms) }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text">days</span>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('default_payment_terms')" class="mt-1" />
                </div>

                <div class="form-group col-md-6">
                    <x-input-label for="default_client_manager_id" :value="__('Default client manager')" />
                    <x-select-input id="default_client_manager_id" name="default_client_manager_id" required>
                        <option value="">— Select —</option>
                        @foreach ($managers as $id => $name)
                            <option value="{{ $id }}" @selected(old('default_client_manager_id', $settings->default_client_manager_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('default_client_manager_id')" class="mt-1" />
                </div>

                <div class="form-group col-md-6">
                    <x-input-label for="notification_recipient_id" :value="__('Recipient of client invoice related notifications')" />
                    <x-select-input id="notification_recipient_id" name="notification_recipient_id" required>
                        <option value="">— Select —</option>
                        @foreach ($managers as $id => $name)
                            <option value="{{ $id }}" @selected(old('notification_recipient_id', $settings->notification_recipient_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('notification_recipient_id')" class="mt-1" />
                </div>

                <div class="form-group col-12">
                    <input type="hidden" name="allow_clients_create_projects" value="0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="allow_clients_create_projects"
                               name="allow_clients_create_projects" value="1"
                               @checked(old('allow_clients_create_projects', $settings->allow_clients_create_projects)) />
                        <label class="custom-control-label" for="allow_clients_create_projects">Allow clients to create projects</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                <a href="{{ route('client-portal.show') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
