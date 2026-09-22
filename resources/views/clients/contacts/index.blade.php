<x-app-layout>
    <x-slot name="header">
        <nav class="mb-1 text-sm text-gray-500">
            <a href="{{ route('clients.index') }}" class="hover:text-indigo-600">Clients</a>
            <span class="mx-1">/</span>
            <a href="{{ route('clients.show', $client) }}" class="hover:text-indigo-600">{{ $client->name }}</a>
            <span class="mx-1">/</span>
            <span class="text-gray-700">Contact persons</span>
        </nav>
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $client->name }}</h2>
    </x-slot>

    <div class="space-y-6">
        @include('clients._tabs', ['active' => 'contacts'])

        <div class="flex justify-end">
            <a href="{{ route('clients.contacts.create', $client) }}">
                <x-primary-button type="button">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    {{ __('New contact person') }}
                </x-primary-button>
            </a>
        </div>

        <x-card no-padding>
            <div class="overflow-x-auto">
                <x-table :heads="['Name', 'Gender', 'Position', 'Email', 'Phone', 'Skype', 'Note', 'Is active', 'Is invoicing', 'Primary', 'Actions']">
                    @forelse ($contacts as $contact)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">{{ $contact->full_name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $contact->gender ? ucfirst($contact->gender) : '—' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $contact->position ?: '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                                @if ($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $contact->email }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $contact->phone ?: '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-700">{{ $contact->skype ?: '—' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ \Illuminate\Support\Str::limit($contact->note, 30) }}</td>
                            <td class="px-4 py-3">
                                @if ($contact->is_active)
                                    <x-badge color="green">Yes</x-badge>
                                @else
                                    <x-badge color="gray">No</x-badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($contact->is_invoicing)
                                    <x-badge color="blue">Yes</x-badge>
                                @else
                                    <span class="text-gray-400">No</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($contact->is_primary)
                                    <x-badge color="indigo">Primary</x-badge>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('clients.contacts.edit', [$client, $contact]) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">Edit</a>
                                    @unless ($contact->is_primary)
                                        <x-delete-form :action="route('clients.contacts.destroy', [$client, $contact])" message="Delete this contact person?" />
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center text-gray-500">
                                <p class="font-medium">No contact persons yet</p>
                                <p class="mt-1 text-sm">Add a contact person to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </x-card>
    </div>
</x-app-layout>
