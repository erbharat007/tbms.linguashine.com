<?php

namespace Database\Seeders;

use App\Models\AccountSetting;
use App\Models\CatLogTemplate;
use App\Models\Currency;
use App\Models\LegalEntity;
use App\Models\LicenseSetting;
use App\Models\ReminderSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // Seed default CAT log conversion scheme
        $scheme = CatLogTemplate::firstOrCreate(
            ['name' => '100% conversion rate'],
            [
                'is_default_client_account' => false,
                'is_default_client_profile' => false,
                'is_default_vendor_account' => false,
                'is_default_vendor_profile' => false,
                'rate_repetitions' => 100.00,
                'rate_100' => 100.00,
                'rate_95_99' => 100.00,
                'rate_85_94' => 100.00,
                'rate_75_84' => 100.00,
                'rate_50_74' => 100.00,
                'rate_no_match' => 100.00,
                'created_by' => $user?->id,
            ]
        );

        CatLogTemplate::firstOrCreate(
            ['name' => 'Standard CAT scheme'],
            [
                'is_default_client_account' => true,
                'is_default_client_profile' => true,
                'is_default_vendor_account' => true,
                'is_default_vendor_profile' => true,
                'rate_repetitions' => 30.00,
                'rate_100' => 30.00,
                'rate_95_99' => 60.00,
                'rate_85_94' => 60.00,
                'rate_75_84' => 100.00,
                'rate_50_74' => 100.00,
                'rate_no_match' => 100.00,
                'created_by' => $user?->id,
            ]
        );

        // Account settings
        $entity = LegalEntity::first();
        $currency = Currency::where('is_system', true)->first() ?? Currency::first();

        AccountSetting::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Linguashine TBMS',
                'type' => 'Enterprise',
                'api_key' => Str::random(40),
                'timezone' => 'Asia/Kolkata',
                'legal_entity_id' => $entity?->id,
                'currency_id' => $currency?->id,
                'client_cat_log_template_id' => $scheme->id,
                'vendor_cat_log_template_id' => $scheme->id,
                'default_quote_pdf_template' => 'Standard',
                'default_client_invoice_pdf_template' => 'Standard',
                'default_vendor_invoice_pdf_template' => 'Standard',
                'default_job_po_pdf_template' => 'Standard',
                'created_by' => $user?->id,
            ]
        );

        // Reminder settings
        ReminderSetting::firstOrCreate(
            ['id' => 1],
            [
                'project_deadline' => true,
                'job_deadline' => true,
                'client_invoice_due' => true,
                'vendor_invoice_due' => true,
                'quote_expiration' => true,
                'project_deadline_days' => 1,
                'job_deadline_days' => 1,
                'client_invoice_days' => 3,
                'vendor_invoice_days' => 3,
                'quote_expiration_days' => 2,
            ]
        );

        // License settings
        LicenseSetting::firstOrCreate(
            ['id' => 1],
            [
                'status' => 'Active',
                'type' => 'Enterprise Unlimited',
                'managers_limit' => 'Unlimited',
                'api_access' => true,
                'expires_at' => now()->addYears(2)->toDateString(),
                'license_key' => 'TBMS-PROTEMOS-ENT-' . strtoupper(substr(md5('tbms-key'), 0, 16)),
            ]
        );
    }
}
