<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultiQuoteEmailTemplate extends Model
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
            '{SENDER_NAME}' => 'full name of the manager sending the multi-quote',
            '{RECIPIENT_NAME}' => 'full name of the contact or primary user of client receiving the multi-quote',
            '{QUOTE_CODE}' => 'the multi-quote code',
            '{QUOTE_LEGAL_ENTITY}' => 'the name of the legal entity associated with the multi-quote',
        ];
    }
}
