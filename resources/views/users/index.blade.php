<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Users" subtitle="Manage team members and their roles.">
            <x-slot name="actions">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add User') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" class="form-inline">
                <div class="input-group input-group-sm mr-2">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name or email" style="min-width: 260px;">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                @if (filled($search))
                    <a href="{{ route('users.index') }}" class="text-muted">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Name', 'Email', 'Role', 'Manager', 'Status', 'Actions']">
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->name ?? '—' }}</td>
                    <td>{{ $user->is_manager ? 'Yes' : 'No' }}</td>
                    <td>
                        @if ($user->is_active)
                            <x-badge color="green">Active</x-badge>
                        @else
                            <x-badge color="red">Inactive</x-badge>
                        @endif
                    </td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('users.edit', $user) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('users.destroy', $user)" message="Delete this user?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No users found</p>
                        <p class="small mb-0">Try a different search, or add a new user.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($users->hasPages())
            <div class="card-body border-top">{{ $users->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
