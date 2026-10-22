<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorContact;
use App\Models\VendorTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Administrator',
            'is_locked' => true,
        ]);

        $this->user = User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    public function test_vendor_index_is_displayed_for_authenticated_users(): void
    {
        Vendor::create([
            'vendor_code' => 'VEND-0001',
            'name' => 'Acme Translations',
            'type' => 'company',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('vendors.index'));

        $response->assertOk();
        $response->assertSee('Acme Translations');
        $response->assertSee('Vendors');
    }

    public function test_vendor_create_page_is_displayed_with_form_fields(): void
    {
        $response = $this->actingAs($this->user)->get(route('vendors.create'));

        $response->assertOk();
        $response->assertSee('New vendor');
        $response->assertSee('General info');
        $response->assertSee('Primary contact person');
        $response->assertSee('Name');
        $response->assertSee('First name');
        $response->assertSee('Last name');
    }

    public function test_vendor_creation_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('vendors.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'type',
            'status',
            'first_name',
            'last_name',
        ]);
    }

    public function test_vendor_can_be_created_with_primary_contact(): void
    {
        $country = Country::create([
            'name' => 'Germany',
            'iso_2' => 'DE',
            'iso_3' => 'DEU',
        ]);

        $currency = Currency::create([
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
        ]);

        $tag = VendorTag::create([
            'name' => 'Certified',
            'color' => 'success',
        ]);

        $postData = [
            'name' => 'Hans Schmidt Localization',
            'type' => 'individual',
            'status' => 'active',
            'country_id' => $country->id,
            'currency_id' => $currency->id,
            'city' => 'Berlin',
            'tax_number' => 'DE123456789',
            'payment_terms' => 30,
            'website' => 'https://hans-translations.example',
            'tags' => [$tag->id],
            // Primary contact fields
            'first_name' => 'Hans',
            'last_name' => 'Schmidt',
            'designation' => 'Lead German Translator',
            'email' => 'hans@example.com',
            'phone' => '+49 30 123456',
            'gender' => 'male',
        ];

        $response = $this->actingAs($this->user)->post(route('vendors.store'), $postData);

        $response->assertRedirect(route('vendors.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('vendors', [
            'name' => 'Hans Schmidt Localization',
            'type' => 'individual',
            'status' => 'active',
            'city' => 'Berlin',
            'tax_number' => 'DE123456789',
        ]);

        $vendor = Vendor::where('name', 'Hans Schmidt Localization')->first();
        $this->assertNotNull($vendor);
        $this->assertStringStartsWith('VEND-', $vendor->vendor_code);

        $this->assertDatabaseHas('vendor_contacts', [
            'vendor_id' => $vendor->id,
            'first_name' => 'Hans',
            'last_name' => 'Schmidt',
            'email' => 'hans@example.com',
            'is_primary' => 1,
        ]);

        $this->assertTrue($vendor->tags->contains($tag));
    }

    public function test_vendor_show_page_displays_details_and_tabs(): void
    {
        $vendor = Vendor::create([
            'vendor_code' => 'VEND-0002',
            'name' => 'Alpha Global Agency',
            'type' => 'company',
            'status' => 'active',
            'city' => 'Tokyo',
        ]);

        $vendor->contacts()->create([
            'first_name' => 'Kenji',
            'last_name' => 'Sato',
            'designation' => 'Project Manager',
            'email' => 'kenji@example.com',
            'is_primary' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('vendors.show', $vendor));

        $response->assertOk();
        $response->assertSee('Alpha Global Agency');
        $response->assertSee('Kenji Sato');
        $response->assertSee('General info');
        $response->assertSee('Contact persons');
        $response->assertSee('Prices');
        $response->assertSee('Payables');
        $response->assertSee('Invoices');
    }

    public function test_vendor_edit_page_is_displayed_with_existing_values(): void
    {
        $vendor = Vendor::create([
            'vendor_code' => 'VEND-0003',
            'name' => 'Lingua Express',
            'type' => 'individual',
            'status' => 'active',
        ]);

        $vendor->contacts()->create([
            'first_name' => 'Maria',
            'last_name' => 'Garcia',
            'email' => 'maria@example.com',
            'is_primary' => true,
        ]);

        $response = $this->actingAs($this->user)->get(route('vendors.edit', $vendor));

        $response->assertOk();
        $response->assertSee('Edit vendor');
        $response->assertSee('Lingua Express');
        $response->assertSee('Maria');
        $response->assertSee('Garcia');
    }

    public function test_vendor_and_primary_contact_can_be_updated(): void
    {
        $vendor = Vendor::create([
            'vendor_code' => 'VEND-0004',
            'name' => 'Original Vendor Name',
            'type' => 'individual',
            'status' => 'active',
        ]);

        $contact = $vendor->contacts()->create([
            'first_name' => 'Original',
            'last_name' => 'Contact',
            'email' => 'original@example.com',
            'is_primary' => true,
        ]);

        $updateData = [
            'name' => 'Updated Vendor Name',
            'type' => 'company',
            'status' => 'inactive',
            'payment_terms' => 45,
            'first_name' => 'Updated',
            'last_name' => 'Coordinator',
            'email' => 'updated@example.com',
        ];

        $response = $this->actingAs($this->user)->put(route('vendors.update', $vendor), $updateData);

        $response->assertRedirect(route('vendors.index'));

        $this->assertDatabaseHas('vendors', [
            'id' => $vendor->id,
            'name' => 'Updated Vendor Name',
            'type' => 'company',
            'status' => 'inactive',
            'payment_terms' => 45,
        ]);

        $this->assertDatabaseHas('vendor_contacts', [
            'id' => $contact->id,
            'first_name' => 'Updated',
            'last_name' => 'Coordinator',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_vendor_can_be_deleted(): void
    {
        $vendor = Vendor::create([
            'vendor_code' => 'VEND-0005',
            'name' => 'Vendor To Delete',
            'type' => 'individual',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->delete(route('vendors.destroy', $vendor));

        $response->assertRedirect(route('vendors.index'));
        $this->assertSoftDeleted('vendors', [
            'id' => $vendor->id,
        ]);
    }

    public function test_vendor_can_be_created_with_protemos_types_and_permissions(): void
    {
        $postData = [
            'name' => 'In-House Linguist Team',
            'type' => 'in-house',
            'status' => 'active',
            'is_assignable' => 1,
            'can_access_finances' => 1,
            'first_name' => 'Sara',
            'last_name' => 'Connor',
            'email' => 'sara@example.com',
            'can_login' => 1,
        ];

        $response = $this->actingAs($this->user)->post(route('vendors.store'), $postData);

        $response->assertRedirect(route('vendors.index'));

        $this->assertDatabaseHas('vendors', [
            'name' => 'In-House Linguist Team',
            'type' => 'in-house',
            'is_assignable' => 1,
            'can_access_finances' => 1,
        ]);

        $this->assertDatabaseHas('vendor_contacts', [
            'email' => 'sara@example.com',
            'can_login' => 1,
        ]);
    }

    public function test_vendor_toggles_and_invitation_actions(): void
    {
        $vendor = Vendor::create([
            'vendor_code' => 'VEND-0006',
            'name' => 'Toggle Test Vendor',
            'type' => 'freelancer',
            'status' => 'active',
            'is_assignable' => true,
            'can_access_finances' => true,
        ]);

        $contact = $vendor->contacts()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'toggle@example.com',
            'is_primary' => true,
            'can_login' => false,
        ]);

        // Toggle assignable
        $res = $this->actingAs($this->user)->post(route('vendors.toggle-assignable', $vendor));
        $res->assertSessionHas('success');
        $this->assertFalse((bool) $vendor->fresh()->is_assignable);

        // Toggle finances
        $res = $this->actingAs($this->user)->post(route('vendors.toggle-finances', $vendor));
        $res->assertSessionHas('success');
        $this->assertFalse((bool) $vendor->fresh()->can_access_finances);

        // Toggle login
        $res = $this->actingAs($this->user)->post(route('vendors.toggle-can-login', $vendor));
        $res->assertSessionHas('success');
        $this->assertTrue((bool) $contact->fresh()->can_login);

        // Invite action
        $res = $this->actingAs($this->user)->post(route('vendors.invite', $vendor));
        $res->assertSessionHas('success');
    }

    public function test_vendor_excel_export(): void
    {
        Vendor::create([
            'vendor_code' => 'VEND-0007',
            'name' => 'Exportable Vendor',
            'type' => 'company',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->get(route('vendors.export'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}
