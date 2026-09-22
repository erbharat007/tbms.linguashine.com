<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update next IDs" />
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('object-codes.next-ids.update') }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @foreach ($objectCodes as $code)
                        <div>
                            <x-input-label :for="'next_' . $code->id" :value="'Next ' . strtolower($code->label) . ' ID'" />
                            <x-text-input :id="'next_' . $code->id" name="next_ids[{{ $code->id }}]" type="number" min="1"
                                          class="mt-1.5"
                                          :value="old('next_ids.' . $code->id, $code->next_id)" required />
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-xs text-gray-400">All fields are mandatory.</p>

                <div class="mt-2 flex items-center gap-3 border-t border-gray-100 pt-6">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                    <a href="{{ route('object-codes.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
