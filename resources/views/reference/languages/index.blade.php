<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Languages" subtitle="Languages used across projects and rates.">
            <x-slot name="actions">
                <a href="{{ route('languages.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add Language') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Code', 'Actions']">
            @forelse ($languages as $language)
                <tr>
                    <td>{{ $language->name }}</td>
                    <td>{{ $language->code }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('languages.edit', $language) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('languages.destroy', $language)" message="Delete this language?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No languages yet</p>
                        <p class="small mb-0">Add your first language to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($languages->hasPages())
            <div class="card-body border-top">{{ $languages->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
