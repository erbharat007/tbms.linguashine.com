<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientInvoiceEmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'body',
    ];

    /**
     * Placeholder variables available in the subject and body,
     * shown as help text on the form.
     *
     * @return array<string, string>
     */
    public static function variables(): array
    {
        return [
            '{SENDER_NAME}' => 'full name of the manager sending the invoice',
            '{RECIPIENT_NAME}' => 'full name of the contact or primary user of client receiving the invoice',
            '{CLIENT_NAME}' => 'name of the client receiving the invoice',
            '{INVOICE_CODE}' => 'the invoice code',
            '{INVOICE_LEGAL_ENTITY}' => 'the name of the legal entity associated with the invoice',
        ];
    }
}
