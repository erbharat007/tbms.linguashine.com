<x-app-layout>
    <x-slot name="header">
        <x-page-header title="CAT log conversion schemes" subtitle="Schemes to convert CAT tool word counts into weighted payable and billable volumes.">
            <x-slot name="actions">
                <a href="{{ route('cat-log-templates.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> New scheme
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Default for clients (account)</th>
                            <th class="text-center">Default in client profiles</th>
                            <th class="text-center">Default for vendors (account)</th>
                            <th class="text-center">Default in vendor profiles</th>
                            <th>Created by</th>
                            <th>Created on</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schemes as $scheme)
                            <tr>
                                <td class="font-weight-bold align-middle">
                                    <a href="{{ route('cat-log-templates.edit', $scheme) }}">
                                        {{ $scheme->name }}
                                    </a>
                                </td>
                                <td class="text-center align-middle">
                                    @if ($scheme->is_default_client_account)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-light text-muted">No</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if ($scheme->is_default_client_profile)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-light text-muted">No</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if ($scheme->is_default_vendor_account)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-light text-muted">No</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if ($scheme->is_default_vendor_profile)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-light text-muted">No</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $scheme->creator?->name ?? 'System' }}</td>
                                <td class="align-middle">{{ $scheme->created_at ? $scheme->created_at->format('Y-m-d H:i') : '—' }}</td>
                                <td class="text-right align-middle">
                                    <a href="{{ route('cat-log-templates.edit', $scheme) }}" class="btn btn-outline-primary btn-xs mr-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('cat-log-templates.destroy', $scheme) }}" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this CAT log scheme?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No CAT log conversion schemes created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($schemes->hasPages())
            <div class="card-footer bg-white border-top-0">
                {{ $schemes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
