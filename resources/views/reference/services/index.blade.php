<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Services" subtitle="Types of work the agency offers.">
            <x-slot name="actions">
                <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New service') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom py-2">
            <p class="text-muted small mb-0">Translations are used only in generated PDF documents.</p>
        </div>

        <x-table :heads="['Service', 'Is enabled', 'Actions']">
            @forelse ($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>
                        @if ($service->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="text-right text-nowrap">
                        <form method="POST" action="{{ route('services.toggle', $service) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-icon" title="{{ $service->is_enabled ? 'Disable' : 'Enable' }}">
                                <i class="fas {{ $service->is_enabled ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('services.edit', $service) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('services.destroy', $service)" message="Delete this service?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No services yet</p>
                        <p class="small mb-0">Add your first service to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($services->hasPages())
            <div class="card-body border-top">{{ $services->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
