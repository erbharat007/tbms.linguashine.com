<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor applications" subtitle="Review incoming freelancer and agency applications." />
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('vendor-applications.index') }}" class="form-row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <x-text-input name="search" :value="$search" placeholder="Search by name, email, app number..." class="form-control-sm" />
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <x-select-input name="status" class="form-control-sm">
                        <option value="">All statuses</option>
                        <option value="pending" @selected($status === 'pending')>Pending</option>
                        <option value="approved" @selected($status === 'approved')>Approved</option>
                        <option value="rejected" @selected($status === 'rejected')>Rejected</option>
                    </x-select-input>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-primary btn-sm mr-2">Filter</button>
                    @if ($search || $status)
                        <a href="{{ route('vendor-applications.index') }}" class="btn btn-link btn-sm text-muted p-0">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <x-table :heads="['Application #', 'Applicant', 'Type', 'Country', 'Status', 'Date', 'Actions']">
            @forelse ($applications as $app)
                <tr>
                    <td class="font-weight-600">
                        <a href="{{ route('vendor-applications.show', $app) }}">{{ $app->application_number }}</a>
                    </td>
                    <td>
                        <div class="font-weight-bold">{{ $app->name }}</div>
                        <div class="small text-muted">{{ $app->email }}</div>
                    </td>
                    <td>
                        <span class="badge badge-light text-capitalize border">{{ $app->type }}</span>
                    </td>
                    <td>{{ $app->country?->name ?? '—' }}</td>
                    <td>
                        @if ($app->status === 'approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif ($app->status === 'rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $app->created_at->format('M d, Y') }}</td>
                    <td class="table-actions">
                        <a href="{{ route('vendor-applications.show', $app) }}" class="action-icon text-primary" title="View Application">
                            <i class="fas fa-eye"></i>
                        </a>
                        <x-delete-form :action="route('vendor-applications.destroy', $app)" message="Delete this application?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No applications found.</td>
                </tr>
            @endforelse
        </x-table>

        @if ($applications->hasPages())
            <div class="card-footer py-2">
                {{ $applications->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
