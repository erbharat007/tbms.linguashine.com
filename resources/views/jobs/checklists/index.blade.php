<x-app-layout>
    <x-slot name="header">
        <x-page-header title="JOB CHECKLISTS" subtitle="QA checks and procedural requirements that linguists must confirm before job delivery.">
            <x-slot name="actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-briefcase mr-1"></i> Jobs
                </a>
                <a href="{{ route('job-checklists.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    New checklist
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center">
            <h5 class="text-uppercase font-weight-bold mb-0" style="color: #009688; letter-spacing: 0.5px;">JOB CHECKLISTS</h5>
            <a href="{{ route('job-checklists.create') }}" class="btn btn-primary btn-sm shadow-sm">
                New checklist
            </a>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped mb-0 text-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 25%;">Name ⇅</th>
                        <th>Is enabled</th>
                        <th>Number of items</th>
                        <th>Note for vendor</th>
                        <th>Updated by</th>
                        <th>Updated at</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($checklists as $chk)
                        <tr>
                            <td class="font-weight-bold">
                                <a href="{{ route('job-checklists.edit', $chk) }}" class="text-primary font-weight-bold">
                                    {{ $chk->name }}
                                </a>
                            </td>
                            <td>
                                @if ($chk->is_enabled)
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Yes</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">No</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light border font-weight-bold">{{ $chk->number_of_items }}</span>
                            </td>
                            <td class="text-truncate" style="max-width: 250px;">
                                {{ $chk->note_for_vendor ?: '—' }}
                            </td>
                            <td>{{ $chk->updater?->name ?? $chk->creator?->name ?? 'System' }}</td>
                            <td>{{ $chk->updated_at ? $chk->updated_at->format('M d, Y H:i') : '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('job-checklists.edit', $chk) }}" class="btn btn-outline-secondary btn-sm mr-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('job-checklists.destroy', $chk) }}" class="d-inline" onsubmit="return confirm('Delete checklist {{ $chk->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-tasks fa-2x mb-2 d-block text-secondary"></i>
                                No results found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($checklists->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $checklists->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
