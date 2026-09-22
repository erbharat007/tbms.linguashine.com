<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Roles" subtitle="Define roles and control which screens each can access.">
            <x-slot name="actions">
                <a href="{{ route('roles.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('Add Role') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <x-table :heads="['Name', 'Description', 'Screens', 'Users', 'Actions']">
            @forelse ($roles as $role)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-gray-900">{{ $role->name }}</span>
                            @if ($role->is_locked)
                                <x-badge color="yellow">Locked</x-badge>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $role->description ?: '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $role->is_locked ? 'All screens' : $role->permissions_count }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $role->users_count }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            @if ($role->is_locked)
                                <span class="text-sm text-gray-400">Protected</span>
                            @else
                                <a href="{{ route('roles.edit', $role) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                                <x-delete-form :action="route('roles.destroy', $role)" message="Delete this role?" />
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No roles yet</p>
                        <p class="mt-1 text-sm">Add a role to control screen access.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($roles->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $roles->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
