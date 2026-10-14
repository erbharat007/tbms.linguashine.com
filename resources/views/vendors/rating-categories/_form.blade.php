<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="name" :value="__('Category name')" />
        <x-text-input id="name" name="name" :value="old('name', $category->name)" required placeholder="e.g. Translation Quality, Punctuality, Communication..." />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="weight" :value="__('Weight (%)')" />
        <x-text-input id="weight" name="weight" type="number" min="1" max="100" :value="old('weight', $category->weight ?? 25)" required placeholder="25" />
        <x-input-error :messages="$errors->get('weight')" class="mt-1" />
    </div>

    <div class="form-group col-md-3">
        <x-input-label for="status" :value="__('Status')" />
        <x-select-input id="status" name="status" required>
            <option value="active" @selected(old('status', $category->status ?? 'active') === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $category->status) === 'inactive')>Inactive</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="3" class="form-control" placeholder="Evaluation criteria details...">{{ old('description', $category->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ ($isEdit ?? false) ? __('Update category') : __('Create category') }}</button>
    <a href="{{ route('vendor-rating-categories.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
