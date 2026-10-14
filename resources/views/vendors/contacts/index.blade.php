<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor contacts" subtitle="Contact persons across all registered vendors.">
            <x-slot name="actions">
                <a href="{{ route('vendor-contacts.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New contact person') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" class="form-inline flex-wrap" style="gap: 10px;">
                <div class="input-group">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name, email, or vendor" style="min-width: 260px;">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <select name="vendor_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All vendors</option>
                    @foreach ($vendors as $id => $name)
                        <option value="{{ $id }}" @selected($vendorId === $id)>{{ $name }}</option>
                    @endforeach
                </select>

                @if (filled($search) || $vendorId > 0)
                    <a href="{{ route('vendor-contacts.index') }}" class="text-muted ml-2">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Name', 'Vendor', 'Designation', 'Email', 'Phone', 'Skype', 'Time zone', 'Actions']">
            @forelse ($contacts as $contact)
                <tr>
                    <td class="text-nowrap font-weight-600">
                        {{ $contact->full_name }}
                        @if ($contact->is_primary)
                            <span class="badge badge-primary ml-1" title="Primary user">Primary</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        @if ($contact->vendor)
                            <a href="{{ route('vendors.show', $contact->vendor) }}">{{ $contact->vendor->name }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-nowrap">{{ $contact->designation ?: '—' }}</td>
                    <td class="text-nowrap">
                        @if ($contact->email)
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-nowrap">{{ $contact->phone ?: '—' }}</td>
                    <td class="text-nowrap">{{ $contact->skype ?: '—' }}</td>
                    <td class="text-nowrap text-muted">{{ $contact->timezone?->label ?: '—' }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('vendor-contacts.edit', $contact) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('vendor-contacts.destroy', $contact)" message="Delete this contact person?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No vendor contacts recorded</p>
                        <p class="small mb-0">Add contact persons to easily reach out to linguists and coordinators.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($contacts->hasPages())
            <div class="card-body border-top">{{ $contacts->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
