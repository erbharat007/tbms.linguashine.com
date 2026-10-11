<x-app-layout>
    <x-slot name="header">
        <div class="row mb-2 align-items-center">
            <div class="col-sm">
                <ol class="breadcrumb bg-transparent p-0 mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                    <li class="breadcrumb-item active">{{ $client->name }}</li>
                </ol>
                <h1 class="m-0 h3">{{ $client->name }}</h1>
            </div>
            <div class="col-sm-auto mt-2 mt-sm-0 text-sm-right">
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-pen mr-1"></i> Update
                </a>
                <form method="POST" action="{{ route('clients.destroy', $client) }}" class="d-inline"
                      onsubmit="return confirm('Delete this client?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    @include('clients._tabs', ['active' => 'general'])

    <div class="tab-panel">
    <div class="row">
        <div class="col-lg-8">
            <x-card title="Client details" no-padding>
                <x-detail-row label="Name">{{ $client->name }}</x-detail-row>
                <x-detail-row label="Type">{{ ucfirst($client->type) }}</x-detail-row>
                <x-detail-row label="Status">
                    @if ($client->status)
                        <x-badge color="blue">{{ $client->status->name }}</x-badge>
                    @else
                        <span class="text-muted">—</span>
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
                        <a href="{{ $client->website }}" target="_blank" rel="noopener">{{ $client->website }}</a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </x-detail-row>
                <x-detail-row label="Note">{{ $client->note ?: '—' }}</x-detail-row>
                <x-detail-row label="Created at">{{ $client->created_at?->format('Y-m-d H:i') }}</x-detail-row>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Primary user" no-padding>
                @if ($contact)
                    <x-detail-row label="First name">{{ $contact->first_name }}</x-detail-row>
                    <x-detail-row label="Last name">{{ $contact->last_name }}</x-detail-row>
                    <x-detail-row label="Gender">{{ $contact->gender ? ucfirst($contact->gender) : '—' }}</x-detail-row>
                    <x-detail-row label="Email">
                        @if ($contact->email)
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </x-detail-row>
                    <x-detail-row label="Phone">{{ $contact->phone ?: '—' }}</x-detail-row>
                    <x-detail-row label="Skype">{{ $contact->skype ?: '—' }}</x-detail-row>
                    <x-detail-row label="Time zone">{{ $contact->timezone?->label ?: '—' }}</x-detail-row>
                    <x-detail-row label="Can log in">{{ $contact->can_login ? 'Yes' : 'No' }}</x-detail-row>
                @else
                    <div class="p-3 text-muted">No primary contact recorded.</div>
                @endif
            </x-card>

            <x-card title="Settings" no-padding>
                <x-detail-row label="Default legal entity">{{ $client->legalEntity?->name ?: '—' }}</x-detail-row>
                <x-detail-row label="Default currency">
                    {{ $client->currency ? $client->currency->code . ' - ' . $client->currency->name : '—' }}
                </x-detail-row>
            </x-card>
        </div>
    </div>
    </div>
</x-app-layout>
