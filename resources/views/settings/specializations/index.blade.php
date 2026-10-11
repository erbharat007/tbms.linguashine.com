<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Specializations" subtitle="Subject areas for projects and vendors.">
            <x-slot name="actions">
                <a href="{{ route('specializations.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New specialization') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Specialization', 'Is enabled', 'Actions']">
            @forelse ($specializations as $specialization)
                <tr>
                    <td>{{ $specialization->name }}</td>
                    <td>
                        @if ($specialization->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="text-right text-nowrap">
                        <form method="POST" action="{{ route('specializations.toggle', $specialization) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-icon" title="{{ $specialization->is_enabled ? 'Disable' : 'Enable' }}">
                                <i class="fas {{ $specialization->is_enabled ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('specializations.edit', $specialization) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('specializations.destroy', $specialization)" message="Delete this specialization?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No specializations yet</p>
                        <p class="small mb-0">Add your first specialization to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($specializations->hasPages())
            <div class="card-body border-top">{{ $specializations->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
