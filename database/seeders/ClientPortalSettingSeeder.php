<?php

namespace Database\Seeders;

use App\Models\ClientPortalSetting;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientPortalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultMethod = PaymentMethod::where('is_default', true)->first();
        $admin = User::where('email', 'admin@linguashine.com')->first();

        ClientPortalSetting::current()->update([
            'default_payment_method_id' => $defaultMethod?->id,
            'default_payment_terms' => 30,
            'allow_clients_create_projects' => true,
            'default_client_manager_id' => $admin?->id,
            'notification_recipient_id' => $admin?->id,
        ]);
    }
}
