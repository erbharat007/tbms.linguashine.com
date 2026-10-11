<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add language pairs" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('language-pairs.store') }}">
            @csrf
            <p class="text-muted small mb-3">
                A new language pair will be added for every combination of source and target languages selected. Same-language combinations are skipped.
            </p>

            <div class="row form-grid">
                <div class="form-group col-md-6">
                    <x-input-label :value="__('Source languages')" />
                    <x-multi-checkbox name="source_language_ids" :options="$languages" :selected="[]" placeholder="Search languages" />
                    <x-input-error :messages="$errors->get('source_language_ids')" class="mt-1" />
                </div>

                <div class="form-group col-md-6">
                    <x-input-label :value="__('Target languages')" />
                    <x-multi-checkbox name="target_language_ids" :options="$languages" :selected="[]" placeholder="Search languages" />
                    <x-input-error :messages="$errors->get('target_language_ids')" class="mt-1" />
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Add pairs') }}</button>
                <a href="{{ route('language-pairs.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
