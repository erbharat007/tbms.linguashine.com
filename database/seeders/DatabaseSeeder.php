<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Permissions and roles must exist before users are assigned to them.
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        $admin = Role::where('name', 'Administrator')->first();
        $pm = Role::where('name', 'Project Manager')->first();
        $accountant = Role::where('name', 'Accountant')->first();

        // Default administrator account.
        User::updateOrCreate(
            ['email' => 'admin@linguashine.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role_id' => $admin?->id,
                'is_active' => true,
                'is_manager' => true,
                'email_verified_at' => now(),
            ]
        );

        // Example team members.
        User::updateOrCreate(
            ['email' => 'pm@linguashine.com'],
            [
                'name' => 'Priya Manager',
                'password' => Hash::make('password'),
                'role_id' => $pm?->id,
                'is_active' => true,
                'is_manager' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'accounts@linguashine.com'],
            [
                'name' => 'Anil Accountant',
                'password' => Hash::make('password'),
                'role_id' => $accountant?->id,
                'is_active' => true,
                'is_manager' => false,
                'email_verified_at' => now(),
            ]
        );

        // Reference data (languages, currencies, services, units) and
        // master data (countries, timezones, client lookups).
        $this->call([
            ReferenceDataSeeder::class,
            CountrySeeder::class,
            TimezoneSeeder::class,
            SpecializationSeeder::class,
            LegalEntitySeeder::class,
            ClientLookupSeeder::class,
            ObjectCodeSeeder::class,
            PaymentMethodSeeder::class,
            ClientPortalSettingSeeder::class,
            VendorPortalSettingSeeder::class,
            QuoteEmailTemplateSeeder::class,
            MultiQuoteEmailTemplateSeeder::class,
            ClientInvoiceEmailTemplateSeeder::class,
            PdfTemplateSeeder::class,
            TaxSeeder::class,
            SystemSettingsSeeder::class,
        ]);
    }
}
