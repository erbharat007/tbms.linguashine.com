<?php

namespace Database\Seeders;

use App\Models\QuoteEmailTemplate;
use Illuminate\Database\Seeder;

class QuoteEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        QuoteEmailTemplate::updateOrCreate(
            ['name' => 'Sample template'],
            [
                'subject' => 'Quote {QUOTE_CODE} from {QUOTE_LEGAL_ENTITY}',
                'body' => <<<'TEXT'
                Dear {RECIPIENT_NAME},

                I am hereby sending the quote for your potential project as requested. We are able to accept this job and can deliver it on {QUOTE_DEADLINE}. The total cost for it will be {QUOTE_TOTAL}.

                Please see the attached file for details. Please let me know if these conditions are OK for you and we can start working on your project.

                Best regards,
                {SENDER_NAME}
                {QUOTE_LEGAL_ENTITY}
                TEXT,
            ]
        );
    }
}
