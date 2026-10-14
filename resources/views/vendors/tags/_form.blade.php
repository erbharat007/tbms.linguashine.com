<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="name" :value="__('Tag name')" />
        <x-text-input id="name" name="name" :value="old('name', $tag->name)" required placeholder="e.g. Legal Expert, Certified, Urgent..." />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="color" :value="__('Color')" />
        <x-select-input id="color" name="color" required>
            <option value="blue" @selected(old('color', $tag->color ?? 'blue') === 'blue')>Blue (Primary)</option>
            <option value="green" @selected(old('color', $tag->color) === 'green')>Green (Success)</option>
            <option value="red" @selected(old('color', $tag->color) === 'red')>Red (Danger)</option>
            <option value="yellow" @selected(old('color', $tag->color) === 'yellow')>Yellow (Warning)</option>
            <option value="info" @selected(old('color', $tag->color) === 'info')>Teal (Info)</option>
            <option value="secondary" @selected(old('color', $tag->color) === 'secondary')>Gray (Secondary)</option>
        </x-select-input>
        <x-input-error :messages="$errors->get('color')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="2" class="form-control" placeholder="Optional notes about this tag...">{{ old('description', $tag->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-1" />
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ ($isEdit ?? false) ? __('Update tag') : __('Create tag') }}</button>
    <a href="{{ route('vendor-tags.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
