<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Multi-quote email templates" subtitle="Templates for emails sent with multi-quotes.">
            <x-slot name="actions">
                <a href="{{ route('multi-quote-email-templates.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New template') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Email subject', 'Email body', 'Actions']">
            @forelse ($templates as $template)
                <tr class="align-top transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $template->name }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $template->subject }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ Str::limit($template->body, 120) }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('multi-quote-email-templates.create', ['clone' => $template->id]) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Clone</a>
                            <a href="{{ route('multi-quote-email-templates.edit', $template) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('multi-quote-email-templates.destroy', $template)" message="Delete this template?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No multi-quote email templates yet</p>
                        <p class="mt-1 text-sm">Add your first template to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($templates->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $templates->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
