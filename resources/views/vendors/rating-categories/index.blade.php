<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Rating categories" subtitle="Evaluation criteria and weightings for vendor quality ratings.">
            <a href="{{ route('vendor-rating-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> New rating category
            </a>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('vendor-rating-categories.index') }}" class="form-row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <x-text-input name="search" :value="$search" placeholder="Search categories..." class="form-control-sm" />
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-primary btn-sm mr-2">Filter</button>
                    @if ($search)
                        <a href="{{ route('vendor-rating-categories.index') }}" class="btn btn-link btn-sm text-muted p-0">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <x-table :heads="['Name', 'Description', 'Weight %', 'Status', 'Actions']">
            @forelse ($categories as $cat)
                <tr>
                    <td class="font-weight-600">
                        <a href="{{ route('vendor-rating-categories.edit', $cat) }}">{{ $cat->name }}</a>
                    </td>
                    <td>{{ $cat->description ?: '—' }}</td>
                    <td>
                        <span class="badge badge-light border font-weight-bold">{{ $cat->weight }}%</span>
                    </td>
                    <td>
                        @if ($cat->status === 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('vendor-rating-categories.edit', $cat) }}" class="action-icon text-secondary" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <x-delete-form :action="route('vendor-rating-categories.destroy', $cat)" message="Delete this rating category?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No rating categories found.</td>
                </tr>
            @endforelse
        </x-table>

        @if ($categories->hasPages())
            <div class="card-footer py-2">
                {{ $categories->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
