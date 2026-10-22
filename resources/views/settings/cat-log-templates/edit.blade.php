<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Edit CAT log conversion scheme" subtitle="Update match percentages and defaults for {{ $scheme->name }}.">
            <x-slot name="actions">
                <a href="{{ route('cat-log-templates.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to list
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="row">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('cat-log-templates.update', $scheme) }}">
                @csrf
                @method('PUT')
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-body">
                        @include('settings.cat-log-templates._form')
                    </div>
                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('cat-log-templates.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
