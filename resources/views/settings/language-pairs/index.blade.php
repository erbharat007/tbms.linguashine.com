<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Language pairs" subtitle="Source → target language combinations used across the system.">
            <x-slot name="actions">
                <a href="{{ route('languages.index') }}" class="btn btn-outline-secondary btn-sm">Available languages</a>
                <a href="{{ route('language-pairs.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add language pairs') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Language pair', 'Tag', 'Is enabled', 'Actions']">
            @forelse ($pairs as $pair)
                <tr>
                    <td>{{ $pair->label }}</td>
                    <td>{{ $pair->tag }}</td>
                    <td>
                        @if ($pair->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="text-right text-nowrap">
                        <form method="POST" action="{{ route('language-pairs.toggle', $pair) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="action-icon" title="{{ $pair->is_enabled ? 'Disable' : 'Enable' }}">
                                <i class="fas {{ $pair->is_enabled ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                            </button>
                        </form>
                        <x-delete-form :action="route('language-pairs.destroy', $pair)" message="Remove this language pair?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No language pairs yet</p>
                        <p class="small mb-0">Use "Add language pairs" to create source → target combinations.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($pairs->hasPages())
            <div class="card-body border-top">{{ $pairs->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
