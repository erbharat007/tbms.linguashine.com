<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update code prefixes" />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('object-codes.prefixes.update') }}">
                @csrf
                @method('PUT')

                <p class="mb-6 text-sm text-gray-500">Changes will affect the codes of both new and existing objects.</p>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @foreach ($objectCodes as $code)
                        <div>
                            <x-input-label :for="'prefix_' . $code->id" :value="$code->label . ' code prefix'" />
                            <x-text-input :id="'prefix_' . $code->id" name="prefixes[{{ $code->id }}]" type="text"
                                          class="mt-1.5" maxlength="10"
                                          :value="old('prefixes.' . $code->id, $code->prefix)" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-6">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('object-codes.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
