<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Multi-quote email templates" subtitle="Templates for emails sent with multi-quotes.">
            <x-slot name="actions">
                <a href="{{ route('multi-quote-email-templates.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New template') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Email subject', 'Email body', 'Actions']">
            @forelse ($templates as $template)
                <tr>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->subject }}</td>
                    <td class="text-muted">{{ Str::limit($template->body, 120) }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('multi-quote-email-templates.create', ['clone' => $template->id]) }}" class="action-icon" title="Clone"><i class="fas fa-copy"></i></a>
                        <a href="{{ route('multi-quote-email-templates.edit', $template) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('multi-quote-email-templates.destroy', $template)" message="Delete this template?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No multi-quote email templates yet</p>
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
