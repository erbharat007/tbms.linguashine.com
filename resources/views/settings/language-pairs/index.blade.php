<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Language pairs" subtitle="Source → target language combinations used across the system.">
            <x-slot name="actions">
                <a href="{{ route('languages.index') }}">
                    <x-secondary-button type="button">Available languages</x-secondary-button>
                </a>
                <a href="{{ route('language-pairs.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('Add language pairs') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Language pair', 'Tag', 'Is enabled', 'Actions']">
            @forelse ($pairs as $pair)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $pair->label }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $pair->tag }}</td>
                    <td class="px-4 py-3">
                        @if ($pair->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <form method="POST" action="{{ route('language-pairs.toggle', $pair) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                    {{ $pair->is_enabled ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <x-delete-form :action="route('language-pairs.destroy', $pair)" message="Remove this language pair?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No language pairs yet</p>
                        <p class="mt-1 text-sm">Use “Add language pairs” to create source → target combinations.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($pairs->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $pairs->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
