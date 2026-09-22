<?php

namespace App\Http\Requests;

use App\Models\DefaultTax;
use App\Models\Tax;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DefaultTaxRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('taxes.view');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'object_type' => ['required', Rule::in(array_keys(DefaultTax::objectTypes()))],
            'tax_id' => ['required', 'exists:taxes,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
        ];
    }

    /**
     * Currency is required for absolute taxes and must be empty for percentage.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $tax = Tax::find($this->input('tax_id'));

            if (! $tax) {
                return;
            }

            if ($tax->type === 'absolute' && ! $this->filled('currency_id')) {
                $validator->errors()->add('currency_id', 'Currency is required for an absolute tax.');
            }
        });
    }

    /**
     * Assemble the row; drop currency for percentage taxes.
     *
     * @return array<string, mixed>
     */
    public function defaultTaxData(): array
    {
        $data = $this->validated();

        $tax = Tax::find($data['tax_id']);
        if ($tax && $tax->type === 'percentage') {
            $data['currency_id'] = null;
        }

        return $data;
    }
}
