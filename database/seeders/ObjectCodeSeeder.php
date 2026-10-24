<?php

namespace Database\Seeders;

use App\Models\CodeSetting;
use App\Models\ObjectCode;
use Illuminate\Database\Seeder;

class ObjectCodeSeeder extends Seeder
{
    /**
     * Seed the object code prefixes / next IDs (Protemos defaults).
     * Quote job and Job are numbered within their parent, so next_id is null.
     */
    public function run(): void
    {
        $objects = [
            ['object_key' => 'quote', 'label' => 'Quote', 'prefix' => 'Q', 'next_id' => 1],
            ['object_key' => 'quote_job', 'label' => 'Quote job', 'prefix' => 'QJ', 'next_id' => null],
            ['object_key' => 'quote_receivable', 'label' => 'Quote receivable', 'prefix' => 'QR', 'next_id' => 1],
            ['object_key' => 'quote_payable', 'label' => 'Quote payable', 'prefix' => 'QP', 'next_id' => 1],
            ['object_key' => 'multi_quote', 'label' => 'Multi-quote', 'prefix' => 'MQ', 'next_id' => 1],
            ['object_key' => 'project', 'label' => 'Project', 'prefix' => 'P', 'next_id' => 1],
            ['object_key' => 'job', 'label' => 'Job', 'prefix' => 'J', 'next_id' => null],
            ['object_key' => 'receivable', 'label' => 'Receivable', 'prefix' => 'PR', 'next_id' => 1],
            ['object_key' => 'payable', 'label' => 'Payable', 'prefix' => 'PB', 'next_id' => 1],
            ['object_key' => 'client_payment', 'label' => 'Client payment', 'prefix' => 'CP', 'next_id' => 1],
            ['object_key' => 'vendor_payment', 'label' => 'Vendor payment', 'prefix' => 'VP', 'next_id' => 1],
            ['object_key' => 'tender', 'label' => 'Tender', 'prefix' => 'T', 'next_id' => 1],
        ];

        foreach ($objects as $index => $object) {
            ObjectCode::updateOrCreate(
                ['object_key' => $object['object_key']],
                $object + ['sort_order' => $index],
            );
        }

        // Ensure the single code-settings row exists.
        CodeSetting::current();
    }
}
