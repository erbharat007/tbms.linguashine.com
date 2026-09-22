@php($template = $template ?? null)

<div class="space-y-6">
    <div>
        <x-input-label for="object_type" :value="__('Object')" />
        <x-select-input id="object_type" name="object_type" class="mt-1.5" required>
            @foreach (\App\Models\PdfTemplate::objectTypes() as $value => $label)
                <option value="{{ $value }}" @selected(old('object_type', $template?->object_type) === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <p class="mt-1 text-xs text-gray-400">Which document this template is used for.</p>
        <x-input-error :messages="$errors->get('object_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1.5"
                      :value="old('name', $template?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="page_size" :value="__('Page size')" />
            <x-select-input id="page_size" name="page_size" class="mt-1.5" required>
                @foreach (\App\Models\PdfTemplate::pageSizes() as $value => $label)
                    <option value="{{ $value }}" @selected(old('page_size', $template?->page_size ?? 'A4') === $value)>{{ $label }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('page_size')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="page_orientation" :value="__('Page orientation')" />
            <x-select-input id="page_orientation" name="page_orientation" class="mt-1.5" required>
                @foreach (\App\Models\PdfTemplate::orientations() as $value => $label)
                    <option value="{{ $value }}" @selected(old('page_orientation', $template?->page_orientation ?? 'portrait') === $value)>{{ $label }}</option>
                @endforeach
            </x-select-input>
            <x-input-error :messages="$errors->get('page_orientation')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="margin_top" :value="__('Top margin, mm')" />
            <x-text-input id="margin_top" name="margin_top" type="number" min="0" max="100" class="mt-1.5"
                          :value="old('margin_top', $template?->margin_top ?? 15)" required />
            <x-input-error :messages="$errors->get('margin_top')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="margin_bottom" :value="__('Bottom margin, mm')" />
            <x-text-input id="margin_bottom" name="margin_bottom" type="number" min="0" max="100" class="mt-1.5"
                          :value="old('margin_bottom', $template?->margin_bottom ?? 15)" required />
            <x-input-error :messages="$errors->get('margin_bottom')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="locale" :value="__('Locale')" />
        <x-select-input id="locale" name="locale" class="mt-1.5" required>
            @foreach (\App\Models\PdfTemplate::locales() as $value => $label)
                <option value="{{ $value }}" @selected(old('locale', $template?->locale ?? 'en_US') === $value)>{{ $label }}</option>
            @endforeach
        </x-select-input>
        <p class="mt-1 text-xs text-gray-400">Controls number and date formatting on the generated PDF.</p>
        <x-input-error :messages="$errors->get('locale')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="header" :value="__('Page header')" />
        <textarea id="header" name="header" rows="3"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('header', $template?->header) }}</textarea>
        <x-input-error :messages="$errors->get('header')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="footer" :value="__('Page footer')" />
        <textarea id="footer" name="footer" rows="3"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('footer', $template?->footer) }}</textarea>
        <x-input-error :messages="$errors->get('footer')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="common_note" :value="__('Common note')" />
        <textarea id="common_note" name="common_note" rows="3"
                  class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500">{{ old('common_note', $template?->common_note) }}</textarea>
        <x-input-error :messages="$errors->get('common_note')" class="mt-2" />
    </div>

    <label class="flex items-center gap-2">
        <input type="hidden" name="include_cat_log" value="0">
        <input type="checkbox" name="include_cat_log" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               @checked(old('include_cat_log', $template?->include_cat_log ?? false)) />
        <span class="text-sm text-gray-700">Include CAT log details</span>
    </label>

    <div class="flex items-center gap-3 border-t border-gray-100 pt-6">
        <x-primary-button>{{ $isEdit ? __('Update PDF template') : __('Create PDF template') }}</x-primary-button>
        <a href="{{ route('pdf-templates.index') }}" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Cancel</a>
    </div>
</div>
