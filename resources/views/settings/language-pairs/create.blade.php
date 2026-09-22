<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Add language pairs" />
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('language-pairs.store') }}">
                @csrf
                <p class="mb-6 text-sm text-gray-500">
                    A new language pair will be added for every combination of source and target languages selected. Same-language combinations are skipped.
                </p>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <x-input-label :value="__('Source languages')" />
                        <div class="mt-1.5">
                            <x-multi-checkbox name="source_language_ids" :options="$languages"
                                              :selected="[]" placeholder="Search languages" />
                        </div>
                        <x-input-error :messages="$errors->get('source_language_ids')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label :value="__('Target languages')" />
                        <div class="mt-1.5">
                            <x-multi-checkbox name="target_language_ids" :options="$languages"
                                              :selected="[]" placeholder="Search languages" />
                        </div>
                        <x-input-error :messages="$errors->get('target_language_ids')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-6">
                    <x-primary-button>{{ __('Add pairs') }}</x-primary-button>
                    <a href="{{ route('language-pairs.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
