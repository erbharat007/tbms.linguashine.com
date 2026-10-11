<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Clients" subtitle="Your client companies and individuals.">
            <x-slot name="actions">
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New client') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" class="form-inline">
                <div class="input-group mr-2">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name" style="min-width: 260px;">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                @if (filled($search))
                    <a href="{{ route('clients.index') }}" class="text-muted">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Name', 'Type', 'Status', 'Country', 'Primary user', 'Created at', 'Actions']">
            @forelse ($clients as $client)
                <tr>
                    <td>
                        <a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a>
                    </td>
                    <td>{{ ucfirst($client->type) }}</td>
                    <td>
                        @if ($client->status)
                            <x-badge color="blue">{{ $client->status->name }}</x-badge>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $client->country?->name ?: '—' }}</td>
                    <td>{{ $client->primaryContact?->full_name ?: '—' }}</td>
                    <td class="text-muted">{{ $client->created_at?->format('d M Y') }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('clients.edit', $client) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('clients.destroy', $client)" message="Delete this client?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No clients yet</p>
                        <p class="small mb-0">Add your first client to get started.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($clients->hasPages())
            <div class="card-body border-top">{{ $clients->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
