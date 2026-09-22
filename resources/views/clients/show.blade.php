<x-app-layout>
    <x-slot name="header">
        <nav class="mb-1 text-sm text-gray-500">
            <a href="{{ route('clients.index') }}" class="hover:text-indigo-600">Clients</a>
            <span class="mx-1">/</span>
            <span class="text-gray-700">{{ $client->name }}</span>
        </nav>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $client->name }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('clients.edit', $client) }}">
                    <x-primary-button type="button">Update</x-primary-button>
                </a>
                <form method="POST" action="{{ route('clients.destroy', $client) }}"
                      onsubmit="return confirm('Delete this client?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 shadow-sm transition hover:bg-red-50">Delete</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @include('clients._tabs', ['active' => 'general'])

        {{-- Client details --}}
        <x-card title="Client details" no-padding>
            <x-detail-row label="Name">{{ $client->name }}</x-detail-row>
            <x-detail-row label="Type">{{ ucfirst($client->type) }}</x-detail-row>
            <x-detail-row label="Status">
                @if ($client->status)
                    <x-badge color="blue">{{ $client->status->name }}</x-badge>
                @else
                    <span class="text-gray-400">—</span>
                @endif
            </x-detail-row>
            <x-detail-row label="Source">{{ $client->source?->name ?: '—' }}</x-detail-row>
            <x-detail-row label="Tax number">{{ $client->tax_number ?: '—' }}</x-detail-row>
            <x-detail-row label="VAT number">{{ $client->vat_number ?: '—' }}</x-detail-row>
            <x-detail-row label="Country">{{ $client->country?->name ?: '—' }}</x-detail-row>
            <x-detail-row label="Region / State">{{ $client->region ?: '—' }}</x-detail-row>
            <x-detail-row label="City">{{ $client->city ?: '—' }}</x-detail-row>
            <x-detail-row label="Postal / Zip code">{{ $client->postal_code ?: '—' }}</x-detail-row>
            <x-detail-row label="Address">{{ $client->address ?: '—' }}</x-detail-row>
            <x-detail-row label="Website">
                @if ($client->website)
                    <a href="{{ $client->website }}" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-800">{{ $client->website }}</a>
                @else
                    <span class="text-gray-400">—</span>
                @endif
            </x-detail-row>
            <x-detail-row label="Note">{{ $client->note ?: '—' }}</x-detail-row>
            <x-detail-row label="Created at">{{ $client->created_at?->format('Y-m-d H:i') }}</x-detail-row>
        </x-card>

        {{-- Primary user --}}
        <x-card title="Primary user" no-padding>
            @if ($contact)
                <x-detail-row label="First name">{{ $contact->first_name }}</x-detail-row>
                <x-detail-row label="Last name">{{ $contact->last_name }}</x-detail-row>
                <x-detail-row label="Gender">{{ $contact->gender ? ucfirst($contact->gender) : '—' }}</x-detail-row>
                <x-detail-row label="Email">
                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="text-indigo-600 hover:text-indigo-800">{{ $contact->email }}</a>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </x-detail-row>
                <x-detail-row label="Phone">{{ $contact->phone ?: '—' }}</x-detail-row>
                <x-detail-row label="Skype">{{ $contact->skype ?: '—' }}</x-detail-row>
                <x-detail-row label="Time zone">{{ $contact->timezone?->label ?: '—' }}</x-detail-row>
                <x-detail-row label="Can log in">{{ $contact->can_login ? 'Yes' : 'No' }}</x-detail-row>
            @else
                <div class="px-4 py-6 text-sm text-gray-500">No primary contact recorded.</div>
            @endif
        </x-card>

        {{-- Settings --}}
        <x-card title="Settings" no-padding>
            <x-detail-row label="Default legal entity">{{ $client->legalEntity?->name ?: '—' }}</x-detail-row>
            <x-detail-row label="Default currency">
                {{ $client->currency ? $client->currency->code . ' - ' . $client->currency->name : '—' }}
            </x-detail-row>
        </x-card>
    </div>
</x-app-layout>
