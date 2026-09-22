<x-app-layout>
    <x-slot name="header">
        <x-page-header title="PDF templates" subtitle="Layout templates for generated PDFs (invoices, quotes, job POs).">
            <x-slot name="actions">
                <a href="{{ route('pdf-templates.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New PDF template') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="overflow-x-auto">
            <x-table :heads="['Object', 'Name', 'Page size', 'Orientation', 'Top margin', 'Bottom margin', 'Locale', 'Header', 'Footer', 'Common note', 'Include CAT log', 'Actions']">
                @forelse ($templates as $template)
                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-4 py-3"><x-badge color="gray">{{ $template->object_type_label }}</x-badge></td>
                        <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $template->name }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $template->page_size }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $template->page_orientation_label }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $template->margin_top }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $template->margin_bottom }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $template->locale_label }}</td>
                        <td class="px-4 py-3">@if (filled($template->header))<x-badge color="green">Yes</x-badge>@else<span class="text-gray-400">—</span>@endif</td>
                        <td class="px-4 py-3">@if (filled($template->footer))<x-badge color="green">Yes</x-badge>@else<span class="text-gray-400">—</span>@endif</td>
                        <td class="px-4 py-3">@if (filled($template->common_note))<x-badge color="green">Yes</x-badge>@else<span class="text-gray-400">—</span>@endif</td>
                        <td class="px-4 py-3">
                            @if ($template->include_cat_log)
                                <x-badge color="blue">Yes</x-badge>
                            @else
                                <span class="text-gray-400">No</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('pdf-templates.create', ['clone' => $template->id]) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Clone</a>
                                <a href="{{ route('pdf-templates.edit', $template) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                                <x-delete-form :action="route('pdf-templates.destroy', $template)" message="Delete this PDF template?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="px-4 py-12 text-center text-gray-500">
                            <p class="font-medium">No PDF templates yet</p>
                            <p class="mt-1 text-sm">Add your first template to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        @if ($templates->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $templates->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
