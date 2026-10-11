<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Update code prefixes" />
    </x-slot>

    <x-card>
        <form method="POST" action="{{ route('object-codes.prefixes.update') }}">
            @csrf
            @method('PUT')

            <p class="text-muted small mb-3">Changes will affect the codes of both new and existing objects.</p>

            <div class="row form-grid">
                @foreach ($objectCodes as $code)
                    <div class="form-group col-md-6">
                        <x-input-label :for="'prefix_' . $code->id" :value="$code->label . ' code prefix'" />
                        <x-text-input :id="'prefix_' . $code->id" name="prefixes[{{ $code->id }}]" type="text"
                                      maxlength="10"
                                      :value="old('prefixes.' . $code->id, $code->prefix)" />
                    </div>
                @endforeach
            </div>

            <hr class="my-4">
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                <a href="{{ route('object-codes.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
