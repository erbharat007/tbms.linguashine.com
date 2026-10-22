<?php

namespace Tests\Feature;

use App\Models\AccountSetting;
use App\Models\CatLogTemplate;
use App\Models\Currency;
use App\Models\Language;
use App\Models\LanguagePair;
use App\Models\LegalEntity;
use App\Models\ReminderSetting;
use App\Models\Role;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\Timezone;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create([
            'name' => 'Administrator',
            'is_locked' => true,
        ]);

        $this->admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'is_active' => true,
            'is_manager' => true,
        ]);

        Timezone::create(['name' => 'UTC', 'label' => '(UTC+00:00) UTC']);
        Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_active' => true, 'is_system' => true]);
        Unit::create(['name' => 'Source word', 'type' => 'volume', 'is_active' => true]);
        Service::create(['name' => 'Translation', 'is_active' => true]);
        Specialization::create(['name' => 'General', 'is_active' => true]);
        Language::create(['code' => 'en', 'name' => 'English']);
        Language::create(['code' => 'es', 'name' => 'Spanish']);
    }

    public function test_system_values_page_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('system-values.index'));

        $response->assertStatus(200);
        $response->assertSee('System values');
        $response->assertSee('Language pairs');
        $response->assertSee('Services');
        $response->assertSee('Specializations');
        $response->assertSee('Units');
        $response->assertSee('Currencies');
    }

    public function test_account_settings_page_is_accessible_and_updatable(): void
    {
        $response = $this->actingAs($this->admin)->get(route('account.index'));

        $response->assertStatus(200);
        $response->assertSee('Account');
        $response->assertSee('Account details');
        $response->assertSee('System settings');

        $updateResponse = $this->actingAs($this->admin)->put(route('account.update'), [
            'name' => 'Linguashine Global TBMS',
            'timezone' => 'UTC',
            'default_quote_pdf_template' => 'Standard',
            'default_client_invoice_pdf_template' => 'Standard',
            'default_vendor_invoice_pdf_template' => 'Standard',
            'default_job_po_pdf_template' => 'Standard',
        ]);

        $updateResponse->assertRedirect(route('account.index'));
        $this->assertDatabaseHas('account_settings', [
            'name' => 'Linguashine Global TBMS',
            'timezone' => 'UTC',
        ]);
    }

    public function test_license_page_is_accessible(): void
    {
        $response = $this->actingAs($this->admin)->get(route('license.index'));

        $response->assertStatus(200);
        $response->assertSee('License details');
        $response->assertSee('License status');
        $response->assertSee('Active managers limit');
    }

    public function test_cat_log_templates_crud(): void
    {
        $indexResponse = $this->actingAs($this->admin)->get(route('cat-log-templates.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('CAT log conversion schemes');

        $createResponse = $this->actingAs($this->admin)->get(route('cat-log-templates.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('New CAT log conversion scheme');

        $storeResponse = $this->actingAs($this->admin)->post(route('cat-log-templates.store'), [
            'name' => 'Trados Scheme 2026',
            'is_default_client_account' => true,
            'rate_repetitions' => 30.00,
            'rate_100' => 30.00,
            'rate_95_99' => 60.00,
            'rate_85_94' => 60.00,
            'rate_75_84' => 100.00,
            'rate_50_74' => 100.00,
            'rate_no_match' => 100.00,
        ]);

        $storeResponse->assertRedirect(route('cat-log-templates.index'));
        $this->assertDatabaseHas('cat_log_templates', [
            'name' => 'Trados Scheme 2026',
            'rate_repetitions' => 30.00,
        ]);
    }

    public function test_reminders_page_is_accessible_and_updatable(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reminders.index'));

        $response->assertStatus(200);
        $response->assertSee('Reminders');
        $response->assertSee('Send project deadline reminders');

        $updateResponse = $this->actingAs($this->admin)->put(route('reminders.update'), [
            'project_deadline' => true,
            'job_deadline' => false,
            'client_invoice_due' => true,
            'vendor_invoice_due' => true,
            'quote_expiration' => true,
            'project_deadline_days' => 2,
            'job_deadline_days' => 1,
            'client_invoice_days' => 5,
            'vendor_invoice_days' => 5,
            'quote_expiration_days' => 3,
        ]);

        $updateResponse->assertRedirect(route('reminders.index'));
        $this->assertDatabaseHas('reminder_settings', [
            'job_deadline' => false,
            'client_invoice_days' => 5,
        ]);
    }
}
