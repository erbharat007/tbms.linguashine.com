<?php

namespace App\Http\Requests;

use App\Models\PdfTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PdfTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('pdf-templates.view');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'object_type' => ['required', Rule::in(array_keys(PdfTemplate::objectTypes()))],
            'name' => ['required', 'string', 'max:255'],
            'page_size' => ['required', Rule::in(array_keys(PdfTemplate::pageSizes()))],
            'page_orientation' => ['required', Rule::in(array_keys(PdfTemplate::orientations()))],
            'margin_top' => ['required', 'integer', 'min:0', 'max:100'],
            'margin_bottom' => ['required', 'integer', 'min:0', 'max:100'],
            'locale' => ['required', Rule::in(array_keys(PdfTemplate::locales()))],
            'header' => ['nullable', 'string'],
            'footer' => ['nullable', 'string'],
            'common_note' => ['nullable', 'string'],
            'include_cat_log' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['include_cat_log' => $this->boolean('include_cat_log')]);
    }
}
