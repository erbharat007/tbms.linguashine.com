<x-app-layout>
    <x-slot name="header">
        <x-page-header title="PROJECT TEMPLATES" subtitle="Predefined project workflows, languages, and client requirements to accelerate project initiation.">
            <x-slot name="actions">
                <a href="{{ route('project-templates.create') }}" class="btn btn-primary btn-sm mr-1">
                    <i class="fas fa-plus mr-1"></i> New template
                </a>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to projects
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <x-card no-padding>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Client</th>
                        <th>Specialization</th>
                        <th>Primary manager</th>
                        <th>Language pairs</th>
                        <th class="text-right" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $t)
                        <tr>
                            <td class="font-weight-bold">
                                {{ $t->name }}
                                @if ($t->description)
                                    <br><small class="text-muted font-weight-normal">{{ Str::limit($t->description, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($t->client)
                                    <a href="{{ route('clients.show', $t->client) }}">{{ $t->client->name }}</a>
                                @else
                                    <span class="text-muted">Any client</span>
                                @endif
                            </td>
                            <td>{{ $t->specialization?->name ?? '—' }}</td>
                            <td>{{ $t->primaryManager?->name ?? '—' }}</td>
                            <td>
                                @forelse ($t->languagePairs as $lp)
                                    <span class="badge badge-light border mr-1">{{ $lp->sourceLanguage?->name }} -> {{ $lp->targetLanguage?->name }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('projects.create', ['template_id' => $t->id]) }}" class="btn btn-xs btn-outline-primary mr-1" title="Create project using this template">
                                    <i class="fas fa-play mr-1"></i> Use
                                </a>
                                <a href="{{ route('project-templates.edit', $t) }}" class="btn btn-xs btn-outline-secondary mr-1" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <x-delete-form :action="route('project-templates.destroy', $t)" message="Delete template '{{ $t->name }}'?" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-layer-group fa-3x mb-3 text-secondary"></i>
                                <p class="font-weight-bold mb-1">No project templates yet</p>
                                <p class="small text-muted mb-3">Create templates to standardize recurring project setups.</p>
                                <a href="{{ route('project-templates.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> New template
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($templates->hasPages())
            <div class="card-body border-top py-2">
                {{ $templates->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
