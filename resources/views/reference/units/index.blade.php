<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Units" subtitle="Units of measure used for pricing (word, page, hour, etc.).">
            <x-slot name="actions">
                <a href="{{ route('units.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New unit') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="border-b border-gray-100 px-4 py-3">
            <p class="text-sm text-gray-500">Translations are used only in generated PDF documents.</p>
        </div>

        <x-table :heads="['Unit', 'Is word', 'Is enabled', 'Actions']">
            @forelse ($units as $unit)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $unit->name }}</td>
                    <td class="px-4 py-3">
                        @if ($unit->is_word)
                            <x-badge color="blue">Yes</x-badge>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($unit->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <form method="POST" action="{{ route('units.toggle', $unit) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                    {{ $unit->is_enabled ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <a href="{{ route('units.edit', $unit) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('units.destroy', $unit)" message="Delete this unit?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No units yet</p>
                        <p class="mt-1 text-sm">Add your first unit to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($units->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $units->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
