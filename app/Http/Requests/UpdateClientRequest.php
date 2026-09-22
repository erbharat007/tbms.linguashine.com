<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('clients.view');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // General info
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['company', 'individual'])],
            'legal_entity_id' => ['nullable', 'exists:legal_entities,id'],
            'client_status_id' => ['nullable', 'exists:client_statuses,id'],
            'client_source_id' => ['nullable', 'exists:client_sources,id'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'region' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],

            // Primary user (contact)
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'timezone_id' => ['nullable', 'exists:timezones,id'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'phone' => ['nullable', 'string', 'max:255'],
            'skype' => ['nullable', 'string', 'max:255'],
            'can_login' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'can_login' => $this->boolean('can_login'),
        ]);
    }
}
