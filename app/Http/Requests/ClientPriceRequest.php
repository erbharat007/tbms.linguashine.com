<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('client-prices.view');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Null client_id => price for all clients (general price book).
            'client_id' => ['nullable', 'exists:clients,id'],
            'service_id' => ['required', 'exists:services,id'],
            'language_pair_id' => ['required', 'exists:language_pairs,id'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
