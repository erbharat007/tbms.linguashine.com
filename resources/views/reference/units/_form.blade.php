@php($unit = $unit ?? null)

<div style="max-width: 600px;">
    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-9">
            <x-text-input id="name" name="name" type="text" :value="old('name', $unit?->name)" required autofocus />
            <small class="form-text text-muted">e.g. Word, Page, Hour, Flat rate.</small>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <span class="col-sm-3 col-form-label">Is word</span>
        <div class="col-sm-9">
            <input type="hidden" name="is_word" value="0">
            <div class="custom-control custom-checkbox mt-2">
                <input type="checkbox" class="custom-control-input" id="is_word" name="is_word" value="1"
                       @checked(old('is_word', $unit?->is_word ?? false)) />
                <label class="custom-control-label" for="is_word"></label>
            </div>
            <small class="form-text text-muted">Mark the unit used for word-count based pricing.</small>
        </div>
    </div>

    <div class="form-group row">
        <span class="col-sm-3 col-form-label">Enabled</span>
        <div class="col-sm-9">
            <input type="hidden" name="is_enabled" value="0">
            <div class="custom-control custom-checkbox mt-2">
                <input type="checkbox" class="custom-control-input" id="is_enabled" name="is_enabled" value="1"
                       @checked(old('is_enabled', $unit?->is_enabled ?? true)) />
                <label class="custom-control-label" for="is_enabled"></label>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update Unit') : __('Create Unit') }}</button>
    <a href="{{ route('units.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
