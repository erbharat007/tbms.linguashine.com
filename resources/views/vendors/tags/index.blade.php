<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Vendor tags" subtitle="Classification tags and labels for categorizing translators and agencies.">
            <a href="{{ route('vendor-tags.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> New tag
            </a>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('vendor-tags.index') }}" class="form-row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <x-text-input name="search" :value="$search" placeholder="Search tag name or description..." class="form-control-sm" />
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-outline-primary btn-sm mr-2">Filter</button>
                    @if ($search)
                        <a href="{{ route('vendor-tags.index') }}" class="btn btn-link btn-sm text-muted p-0">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <x-table :heads="['Tag', 'Color', 'Description', 'Assigned Vendors', 'Actions']">
            @forelse ($tags as $tag)
                @php
                    $badgeClass = match($tag->color) {
                        'green' => 'badge-success',
                        'red' => 'badge-danger',
                        'yellow' => 'badge-warning',
                        'info' => 'badge-info',
                        'secondary' => 'badge-secondary',
                        default => 'badge-primary',
                    };
                @endphp
                <tr>
                    <td class="font-weight-600">
                        <span class="badge {{ $badgeClass }} px-2 py-1">{{ $tag->name }}</span>
                    </td>
                    <td class="text-capitalize">{{ $tag->color }}</td>
                    <td>{{ $tag->description ?: '—' }}</td>
                    <td>
                        <span class="badge badge-light border">{{ $tag->vendors_count }}</span>
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('vendor-tags.edit', $tag) }}" class="action-icon text-secondary" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        <x-delete-form :action="route('vendor-tags.destroy', $tag)" message="Delete this tag?" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No vendor tags found.</td>
                </tr>
            @endforelse
        </x-table>

        @if ($tags->hasPages())
            <div class="card-footer py-2">
                {{ $tags->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
