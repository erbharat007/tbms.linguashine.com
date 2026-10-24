<x-app-layout>
    <x-slot name="header">
        <x-page-header title="JOBS TAGS LIST" subtitle="Categorize jobs by domain, urgency, client tier, or workflow flags.">
            <x-slot name="actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary btn-sm mr-1">
                    <i class="fas fa-briefcase mr-1"></i> Jobs
                </a>
                <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#newTagModal">
                    <i class="fas fa-plus mr-1"></i> New tag
                </button>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center">
                    <h5 class="text-uppercase font-weight-bold mb-0" style="color: #009688; letter-spacing: 0.5px;">JOBS TAGS LIST</h5>
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#newTagModal">
                        New tag
                    </button>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped mb-0 text-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th>Tag</th>
                                <th>Number of jobs</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tags as $tag)
                                <tr>
                                    <td>
                                        <span class="badge px-3 py-2" style="background-color: {{ $tag->color }}; color: #ffffff; font-size: 13px;">
                                            <i class="fas fa-tag mr-1"></i> {{ $tag->name }}
                                        </span>
                                        @if ($tag->description)
                                            <span class="text-muted small ml-2">{{ $tag->description }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light border font-weight-bold font-size-13">{{ $tag->jobs_count }}</span>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-outline-secondary btn-sm mr-1" data-toggle="modal" data-target="#editTagModal_{{ $tag->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('job-tags.destroy', $tag) }}" class="d-inline" onsubmit="return confirm('Delete tag {{ $tag->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                        {{-- Edit Modal --}}
                                        <div class="modal fade text-left" id="editTagModal_{{ $tag->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('job-tags.update', $tag) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title font-weight-bold">Edit Tag: {{ $tag->name }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label class="font-weight-500">Tag Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="name" class="form-control" value="{{ $tag->name }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-500">Badge Color</label>
                                                                <div class="input-group">
                                                                    <input type="color" name="color" class="form-control form-control-color" value="{{ $tag->color }}" style="max-width: 60px;">
                                                                    <input type="text" class="form-control" value="{{ $tag->color }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-500">Description</label>
                                                                <input type="text" name="description" class="form-control" value="{{ $tag->description }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="fas fa-tags fa-2x mb-2 d-block text-secondary"></i>
                                        No results found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($tags->hasPages())
                    <div class="card-footer bg-white border-top py-2">
                        {{ $tags->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="font-weight-bold mb-0 text-primary">Tagging Guidelines</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Job tags allow your project managers to mark urgent jobs (e.g. <code>Urgent</code>, <code>Rush</code>), quality requirements (e.g. <code>Medical Certified</code>, <code>ISO 17100</code>), or workflow stages (e.g. <code>CAT tool</code>, <code>DTP Required</code>).
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#newTagModal">
                        <i class="fas fa-plus mr-1"></i> Add new tag
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- New Tag Modal --}}
    <div class="modal fade" id="newTagModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('job-tags.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold text-primary">New Job Tag</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-500">Tag Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Urgent, ISO 17100, MTPE" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-500">Color</label>
                            <div class="input-group">
                                <input type="color" name="color" class="form-control form-control-color" value="#009688" style="max-width: 60px;">
                                <input type="text" class="form-control" value="#009688" placeholder="#009688">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-500">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Optional description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
