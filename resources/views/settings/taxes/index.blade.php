<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tax configuration" subtitle="Named taxes used on invoices.">
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    <a href="{{ route('default-taxes.index') }}">
                        <x-secondary-button type="button">View Default Taxes</x-secondary-button>
                    </a>
                    <a href="{{ route('taxes.create') }}">
                        <x-primary-button type="button">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            {{ __('New tax') }}
                        </x-primary-button>
                    </a>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Type', 'Sort order inside invoices', 'Actions']">
            @forelse ($taxes as $tax)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $tax->name }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $tax->type_label }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $tax->sort_order }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('taxes.edit', $tax) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('taxes.destroy', $tax)" message="Delete this tax?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No taxes yet</p>
                        <p class="mt-1 text-sm">Add your first tax to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($taxes->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $taxes->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
