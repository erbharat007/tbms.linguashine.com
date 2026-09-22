<?php

namespace Database\Seeders;

use App\Models\MultiQuoteEmailTemplate;
use Illuminate\Database\Seeder;

class MultiQuoteEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        MultiQuoteEmailTemplate::updateOrCreate(
            ['name' => 'Sample template'],
            [
                'subject' => 'Multi-quote {QUOTE_CODE} from {QUOTE_LEGAL_ENTITY}',
                'body' => <<<'TEXT'
                Dear {RECIPIENT_NAME},

                I am hereby sending the multi-quote with several options for your potential project as requested. Please see the attached quote files of each option for details.

                Please let me know if conditions of any of the options are OK for you and we can start working on your project.

                Best regards,
                {SENDER_NAME}
                {QUOTE_LEGAL_ENTITY}
                TEXT,
            ]
        );
    }
}
