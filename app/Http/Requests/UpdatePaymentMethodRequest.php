<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('payment-methods.view');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Type is fixed once created; derive detail rules from the existing model.
        $type = $this->route('payment_method')->type;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'is_enabled' => ['boolean'],
        ];

        return array_merge($rules, $this->detailRules($type));
    }

    /**
     * Per-type validation rules for the details.* fields.
     *
     * @return array<string, mixed>
     */
    protected function detailRules(?string $type): array
    {
        return match ($type) {
            'bank_transfer' => [
                'details.bank_name' => ['required', 'string', 'max:255'],
                'details.bank_address' => ['nullable', 'string', 'max:255'],
                'details.swift' => ['nullable', 'string', 'max:255'],
                'details.iban' => ['nullable', 'string', 'max:255'],
                'details.sort_code' => ['nullable', 'string', 'max:255'],
                'details.account_number' => ['nullable', 'string', 'max:255'],
                'details.account_currency' => ['nullable', 'string', 'max:255'],
                'details.beneficiary_name' => ['nullable', 'string', 'max:255'],
                'details.beneficiary_address' => ['nullable', 'string', 'max:255'],
            ],
            'paypal', 'skrill' => [
                'details.email' => ['required', 'email', 'max:255'],
            ],
            'custom' => [
                'details.custom_type' => ['required', 'string', 'max:255'],
                'details.details' => ['required', 'string'],
            ],
            default => [],
        };
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_enabled' => $this->boolean('is_enabled')]);
    }

    /**
     * Assemble the model attributes (details collapsed to JSON array).
     * Type is unchanged so it is not returned here.
     *
     * @return array<string, mixed>
     */
    public function paymentMethodData(): array
    {
        $validated = $this->validated();

        return [
            'name' => $validated['name'],
            'note' => $validated['note'] ?? null,
            'is_enabled' => $validated['is_enabled'] ?? true,
            'details' => array_filter($validated['details'] ?? [], fn ($v) => $v !== null && $v !== ''),
        ];
    }
}
