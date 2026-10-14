<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendors" subtitle="Your linguists, translation vendors, and freelancer agencies.">
            <x-slot name="actions">
                <a href="{{ route('vendors.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> {{ __('New vendor') }}
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" class="form-inline flex-wrap" style="gap: 10px;">
                <div class="input-group">
                    <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name, code, or city" style="min-width: 260px;">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <select name="type" class="form-control" onchange="this.form.submit()">
                    <option value="">All types</option>
                    <option value="individual" @selected($type === 'individual')>Individual</option>
                    <option value="company" @selected($type === 'company')>Company</option>
                </select>

                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="blocked" @selected($status === 'blocked')>Blocked</option>
                </select>

                @if (filled($search) || filled($type) || filled($status))
                    <a href="{{ route('vendors.index') }}" class="text-muted ml-2">Clear</a>
                @endif
            </form>
        </div>

        <x-table :heads="['Code', 'Name', 'Type', 'Status', 'Country', 'Primary user', 'Tags', 'Created at', 'Actions']">
            @forelse ($vendors as $vendor)
                <tr>
                    <td class="text-nowrap font-weight-bold text-secondary">
                        {{ $vendor->vendor_code ?: '—' }}
                    </td>
                    <td>
                        <a href="{{ route('vendors.show', $vendor) }}" class="font-weight-600">{{ $vendor->name }}</a>
                    </td>
                    <td class="text-nowrap">{{ ucfirst($vendor->type) }}</td>
                    <td class="text-nowrap">
                        @php
                            $badgeColor = match($vendor->status) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'blocked' => 'danger',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($vendor->status) }}</span>
                    </td>
                    <td class="text-nowrap">{{ $vendor->country?->name ?: '—' }}</td>
                    <td class="text-nowrap">
                        @if ($vendor->primaryContact)
                            <span>{{ $vendor->primaryContact->full_name }}</span>
                            @if ($vendor->primaryContact->email)
                                <br><small class="text-muted">{{ $vendor->primaryContact->email }}</small>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap" style="gap: 4px;">
                            @forelse ($vendor->tags as $t)
                                <span class="badge badge-{{ $t->color }}">{{ $t->name }}</span>
                            @empty
                                <span class="text-muted small">—</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="text-muted text-nowrap">{{ $vendor->created_at?->format('d M Y') }}</td>
                    <td class="text-right text-nowrap">
                        <a href="{{ route('vendors.show', $vendor) }}" class="action-icon text-info" title="View details"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('vendors.edit', $vendor) }}" class="action-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        <x-delete-form :action="route('vendors.destroy', $vendor)" message="Delete this vendor?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <p class="font-weight-bold mb-1">No vendors found</p>
                        <p class="small mb-0">Add your first vendor or adjust your search filters.</p>
                    </td>
                </tr>
            @endforelse
        </x-table>

        @if ($vendors->hasPages())
            <div class="card-body border-top">{{ $vendors->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
