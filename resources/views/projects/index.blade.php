<x-app-layout>
    <x-slot name="header">
        <x-page-header title="PROJECTS" subtitle="Track translation projects, deadlines, linguist assignments, and gross profit margins.">
            <x-slot name="actions">
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm mr-1 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> New project
                </a>
                <a href="{{ route('project-templates.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-layer-group mr-1"></i> Templates
                </a>
                <a href="{{ route('projects.export') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export to Excel
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    {{-- KPI Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $allCount }}</h3>
                    <p class="mb-0 font-weight-bold">Total Projects</p>
                </div>
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <span class="small-box-footer py-1">{{ $inProgressCount }} In Production</span>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $inProgressCount }}</h3>
                    <p class="mb-0 font-weight-bold">In Progress</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <span class="small-box-footer py-1">Active Deadlines</span>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>$ {{ number_format($totalToReceiveSum, 2) }}</h3>
                    <p class="mb-0 font-weight-bold">Total Receivables</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <span class="small-box-footer py-1">Client Billables</span>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>$ {{ number_format($totalProfitSum, 2) }}</h3>
                    <p class="mb-0 font-weight-bold">Total Gross Profit</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="small-box-footer py-1">Net Margin Yield</span>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body py-2">
            <form method="GET" class="form-row align-items-center">
                {{-- Status Pills --}}
                <div class="col-auto mr-2">
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" class="btn {{ (!request()->filled('status') || request('status') === 'all') ? 'btn-primary' : 'btn-outline-secondary' }}">
                            All ({{ $allCount }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}" class="btn {{ request('status') === 'in_progress' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            In progress ({{ $inProgressCount }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}" class="btn {{ request('status') === 'completed' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Completed ({{ $completedCount }})
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}" class="btn {{ request('status') === 'draft' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Draft
                        </a>
                    </div>
                </div>

                {{-- Client Filter --}}
                <div class="col-auto">
                    <select name="client_id" class="form-control form-control-sm">
                        <option value="">All Clients</option>
                        @foreach ($clients as $c)
                            <option value="{{ $c->id }}" @selected(request('client_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Manager Filter --}}
                <div class="col-auto">
                    <select name="manager_id" class="form-control form-control-sm">
                        <option value="">All Managers</option>
                        @foreach ($managers as $m)
                            <option value="{{ $m->id }}" @selected(request('manager_id') == $m->id)>{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search code, name..." style="min-width: 200px;">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-info btn-sm">Filter</button>
                    @if (request()->hasAny(['status', 'client_id', 'manager_id', 'search']))
                        <a href="{{ route('projects.index') }}" class="btn btn-link btn-sm text-muted">Clear</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Projects Table Card --}}
    <x-card no-padding>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 85px;">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'code', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Code <i class="fas fa-sort text-muted"></i>
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                Name
                            </a>
                        </th>
                        <th>Client</th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'start_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Start at
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'deadline_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Deadline at
                            </a>
                        </th>
                        <th>Status</th>
                        <th>Primary manager</th>
                        <th>Tags</th>
                        <th class="text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_to_receive', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Total to receive, USD
                            </a>
                        </th>
                        <th class="text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'total_to_pay', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Total to pay, USD
                            </a>
                        </th>
                        <th class="text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'profit', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Profit, USD
                            </a>
                        </th>
                        <th class="text-right">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'profit_margin', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-dark text-nowrap">
                                Profit margin, %
                            </a>
                        </th>
                        <th class="text-right" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td class="font-weight-bold">
                                <a href="{{ route('projects.show', $project) }}" class="text-primary">
                                    {{ $project->code }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('projects.show', $project) }}" class="font-weight-600 text-dark">
                                    {{ $project->name }}
                                </a>
                                @if ($project->specialization)
                                    <br><small class="text-muted"><i class="fas fa-tag mr-1"></i>{{ $project->specialization->name }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($project->client)
                                    <a href="{{ route('clients.show', $project->client) }}">
                                        {{ $project->client->name }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-nowrap text-muted">
                                {{ $project->start_at ? $project->start_at->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="text-nowrap">
                                @if ($project->deadline_at)
                                    <span class="{{ $project->deadline_at->isPast() && $project->status !== 'completed' ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                        {{ $project->deadline_at->format('Y-m-d H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $project->status_badge_class }} px-2 py-1">
                                    {{ $project->status_label }}
                                </span>
                            </td>
                            <td>
                                {{ $project->primaryManager?->name ?? '—' }}
                            </td>
                            <td>
                                @forelse ($project->tags as $tag)
                                    <span class="badge badge-light border text-muted mr-1">{{ $tag->name }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td class="text-right font-weight-bold text-nowrap">
                                {{ number_format($project->total_to_receive, 2) }}
                            </td>
                            <td class="text-right text-muted text-nowrap">
                                {{ number_format($project->total_to_pay, 2) }}
                            </td>
                            <td class="text-right font-weight-bold {{ $project->profit >= 0 ? 'text-success' : 'text-danger' }} text-nowrap">
                                {{ number_format($project->profit, 2) }}
                            </td>
                            <td class="text-right font-weight-bold {{ $project->profit_margin >= 45 ? 'text-success' : 'text-dark' }} text-nowrap">
                                {{ number_format($project->profit_margin, 1) }} %
                            </td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('projects.show', $project) }}" class="btn btn-xs btn-outline-info mr-1" title="View details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-xs btn-outline-secondary mr-1" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <x-delete-form :action="route('projects.destroy', $project)" message="Delete project {{ $project->code }}?" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center py-5 text-muted">
                                <i class="fas fa-project-diagram fa-3x mb-3 text-secondary"></i>
                                <p class="font-weight-bold mb-1">No projects found</p>
                                <p class="small text-muted mb-3">Create your first translation project or adjust active filters.</p>
                                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> New project
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($projects->hasPages())
            <div class="card-body border-top py-2">
                {{ $projects->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
