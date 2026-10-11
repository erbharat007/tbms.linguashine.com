@php($language = $language ?? null)

<div style="max-width: 560px;">
    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-9">
            <x-text-input id="name" name="name" type="text" :value="old('name', $language?->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>
    </div>

    <div class="form-group row">
        <label for="code" class="col-sm-3 col-form-label">Code</label>
        <div class="col-sm-9">
            <x-text-input id="code" name="code" type="text" :value="old('code', $language?->code)" required />
            <small class="form-text text-muted">ISO 639-1 code, e.g. en, fr, de.</small>
            <x-input-error :messages="$errors->get('code')" class="mt-1" />
        </div>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update Language') : __('Create Language') }}</button>
    <a href="{{ route('languages.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
