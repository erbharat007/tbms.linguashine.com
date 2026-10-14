<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\LanguagePair;
use App\Models\LegalEntity;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\Specialization;
use App\Models\Timezone;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorApplication;
use App\Models\VendorContact;
use App\Models\VendorInvoice;
use App\Models\VendorPayable;
use App\Models\VendorPayment;
use App\Models\VendorPrice;
use App\Models\VendorRatingCategory;
use App\Models\VendorTag;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Rating Categories
        $ratingCategories = [
            ['name' => 'Translation Quality', 'description' => 'Accuracy, terminology, and stylistic quality of translation output.', 'weight' => 40],
            ['name' => 'Punctuality & Deadlines', 'description' => 'Adherence to agreed delivery milestones and turnaround times.', 'weight' => 30],
            ['name' => 'Communication & Responsiveness', 'description' => 'Promptness in acknowledging job orders and answering queries.', 'weight' => 20],
            ['name' => 'Formatting & Compliance', 'description' => 'Following specific project instructions and CAT tool guidelines.', 'weight' => 10],
        ];
        foreach ($ratingCategories as $cat) {
            VendorRatingCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 2. Vendor Tags
        $tags = [
            ['name' => 'Native Translator', 'color' => 'blue', 'description' => 'Native language speaker verified'],
            ['name' => 'Certified / Sworn', 'color' => 'green', 'description' => 'Official court/certified translations'],
            ['name' => 'Medical Specialist', 'color' => 'red', 'description' => 'Healthcare and clinical trials'],
            ['name' => 'Legal Specialist', 'color' => 'yellow', 'description' => 'Contracts, patents, and litigation'],
            ['name' => 'Technical & IT', 'color' => 'info', 'description' => 'Software, engineering, and electronics'],
            ['name' => 'Proofreader / QA', 'color' => 'secondary', 'description' => 'Post-editing and quality evaluation'],
            ['name' => 'Urgent Turnaround', 'color' => 'blue', 'description' => 'Available for weekend and rush jobs'],
        ];
        foreach ($tags as $tag) {
            VendorTag::firstOrCreate(['name' => $tag['name']], $tag);
        }

        // Check if sample vendors exist
        if (Vendor::count() > 0) {
            return;
        }

        $currency = Currency::first();
        $country = Country::first();
        $legalEntity = LegalEntity::first();
        $manager = User::first();
        $timezone = Timezone::first();
        $service = Service::first();
        $languagePair = LanguagePair::first();
        $specialization = Specialization::first();
        $unit = Unit::first();
        $paymentMethod = PaymentMethod::first();

        // Sample Vendor 1: Individual
        $vendor1 = Vendor::create([
            'vendor_code' => 'VEND-0001',
            'name' => 'Dr. Alexander Schmidt',
            'type' => 'individual',
            'status' => 'active',
            'legal_entity_id' => $legalEntity?->id,
            'country_id' => $country?->id,
            'city' => 'Berlin',
            'address' => 'Friedrichstraße 123',
            'tax_number' => 'DE123456789',
            'currency_id' => $currency?->id,
            'payment_terms' => 30,
            'manager_id' => $manager?->id,
            'note' => 'Reliable senior German translator with medical specialization.',
        ]);

        $contact1 = VendorContact::create([
            'vendor_id' => $vendor1->id,
            'first_name' => 'Alexander',
            'last_name' => 'Schmidt',
            'designation' => 'Lead Translator',
            'email' => 'alexander.schmidt@translations.de',
            'phone' => '+49 30 1234567',
            'timezone_id' => $timezone?->id,
            'gender' => 'male',
            'is_primary' => true,
        ]);

        // Sample Vendor 2: Company
        $vendor2 = Vendor::create([
            'vendor_code' => 'VEND-0002',
            'name' => 'Nordic Language Solutions Ltd',
            'type' => 'company',
            'status' => 'active',
            'legal_entity_id' => $legalEntity?->id,
            'country_id' => $country?->id,
            'city' => 'Stockholm',
            'address' => 'Sveavägen 45',
            'tax_number' => 'SE987654321',
            'currency_id' => $currency?->id,
            'payment_terms' => 45,
            'manager_id' => $manager?->id,
            'note' => 'Specialized agency for Scandinavian language combinations.',
        ]);

        $contact2 = VendorContact::create([
            'vendor_id' => $vendor2->id,
            'first_name' => 'Astrid',
            'last_name' => 'Lindqvist',
            'designation' => 'Vendor Manager',
            'email' => 'operations@nordiclang.se',
            'phone' => '+46 8 555 1234',
            'timezone_id' => $timezone?->id,
            'gender' => 'female',
            'is_primary' => true,
        ]);

        // Attach sample tags
        $allTags = VendorTag::all();
        if ($allTags->count() >= 2) {
            $vendor1->tags()->attach([$allTags[0]->id, $allTags[2]->id]);
            $vendor2->tags()->attach([$allTags[1]->id, $allTags[4]->id]);
        }

        // Sample Prices
        if ($service && $languagePair && $specialization && $unit && $currency) {
            VendorPrice::create([
                'vendor_id' => $vendor1->id,
                'service_id' => $service->id,
                'language_pair_id' => $languagePair->id,
                'specialization_id' => $specialization->id,
                'unit_id' => $unit->id,
                'unit_price' => 0.0850,
                'currency_id' => $currency->id,
                'note' => 'Standard rate for medical translations',
                'updated_by' => $manager?->id,
            ]);

            VendorPrice::create([
                'vendor_id' => $vendor2->id,
                'service_id' => $service->id,
                'language_pair_id' => $languagePair->id,
                'specialization_id' => $specialization->id,
                'unit_id' => $unit->id,
                'unit_price' => 0.0950,
                'currency_id' => $currency->id,
                'note' => 'Agency rate with QA included',
                'updated_by' => $manager?->id,
            ]);
        }

        // Sample Payables
        if ($currency) {
            $pay1 = VendorPayable::create([
                'payable_number' => 'PAY-2026-0001',
                'vendor_id' => $vendor1->id,
                'job_code' => 'JOB-1042',
                'project_name' => 'Medical Protocol Translation - Phase 3',
                'description' => 'Translation of clinical protocol section 4-7',
                'quantity' => 2500,
                'unit_id' => $unit?->id,
                'rate' => 0.0850,
                'amount' => 212.50,
                'currency_id' => $currency->id,
                'due_date' => now()->addDays(20),
                'status' => 'open',
            ]);

            $pay2 = VendorPayable::create([
                'payable_number' => 'PAY-2026-0002',
                'vendor_id' => $vendor2->id,
                'job_code' => 'JOB-1088',
                'project_name' => 'Nordic Software UI Localization',
                'description' => 'App string translation and review',
                'quantity' => 4200,
                'unit_id' => $unit?->id,
                'rate' => 0.0950,
                'amount' => 399.00,
                'currency_id' => $currency->id,
                'due_date' => now()->addDays(15),
                'status' => 'invoiced',
            ]);

            // Sample Invoices
            $inv = VendorInvoice::create([
                'invoice_number' => 'VINV-2026-0041',
                'vendor_id' => $vendor2->id,
                'invoice_date' => now()->subDays(5),
                'due_date' => now()->addDays(25),
                'currency_id' => $currency->id,
                'subtotal' => 399.00,
                'tax' => 0.00,
                'discount' => 0.00,
                'total' => 399.00,
                'paid_amount' => 0.00,
                'status' => 'received',
                'notes' => 'Invoice for Job JOB-1088',
            ]);

            $pay2->update(['vendor_invoice_id' => $inv->id]);

            // Sample Payment
            VendorPayment::create([
                'payment_number' => 'VPAY-2026-0012',
                'vendor_id' => $vendor1->id,
                'payment_date' => now()->subDays(10),
                'amount' => 450.00,
                'currency_id' => $currency->id,
                'payment_method_id' => $paymentMethod?->id,
                'reference_number' => 'WIRE-20260904-88',
                'notes' => 'Settlement for previous batch deliveries',
                'status' => 'paid',
            ]);
        }

        // Sample Vendor Applications
        VendorApplication::create([
            'application_number' => 'APP-2026-001',
            'name' => 'Elena Rostova',
            'type' => 'individual',
            'email' => 'elena.rostova@linguist-pro.com',
            'phone' => '+33 6 12 34 56 78',
            'country_id' => $country?->id,
            'native_language' => 'Russian',
            'services_offered' => 'Translation, Proofreading, MTPE',
            'language_pairs_offered' => 'English -> Russian, French -> Russian',
            'status' => 'pending',
            'note' => 'Experienced technical translator with 8+ years experience.',
        ]);

        VendorApplication::create([
            'application_number' => 'APP-2026-002',
            'name' => 'Kenji Tanaka Localization',
            'type' => 'company',
            'email' => 'contact@tanaka-loc.jp',
            'phone' => '+81 3 5555 0192',
            'country_id' => $country?->id,
            'native_language' => 'Japanese',
            'services_offered' => 'Software Localization, Gaming Localization',
            'language_pairs_offered' => 'English -> Japanese',
            'status' => 'pending',
            'note' => 'Boutique game and software localization studio based in Tokyo.',
        ]);
    }
}
