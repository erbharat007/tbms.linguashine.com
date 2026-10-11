@php($tax = $tax ?? null)

<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $tax?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="sort_order" :value="__('Sort order inside invoices')" />
        <x-text-input id="sort_order" name="sort_order" type="number" min="0" max="9999"
                      :value="old('sort_order', $tax?->sort_order ?? 0)" required />
        <x-input-error :messages="$errors->get('sort_order')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label :value="__('Type')" />
        <div class="d-flex align-items-center" style="gap: 1.5rem;">
            @foreach (\App\Models\Tax::types() as $value => $label)
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="type_{{ $value }}" name="type" value="{{ $value }}"
                           @checked(old('type', $tax?->type ?? 'percentage') === $value) />
                    <label class="custom-control-label" for="type_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('type')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update tax') : __('Create tax') }}</button>
    <a href="{{ route('taxes.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
