<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Specializations" subtitle="Subject areas for projects and vendors.">
            <x-slot name="actions">
                <a href="{{ route('specializations.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New specialization') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Specialization', 'Is enabled', 'Actions']">
            @forelse ($specializations as $specialization)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $specialization->name }}</td>
                    <td class="px-4 py-3">
                        @if ($specialization->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <form method="POST" action="{{ route('specializations.toggle', $specialization) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                    {{ $specialization->is_enabled ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <a href="{{ route('specializations.edit', $specialization) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('specializations.destroy', $specialization)" message="Delete this specialization?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No specializations yet</p>
                        <p class="mt-1 text-sm">Add your first specialization to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($specializations->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $specializations->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
