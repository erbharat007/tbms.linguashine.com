<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Roles" subtitle="Define roles and control which screens each can access.">
            <x-slot name="actions">
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add Role') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Description', 'Screens', 'Users', 'Actions']">
            @forelse ($roles as $role)
                <tr>
                    <td>
                        {{ $role->name }}
                        @if ($role->is_locked)
                            <x-badge color="yellow">Locked</x-badge>
                        @endif
                    </td>
                    <td>{{ $role->description ?: '—' }}</td>
                    <td>{{ $role->is_locked ? 'All screens' : $role->permissions_count }}</td>
                    <td>{{ $role->users_count }}</td>
                    <td class="text-right text-nowrap">
                        @if ($role->is_locked)
                            <span class="text-muted small">Protected</span>
                        @else
                            <a href="{{ route('roles.edit', $role) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <x-delete-form :action="route('roles.destroy', $role)" message="Delete this role?" />
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No roles yet</p>
                        <p class="small mb-0">Add a role to control screen access.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($roles->hasPages())
            <div class="card-body border-top">{{ $roles->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
