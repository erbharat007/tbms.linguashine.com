<x-app-layout>
    <x-slot name="header">
        <x-page-header title="JOB CHAIN TEMPLATE LIST" subtitle="Predefined sequence of workflow services (e.g., Translation → Editing → Proofreading).">
            <x-slot name="actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-briefcase mr-1"></i> Jobs
                </a>
                <a href="{{ route('job-chain-templates.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus mr-1"></i> New template
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center">
            <h5 class="text-uppercase font-weight-bold mb-0" style="color: #009688; letter-spacing: 0.5px;">JOB CHAIN TEMPLATE LIST</h5>
            <a href="{{ route('job-chain-templates.create') }}" class="btn btn-primary btn-sm shadow-sm">
                New template
            </a>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover table-striped mb-0 text-nowrap">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 25%;">Name</th>
                        <th>Workflow Chain</th>
                        <th>Created by</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $tmpl)
                        <tr>
                            <td class="font-weight-bold">
                                <a href="{{ route('job-chain-templates.edit', $tmpl) }}" class="text-primary font-weight-bold">
                                    {{ $tmpl->name }}
                                </a>
                                @if ($tmpl->description)
                                    <div class="small text-muted">{{ $tmpl->description }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center flex-wrap">
                                    @php $steps = $tmpl->steps ?? []; @endphp
                                    @foreach ($steps as $idx => $step)
                                        @php
                                            $srvId = is_array($step) ? ($step['service_id'] ?? null) : $step;
                                            $srv = $srvId && isset($services[$srvId]) ? $services[$srvId] : null;
                                        @endphp
                                        <span class="badge badge-info px-2 py-1">{{ $srv?->name ?? "Service #{$srvId}" }}</span>
                                        @if ($idx < count($steps) - 1)
                                            <i class="fas fa-arrow-right text-muted mx-2 font-size-12"></i>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>{{ $tmpl->creator?->name ?? 'System' }}</td>
                            <td class="text-right">
                                <a href="{{ route('job-chain-templates.edit', $tmpl) }}" class="btn btn-outline-secondary btn-sm mr-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('job-chain-templates.destroy', $tmpl) }}" class="d-inline" onsubmit="return confirm('Delete template {{ $tmpl->name }}?');">
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
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-link fa-2x mb-2 d-block text-secondary"></i>
                                No job chain templates found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($templates->hasPages())
            <div class="card-footer bg-white border-top py-2">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
