<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Units" subtitle="Units of measure used for pricing (word, page, hour, etc.).">
            <x-slot name="actions">
                <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New unit') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom py-2">
            <p class="text-muted small mb-0">Translations are used only in generated PDF documents.</p>
        </div>

        <x-table :heads="['Unit', 'Is word', 'Is enabled', 'Actions']">
            @forelse ($units as $unit)
                <tr>
                    <td>{{ $unit->name }}</td>
                    <td>
                        @if ($unit->is_word)
                            <x-badge color="blue">Yes</x-badge>
                        @else
                            <span class="text-muted">No</span>
                        @endif
                    </td>
                    <td>
                        @if ($unit->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="text-right text-nowrap">
                        <form method="POST" action="{{ route('units.toggle', $unit) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-icon" title="{{ $unit->is_enabled ? 'Disable' : 'Enable' }}">
                                <i class="fas {{ $unit->is_enabled ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('units.edit', $unit) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('units.destroy', $unit)" message="Delete this unit?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No units yet</p>
                        <p class="small mb-0">Add your first unit to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($units->hasPages())
            <div class="card-body border-top">{{ $units->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
