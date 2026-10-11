<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update next IDs" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('object-codes.next-ids.update') }}">
            @csrf
            @method('PUT')

            <div class="row form-grid">
                @foreach ($objectCodes as $code)
                    <div class="form-group col-md-6">
                        <x-input-label :for="'next_' . $code->id" :value="'Next ' . strtolower($code->label) . ' ID'" />
                        <x-text-input :id="'next_' . $code->id" name="next_ids[{{ $code->id }}]" type="number" min="1"
                                      :value="old('next_ids.' . $code->id, $code->next_id)" required />
                    </div>
                @endforeach
            </div>

            <small class="form-text text-muted mt-2">All fields are mandatory.</small>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                <a href="{{ route('object-codes.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
