<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Object codes" subtitle="Prefixes and numbering used to auto-generate reference codes.">
            <x-slot name="actions">
                <a href="{{ route('object-codes.next-ids.edit') }}" class="btn btn-outline-secondary btn-sm">Update next IDs</a>
                <a href="{{ route('object-codes.prefixes.edit') }}" class="btn btn-primary btn-sm">Update code prefixes</a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Object', 'Code prefix', 'Next ID', 'Next code preview']">
            @foreach ($objectCodes as $code)
                <tr>
                    <td>{{ $code->label }}</td>
                    <td>{{ $code->prefix }}</td>
                    <td>{{ $code->next_id ?? '—' }}</td>
                    <td><x-badge>{{ $code->next_code_preview }}</x-badge></td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    <x-card title="Other settings" no-padding class="mt-4">
        <x-slot name="actions">
            <a href="{{ route('object-codes.settings.edit') }}" class="btn btn-primary btn-sm">Update</a>
        </x-slot>
        <div class="detail-row border-bottom py-2 px-3">
            <div class="detail-row-label text-muted text-nowrap mr-3" style="flex-basis:auto; max-width:none;">Include year in invoice codes</div>
            <div class="detail-row-value">{{ $codeSetting->include_year_in_invoice_codes ? 'Yes' : 'No' }}</div>
        </div>
    </x-card>
</x-app-layout>
