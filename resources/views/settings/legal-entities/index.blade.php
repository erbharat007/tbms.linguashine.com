<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Legal entities" subtitle="Your billing identities used on invoices.">
            <x-slot name="actions">
                <a href="{{ route('legal-entities.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New legal entity') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Logo', 'Name', 'Country', 'Tax number', 'VAT number', 'Phone', 'Actions']">
            @forelse ($legalEntities as $entity)
                <tr>
                    <td>
                        @if ($entity->logo_url)
                            <img src="{{ $entity->logo_url }}" alt="{{ $entity->name }}" class="rounded" style="height: 32px; width: 32px; object-fit: contain;">
                        @else
                            <span class="d-inline-flex align-items-center justify-content-center rounded bg-light text-muted font-weight-bold" style="height: 32px; width: 32px; font-size: 0.75rem;">
                                {{ strtoupper(substr($entity->name, 0, 1)) }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('legal-entities.edit', $entity) }}">{{ $entity->name }}</a>
                    </td>
                    <td>{{ $entity->country?->name ?: '—' }}</td>
                    <td>{{ $entity->tax_number ?: '—' }}</td>
                    <td>{{ $entity->vat_number ?: '—' }}</td>
                    <td>{{ $entity->phone ?: '—' }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('legal-entities.edit', $entity) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('legal-entities.destroy', $entity)" message="Delete this legal entity?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No legal entities yet</p>
                        <p class="small mb-0">Add your first legal entity to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($legalEntities->hasPages())
            <div class="card-body border-top">{{ $legalEntities->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
