<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Object codes" subtitle="Prefixes and numbering used to auto-generate reference codes.">
            <x-slot name="actions">
                <a href="{{ route('object-codes.next-ids.edit') }}">
                    <x-secondary-button type="button">Update next IDs</x-secondary-button>
                </a>
                <a href="{{ route('object-codes.prefixes.edit') }}">
                    <x-primary-button type="button">Update code prefixes</x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        <x-card no-padding>
            <x-table :heads="['Object', 'Code prefix', 'Next ID', 'Next code preview']">
                @foreach ($objectCodes as $code)
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $code->label }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $code->prefix }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $code->next_id ?? '-' }}</td>
                        <td class="px-4 py-3"><x-badge>{{ $code->next_code_preview }}</x-badge></td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        <x-card no-padding>
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 class="text-base font-semibold text-gray-900">Other settings</h3>
                <a href="{{ route('object-codes.settings.edit') }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Update</a>
            </div>
            <x-detail-row label="Include year in invoice codes">
                {{ $codeSetting->include_year_in_invoice_codes ? 'Yes' : 'No' }}
            </x-detail-row>
        </x-card>
    </div>
</x-app-layout>
