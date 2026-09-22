<?php

namespace Database\Seeders;

use App\Models\ClientInvoiceEmailTemplate;
use Illuminate\Database\Seeder;

class ClientInvoiceEmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        ClientInvoiceEmailTemplate::updateOrCreate(
            ['name' => 'Sample template'],
            [
                'subject' => 'Invoice {INVOICE_CODE} from {INVOICE_LEGAL_ENTITY}',
                'body' => <<<'TEXT'
                Dear {RECIPIENT_NAME},

                Please find attached invoice {INVOICE_CODE} covering jobs recently completed for {CLIENT_NAME}. Please confirm that this invoice contains no mistakes and will be paid according to the terms agreed.

                Best regards,
                {SENDER_NAME}
                {INVOICE_LEGAL_ENTITY}
                TEXT,
            ]
        );
    }
}
