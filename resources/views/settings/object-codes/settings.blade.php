<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update other code settings" />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('object-codes.settings.update') }}">
                @csrf
                @method('PUT')

                <p class="mb-6 text-sm text-gray-500">Changes will affect the codes of both new and existing invoices.</p>

                <label class="flex items-center gap-2">
                    <input type="hidden" name="include_year_in_invoice_codes" value="0">
                    <input type="checkbox" name="include_year_in_invoice_codes" value="1"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           @checked(old('include_year_in_invoice_codes', $codeSetting->include_year_in_invoice_codes)) />
                    <span class="text-sm text-gray-700">Include year in invoice codes</span>
                </label>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-6">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('object-codes.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
