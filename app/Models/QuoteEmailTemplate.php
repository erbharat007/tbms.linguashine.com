<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteEmailTemplate extends Model
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
            '{SENDER_NAME}' => 'full name of the manager sending the quote',
            '{RECIPIENT_NAME}' => 'full name of the contact or primary user of client receiving the quote',
            '{QUOTE_CODE}' => 'the quote code',
            '{QUOTE_LEGAL_ENTITY}' => 'the name of the legal entity associated with the quote',
            '{QUOTE_NAME}' => 'the quote name',
            '{QUOTE_DESCRIPTION}' => 'the quote description',
            '{QUOTE_TOTAL}' => 'the quote total amount and currency',
            '{QUOTE_DEADLINE}' => 'the quote deadline',
        ];
    }
}
