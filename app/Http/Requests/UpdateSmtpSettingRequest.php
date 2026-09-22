<?php

namespace App\Http\Requests;

use App\Models\SmtpSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSmtpSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasPermission('smtp.view');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Password is required when configuring for the first time, optional
        // afterwards (blank = keep the existing password).
        $passwordRequired = SmtpSetting::current() === null;

        return [
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['required', Rule::in(['none', 'starttls', 'tls'])],
            'authentication' => ['required', Rule::in(['auto', 'plain', 'login', 'ntlm', 'xoauth2'])],
            'username' => ['required', 'string', 'max:255'],
            'password' => [$passwordRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'from_email' => ['required', 'email', 'max:255'],
        ];
    }
}
