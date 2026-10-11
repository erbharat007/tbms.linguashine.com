@php($template = $template ?? null)

<div class="row form-grid">
    <div class="form-group col-md-6">
        <x-input-label for="object_type" :value="__('Object')" />
        <x-select-input id="object_type" name="object_type" required>
            @foreach (\App\Models\PdfTemplate::objectTypes() as $value => $label)
                <option value="{{ $value }}" @selected(old('object_type', $template?->object_type) === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <small class="form-text text-muted">Which document this template is used for.</small>
        <x-input-error :messages="$errors->get('object_type')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $template?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="page_size" :value="__('Page size')" />
        <x-select-input id="page_size" name="page_size" required>
            @foreach (\App\Models\PdfTemplate::pageSizes() as $value => $label)
                <option value="{{ $value }}" @selected(old('page_size', $template?->page_size ?? 'A4') === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('page_size')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="page_orientation" :value="__('Page orientation')" />
        <x-select-input id="page_orientation" name="page_orientation" required>
            @foreach (\App\Models\PdfTemplate::orientations() as $value => $label)
                <option value="{{ $value }}" @selected(old('page_orientation', $template?->page_orientation ?? 'portrait') === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <x-input-error :messages="$errors->get('page_orientation')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="margin_top" :value="__('Top margin, mm')" />
        <x-text-input id="margin_top" name="margin_top" type="number" min="0" max="100"
                      :value="old('margin_top', $template?->margin_top ?? 15)" required />
        <x-input-error :messages="$errors->get('margin_top')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="margin_bottom" :value="__('Bottom margin, mm')" />
        <x-text-input id="margin_bottom" name="margin_bottom" type="number" min="0" max="100"
                      :value="old('margin_bottom', $template?->margin_bottom ?? 15)" required />
        <x-input-error :messages="$errors->get('margin_bottom')" class="mt-1" />
    </div>

    <div class="form-group col-md-6">
        <x-input-label for="locale" :value="__('Locale')" />
        <x-select-input id="locale" name="locale" required>
            @foreach (\App\Models\PdfTemplate::locales() as $value => $label)
                <option value="{{ $value }}" @selected(old('locale', $template?->locale ?? 'en_US') === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <small class="form-text text-muted">Controls number and date formatting on the generated PDF.</small>
        <x-input-error :messages="$errors->get('locale')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="header" :value="__('Page header')" />
        <textarea id="header" name="header" rows="3" class="form-control">{{ old('header', $template?->header) }}</textarea>
        <x-input-error :messages="$errors->get('header')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="footer" :value="__('Page footer')" />
        <textarea id="footer" name="footer" rows="3" class="form-control">{{ old('footer', $template?->footer) }}</textarea>
        <x-input-error :messages="$errors->get('footer')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <x-input-label for="common_note" :value="__('Common note')" />
        <textarea id="common_note" name="common_note" rows="3" class="form-control">{{ old('common_note', $template?->common_note) }}</textarea>
        <x-input-error :messages="$errors->get('common_note')" class="mt-1" />
    </div>

    <div class="form-group col-12">
        <input type="hidden" name="include_cat_log" value="0">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="include_cat_log" name="include_cat_log" value="1"
                   @checked(old('include_cat_log', $template?->include_cat_log ?? false)) />
            <label class="custom-control-label" for="include_cat_log">Include CAT log details</label>
        </div>
    </div>
</div>

<hr class="my-4">
<div class="d-flex align-items-center">
    <button type="submit" class="btn btn-primary btn-sm">{{ $isEdit ? __('Update PDF template') : __('Create PDF template') }}</button>
    <a href="{{ route('pdf-templates.index') }}" class="btn btn-link btn-sm text-muted ml-2">Cancel</a>
</div>
