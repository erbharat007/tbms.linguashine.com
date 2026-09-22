<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Clients" subtitle="Your client companies and individuals.">
            <x-slot name="actions">
                <a href="{{ route('clients.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('New client') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="border-b border-gray-100 p-4">
            <form method="GET" class="flex gap-2">
                <x-text-input name="search" :value="$search" placeholder="Search by name" class="w-full sm:w-80" />
                <x-secondary-button type="submit">Search</x-secondary-button>
                @if (filled($search))
                    <a href="{{ route('clients.index') }}" class="inline-flex items-center px-3 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Name', 'Type', 'Status', 'Country', 'Primary user', 'Created at', 'Actions']">
            @forelse ($clients as $client)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        <a href="{{ route('clients.show', $client) }}" class="hover:text-indigo-600">{{ $client->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ ucfirst($client->type) }}</td>
                    <td class="px-4 py-3">
                        @if ($client->status)
                            <x-badge color="blue">{{ $client->status->name }}</x-badge>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $client->country?->name ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $client->primaryContact?->full_name ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $client->created_at?->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('clients.edit', $client) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('clients.destroy', $client)" message="Delete this client?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No clients yet</p>
                        <p class="mt-1 text-sm">Add your first client to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($clients->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $clients->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
