<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Languages" subtitle="Languages used across projects and rates.">
            <x-slot name="actions">
                <a href="{{ route('languages.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('Add Language') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Code', 'Actions']">
            @forelse ($languages as $language)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $language->name }}</td>
                    <td class="px-4 py-3"><x-badge>{{ $language->code }}</x-badge></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('languages.edit', $language) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('languages.destroy', $language)" message="Delete this language?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No languages yet</p>
                        <p class="mt-1 text-sm">Add your first language to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($languages->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $languages->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
