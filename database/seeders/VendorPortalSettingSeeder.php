<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VendorPortalSetting;
use Illuminate\Database\Seeder;

class VendorPortalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@linguashine.com')->first();

        // current() creates the singleton with a generated token.
        VendorPortalSetting::current()->update([
            'default_payment_terms' => 30,
            'allow_vendors_create_invoices' => false,
            'invoice_notification_recipient_id' => $admin?->id,
            'application_form_enabled' => true,
            'application_notification_recipient_id' => $admin?->id,
        ]);
    }
}
