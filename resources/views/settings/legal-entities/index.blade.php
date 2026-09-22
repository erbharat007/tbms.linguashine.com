<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Legal entities" subtitle="Your billing identities used on invoices.">
            <x-slot name="actions">
                <a href="{{ route('legal-entities.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New legal entity') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Logo', 'Name', 'Country', 'Tax number', 'VAT number', 'Phone', 'Actions']">
            @forelse ($legalEntities as $entity)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if ($entity->logo_url)
                            <img src="{{ $entity->logo_url }}" alt="{{ $entity->name }}" class="h-8 w-8 rounded object-contain">
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded bg-gray-100 text-xs font-semibold text-gray-400">
                                {{ strtoupper(substr($entity->name, 0, 1)) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <a href="{{ route('legal-entities.edit', $entity) }}" class="hover:text-indigo-600">{{ $entity->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $entity->country?->name ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $entity->tax_number ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $entity->vat_number ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $entity->phone ?: '—' }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('legal-entities.edit', $entity) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('legal-entities.destroy', $entity)" message="Delete this legal entity?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No legal entities yet</p>
                        <p class="mt-1 text-sm">Add your first legal entity to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($legalEntities->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $legalEntities->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
