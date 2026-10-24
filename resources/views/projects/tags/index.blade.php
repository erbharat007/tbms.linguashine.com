<x-app-layout>
    <x-slot name="header">
        <x-page-header title="PROJECTS TAGS LIST" subtitle="Categorize and filter projects by custom tags and workflows.">
            <x-slot name="actions">
                <button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#newTagModal">
                    <i class="fas fa-plus mr-1"></i> New tag
                </button>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to projects
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <x-card no-padding>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Tag</th>
                                <th style="width: 180px;">Number of projects</th>
                                <th class="text-right" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tags as $tag)
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary px-2 py-1 mr-1" style="font-size: 0.9rem;">
                                            <i class="fas fa-tag mr-1 text-light"></i> {{ $tag->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.index', ['search' => $tag->name]) }}" class="badge badge-light border px-2 py-1">
                                            {{ $tag->projects_count }} {{ Str::plural('project', $tag->projects_count) }}
                                        </a>
                                    </td>
                                    <td class="text-right text-nowrap">
                                        <button type="button" class="btn btn-xs btn-outline-secondary mr-1" data-toggle="modal" data-target="#editTagModal{{ $tag->id }}" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <x-delete-form :action="route('project-tags.destroy', $tag)" message="Delete tag '{{ $tag->name }}'?" />
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editTagModal{{ $tag->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('project-tags.update', $tag) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title font-weight-bold">Update Tag</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name{{ $tag->id }}" class="font-weight-bold">Tag Name</label>
                                                        <input type="text" name="name" id="name{{ $tag->id }}" class="form-control" value="{{ $tag->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="fas fa-tags fa-3x mb-3 text-secondary"></i>
                                        <p class="font-weight-bold mb-1">No tags found</p>
                                        <p class="small text-muted mb-3">Add tags to group and organize projects.</p>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newTagModal">
                                            <i class="fas fa-plus mr-1"></i> New tag
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($tags->hasPages())
                    <div class="card-body border-top py-2">
                        {{ $tags->links() }}
                    </div>
                @endif
            </x-card>
        </div>

        {{-- Quick Add Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-plus-circle mr-1 text-primary"></i> Quick Add Tag
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('project-tags.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="tag_name" class="font-weight-bold small text-muted text-uppercase">Tag Name</label>
                            <input type="text" name="name" id="tag_name" class="form-control form-control-sm" placeholder="e.g. Rush / Express" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            Save Tag
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- New Tag Modal --}}
    <div class="modal fade" id="newTagModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('project-tags.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">New Project Tag</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modal_tag_name" class="font-weight-bold">Tag Name</label>
                            <input type="text" name="name" id="modal_tag_name" class="form-control" placeholder="e.g. VIP Client, Rush, Sworn" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Create Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
