<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update other code settings" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('object-codes.settings.update') }}">
            @csrf
            @method('PUT')

            <p class="text-muted small mb-3">Changes will affect the codes of both new and existing invoices.</p>

            <input type="hidden" name="include_year_in_invoice_codes" value="0">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="include_year_in_invoice_codes"
                       name="include_year_in_invoice_codes" value="1"
                       @checked(old('include_year_in_invoice_codes', $codeSetting->include_year_in_invoice_codes)) />
                <label class="custom-control-label" for="include_year_in_invoice_codes">Include year in invoice codes</label>
            </div>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                <a href="{{ route('object-codes.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
