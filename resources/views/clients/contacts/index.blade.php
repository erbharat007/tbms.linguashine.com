<x-app-layout>
    <x-slot name="header">
        <ol class="breadcrumb bg-transparent p-0 mb-1 small">
            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
            <li class="breadcrumb-item active">{{ $client->name }}</li>
        </ol>
        <h1 class="m-0 h3">{{ $client->name }}</h1>
    </x-slot>

    @include('clients._tabs', ['active' => 'contacts'])

    <div class="tab-panel">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('clients.contacts.create', $client) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> {{ __('New contact person') }}
            </a>
        </div>

        <x-card no-padding>
            <x-table :heads="['Name', 'Email', 'Phone', 'Is active', 'Primary', 'Actions']">
                @forelse ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->full_name }}</td>
                        <td>
                            @if ($contact->email)
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $contact->phone ?: '—' }}</td>
                        <td>
                            @if ($contact->is_active)
                                <x-badge color="green">Yes</x-badge>
                            @else
                                <x-badge color="gray">No</x-badge>
                            @endif
                        </td>
                        <td>
                            @if ($contact->is_primary)
                                <x-badge color="indigo">Primary</x-badge>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('clients.contacts.edit', [$client, $contact]) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            @unless ($contact->is_primary)
                                <x-delete-form :action="route('clients.contacts.destroy', [$client, $contact])" message="Delete this contact person?" />
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <p class="font-weight-bold mb-1">No contact persons yet</p>
                            <p class="small mb-0">Add a contact person to get started.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
</x-app-layout>
