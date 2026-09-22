<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Services" subtitle="Types of work the agency offers.">
            <x-slot name="actions">
                <a href="{{ route('services.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New service') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="border-b border-gray-100 px-4 py-3">
            <p class="text-sm text-gray-500">Translations are used only in generated PDF documents.</p>
        </div>

        <x-table :heads="['Service', 'Is enabled', 'Actions']">
            @forelse ($services as $service)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $service->name }}</td>
                    <td class="px-4 py-3">
                        @if ($service->is_enabled)
                            <x-badge color="green">Yes</x-badge>
                        @else
                            <x-badge color="gray">No</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <form method="POST" action="{{ route('services.toggle', $service) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                    {{ $service->is_enabled ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            <a href="{{ route('services.edit', $service) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('services.destroy', $service)" message="Delete this service?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No services yet</p>
                        <p class="mt-1 text-sm">Add your first service to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($services->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $services->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
