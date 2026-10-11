<x-app-layout>
    <x-slot name="header">
        <x-page-header title="PDF templates" subtitle="Layout templates for generated PDFs (invoices, quotes, job POs).">
            <x-slot name="actions">
                <a href="{{ route('pdf-templates.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New PDF template') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Object', 'Name', 'Locale', 'Header', 'Footer', 'Actions']">
            @forelse ($templates as $template)
                <tr>
                    <td class="text-nowrap"><x-badge color="gray">{{ $template->object_type_label }}</x-badge></td>
                    <td class="text-nowrap">{{ $template->name }}</td>
                    <td class="text-nowrap">{{ $template->locale_label }}</td>
                    <td>@if (filled($template->header))<x-badge color="green">Yes</x-badge>@else<span class="text-muted">—</span>@endif</td>
                    <td>@if (filled($template->footer))<x-badge color="green">Yes</x-badge>@else<span class="text-muted">—</span>@endif</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('pdf-templates.create', ['clone' => $template->id]) }}" class="action-icon" title="Clone"><i class="fas fa-copy"></i></a>
                        <a href="{{ route('pdf-templates.edit', $template) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('pdf-templates.destroy', $template)" message="Delete this PDF template?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No PDF templates yet</p>
                        <p class="small mb-0">Add your first template to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($templates->hasPages())
            <div class="card-body border-top">{{ $templates->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
