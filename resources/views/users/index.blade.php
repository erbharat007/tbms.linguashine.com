<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Users" subtitle="Manage team members and their roles.">
            <x-slot name="actions">
                <a href="{{ route('users.create') }}">
                    <x-primary-button type="button">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('Add User') }}
                    </x-primary-button>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="border-b border-gray-100 p-4">
            <form method="GET" class="flex gap-2">
                <x-text-input name="search" :value="$search" placeholder="Search by name or email" class="w-full sm:w-80" />
                <x-secondary-button type="submit">Search</x-secondary-button>
                @if (filled($search))
                    <a href="{{ route('users.index') }}" class="inline-flex items-center px-3 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Name', 'Email', 'Role', 'Manager', 'Status', 'Actions']">
            @forelse ($users as $user)
                <tr class="transition hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <x-badge color="blue">{{ $user->role?->name ?? '—' }}</x-badge>
                    </td>
                    <td class="px-4 py-3">
                        @if ($user->is_manager)
                            <x-badge color="blue">Yes</x-badge>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if ($user->is_active)
                            <x-badge color="green">Active</x-badge>
                        @else
                            <x-badge color="red">Inactive</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('users.edit', $user) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                            <x-delete-form :action="route('users.destroy', $user)" message="Delete this user?" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                        <p class="font-medium">No users found</p>
                        <p class="mt-1 text-sm">Try a different search, or add a new user.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($users->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">{{ $users->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
