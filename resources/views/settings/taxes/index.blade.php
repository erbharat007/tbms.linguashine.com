<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Tax configuration" subtitle="Named taxes used on invoices.">
            <x-slot name="actions">
                <a href="{{ route('default-taxes.index') }}" class="btn btn-outline-secondary btn-sm">View Default Taxes</a>
                <a href="{{ route('taxes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New tax') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Type', 'Sort order inside invoices', 'Actions']">
            @forelse ($taxes as $tax)
                <tr>
                    <td>{{ $tax->name }}</td>
                    <td>{{ $tax->type_label }}</td>
                    <td>{{ $tax->sort_order }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('taxes.edit', $tax) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('taxes.destroy', $tax)" message="Delete this tax?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No taxes yet</p>
                        <p class="small mb-0">Add your first tax to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($taxes->hasPages())
            <div class="card-body border-top">{{ $taxes->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
