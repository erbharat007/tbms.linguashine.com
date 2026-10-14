<x-app-layout>
    <x-slot name="header">
        <div class="row mb-2 align-items-center">
            <div class="col-sm">
                <ol class="breadcrumb bg-transparent p-0 mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendors.show', $vendor) }}">{{ $vendor->name }}</a></li>
                    <li class="breadcrumb-item active">Contact persons</li>
                </ol>
                <h1 class="m-0 h3 font-weight-bold">{{ $vendor->name }} — Contact persons</h1>
            </div>
            <div class="col-sm-auto mt-2 mt-sm-0 text-sm-right">
                <a href="{{ route('vendor-contacts.create', ['vendor_id' => $vendor->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> New contact
                </a>
            </div>
        </div>
    </x-slot>

    @include('vendors._tabs', ['active' => 'contacts'])

    <div class="tab-panel">
        <x-card no-padding>
            <x-table :heads="['Name', 'Designation', 'Gender', 'Email', 'Phone', 'Skype', 'Time zone', 'Actions']">
                @forelse ($contacts as $contact)
                    <tr>
                        <td class="text-nowrap font-weight-600">
                            {{ $contact->full_name }}
                            @if ($contact->is_primary)
                                <span class="badge badge-primary ml-1">Primary</span>
                            @endif
                        </td>
                        <td>{{ $contact->designation ?: '—' }}</td>
                        <td>{{ $contact->gender ? ucfirst($contact->gender) : '—' }}</td>
                        <td>
                            @if ($contact->email)
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $contact->phone ?: '—' }}</td>
                        <td>{{ $contact->skype ?: '—' }}</td>
                        <td>{{ $contact->timezone?->label ?: '—' }}</td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('vendor-contacts.edit', $contact) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <x-delete-form :action="route('vendor-contacts.destroy', $contact)" message="Delete this contact?" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <p class="font-weight-bold mb-1">No contacts for this vendor</p>
                            <p class="small mb-0">Add a contact person for communication.</p>
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
</x-app-layout>
