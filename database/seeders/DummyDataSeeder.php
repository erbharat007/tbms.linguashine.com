<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\ClientInvoiceEmailTemplate;
use App\Models\ClientPrice;
use App\Models\ClientSource;
use App\Models\ClientStatus;
use App\Models\Country;
use App\Models\Currency;
use App\Models\DefaultTax;
use App\Models\Language;
use App\Models\LanguagePair;
use App\Models\LegalEntity;
use App\Models\MultiQuoteEmailTemplate;
use App\Models\PaymentMethod;
use App\Models\QuoteEmailTemplate;
use App\Models\Role;
use App\Models\Service;
use App\Models\SmtpSetting;
use App\Models\Specialization;
use App\Models\Tax;
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
use App\Models\VendorTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Seed comprehensive dummy data for all TBMS options including reports, settings, clients, and vendors.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 0. REFERENCE LOOKUPS & SYSTEM FOUNDATIONS
        // -------------------------------------------------------------
        $currencies = Currency::all()->keyBy('code');
        $defaultCurrency = $currencies['EUR'] ?? $currencies['USD'] ?? Currency::first();
        $usd = $currencies['USD'] ?? $defaultCurrency;
        $eur = $currencies['EUR'] ?? $defaultCurrency;
        $gbp = $currencies['GBP'] ?? $defaultCurrency;
        $chf = $currencies['CHF'] ?? $defaultCurrency;
        $jpy = $currencies['JPY'] ?? $defaultCurrency;

        $countries = Country::all()->keyBy('name');
        $defaultCountry = Country::first();
        $timezones = Timezone::all();
        $defaultTimezone = $timezones->first();

        $adminRole = Role::where('name', 'Administrator')->first() ?? Role::first();
        $pmRole = Role::where('name', 'Project Manager')->first() ?? $adminRole;
        $accountantRole = Role::where('name', 'Accountant')->first() ?? $adminRole;

        // -------------------------------------------------------------
        // 1. SEED LEGAL ENTITIES
        // -------------------------------------------------------------
        $legalEntitySpecs = [
            [
                'name' => 'LinguaShine Global UK Ltd',
                'website' => 'https://uk.linguashine.com',
                'tax_number' => 'GB-987654321',
                'vat_number' => 'GB987654321',
                'country_id' => $countries['United Kingdom']?->id ?? $defaultCountry?->id,
                'region' => 'Greater London',
                'postal_code' => 'EC2A 4NE',
                'city' => 'London',
                'address' => '25 Bank Street, Canary Wharf',
                'phone' => '+44 20 7946 0990',
                'first_client_invoice_number' => 1001,
                'first_vendor_invoice_number' => 5001,
                'client_invoice_prefix' => 'LS-UK-',
                'vendor_invoice_prefix' => 'V-UK-',
            ],
            [
                'name' => 'LinguaShine US Inc.',
                'website' => 'https://us.linguashine.com',
                'tax_number' => 'US-12-3456789',
                'vat_number' => null,
                'country_id' => $countries['United States']?->id ?? $defaultCountry?->id,
                'region' => 'New York',
                'postal_code' => '10001',
                'city' => 'New York',
                'address' => '350 Fifth Avenue, Suite 4800',
                'phone' => '+1 212 555 0199',
                'first_client_invoice_number' => 2001,
                'first_vendor_invoice_number' => 6001,
                'client_invoice_prefix' => 'LS-US-',
                'vendor_invoice_prefix' => 'V-US-',
            ],
            [
                'name' => 'LinguaShine GmbH (DACH)',
                'website' => 'https://de.linguashine.com',
                'tax_number' => 'DE-345678901',
                'vat_number' => 'DE345678901',
                'country_id' => $countries['Germany']?->id ?? $defaultCountry?->id,
                'region' => 'Hessen',
                'postal_code' => '60311',
                'city' => 'Frankfurt am Main',
                'address' => 'Mainzer Landstraße 180',
                'phone' => '+49 69 1234 5678',
                'first_client_invoice_number' => 3001,
                'first_vendor_invoice_number' => 7001,
                'client_invoice_prefix' => 'LS-EU-',
                'vendor_invoice_prefix' => 'V-EU-',
            ],
        ];

        foreach ($legalEntitySpecs as $le) {
            LegalEntity::firstOrCreate(['name' => $le['name']], $le);
        }

        $allLegalEntities = LegalEntity::all();
        $defaultLegalEntity = $allLegalEntities->first();

        // -------------------------------------------------------------
        // 2. SEED SYSTEM USERS
        // -------------------------------------------------------------
        $userSpecs = [
            [
                'name' => 'Sarah Jenkins',
                'email' => 'sarah.j@linguashine.com',
                'role_id' => $pmRole?->id,
                'is_manager' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Lucas Vance',
                'email' => 'lucas.v@linguashine.com',
                'role_id' => $pmRole?->id,
                'is_manager' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Elena Morozova',
                'email' => 'elena.m@linguashine.com',
                'role_id' => $accountantRole?->id,
                'is_manager' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Marcus Weber',
                'email' => 'marcus.w@linguashine.com',
                'role_id' => $adminRole?->id,
                'is_manager' => true,
                'is_active' => true,
            ],
        ];

        foreach ($userSpecs as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'role_id' => $u['role_id'],
                    'is_manager' => $u['is_manager'],
                    'is_active' => $u['is_active'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        $manager = User::where('is_manager', true)->first();

        // -------------------------------------------------------------
        // 3. SEED POPULAR LANGUAGE PAIRS
        // -------------------------------------------------------------
        $langs = Language::all()->keyBy('code');
        if ($langs->has('en')) {
            $en = $langs['en'];
            $targetCodes = ['de', 'fr', 'es', 'it', 'ja', 'zh', 'pt', 'ru', 'ar', 'nl', 'ko', 'hi'];
            foreach ($targetCodes as $tc) {
                if ($langs->has($tc)) {
                    $tgt = $langs[$tc];
                    // English -> Target
                    LanguagePair::firstOrCreate(
                        ['source_language_id' => $en->id, 'target_language_id' => $tgt->id],
                        ['is_enabled' => true]
                    );
                    // Target -> English
                    LanguagePair::firstOrCreate(
                        ['source_language_id' => $tgt->id, 'target_language_id' => $en->id],
                        ['is_enabled' => true]
                    );
                }
            }
        }

        $allLangPairs = LanguagePair::with(['sourceLanguage', 'targetLanguage'])->get();

        // -------------------------------------------------------------
        // 4. SEED PAYMENT METHODS
        // -------------------------------------------------------------
        $pmSpecs = [
            [
                'name' => 'Wire transfer',
                'type' => 'bank',
                'note' => 'International SWIFT / IBAN bank wire settlement.',
                'details' => "Beneficiary: LinguaShine Ltd\nIBAN: DE89 3704 0044 0532 0130 00\nBIC: DEUTDEDDFXX\nBank: Deutsche Bank AG",
                'is_enabled' => true,
                'is_default' => true,
            ],
            [
                'name' => 'SEPA Credit Transfer',
                'type' => 'bank',
                'note' => 'Eurozone SEPA direct bank credit transfer (Euro clearing).',
                'details' => "Beneficiary: LinguaShine GmbH\nIBAN: DE12 5001 0517 0648 4898 90\nBIC: HELADEF1800\nBank: Landesbank Hessen-Thüringen",
                'is_enabled' => true,
                'is_default' => false,
            ],
            [
                'name' => 'PayPal Business',
                'type' => 'electronic',
                'note' => 'Instant merchant checkout and freelance translator mass payout.',
                'details' => "Account Email: payments@linguashine.com\nMerchant ID: PAYPAL-LS-998811",
                'is_enabled' => true,
                'is_default' => false,
            ],
            [
                'name' => 'Stripe Corporate Card',
                'type' => 'card',
                'note' => 'Online customer portal credit/debit card processing rail.',
                'details' => "Gateway: Stripe Payments Europe Ltd\nAccepted: Visa, MasterCard, Amex",
                'is_enabled' => true,
                'is_default' => false,
            ],
            [
                'name' => 'Wise Business',
                'type' => 'electronic',
                'note' => 'Low-cost multi-currency international borderless transfers.',
                'details' => "Account: Wise Payments UK Ltd\nMulti-currency borderless balance",
                'is_enabled' => true,
                'is_default' => false,
            ],
            [
                'name' => 'ACH Direct Deposit',
                'type' => 'bank',
                'note' => 'US domestic Automated Clearing House electronic transfer.',
                'details' => "Routing Transit #: 021000021\nAccount #: 987654321012\nBank: JPMorgan Chase Bank NA",
                'is_enabled' => true,
                'is_default' => false,
            ],
        ];

        foreach ($pmSpecs as $spec) {
            PaymentMethod::firstOrCreate(['name' => $spec['name']], $spec);
        }

        $wireTransfer = PaymentMethod::where('name', 'Wire transfer')->first() ?? PaymentMethod::first();

        // -------------------------------------------------------------
        // 5. SEED TAXES & DEFAULT TAX CONFIGURATIONS
        // -------------------------------------------------------------
        $taxSpecs = [
            ['name' => 'VAT Standard (19%)', 'type' => 'percentage', 'sort_order' => 1],
            ['name' => 'VAT Reduced (7%)', 'type' => 'percentage', 'sort_order' => 2],
            ['name' => 'UK VAT (20%)', 'type' => 'percentage', 'sort_order' => 3],
            ['name' => 'US State Sales Tax (8.25%)', 'type' => 'percentage', 'sort_order' => 4],
            ['name' => 'Zero-Rated EU Export (0%)', 'type' => 'percentage', 'sort_order' => 5],
            ['name' => 'Freelancer Withholding Tax (10%)', 'type' => 'percentage', 'sort_order' => 6],
            ['name' => 'Sworn Certification Fixed Surcharge', 'type' => 'absolute', 'sort_order' => 7],
        ];

        foreach ($taxSpecs as $ts) {
            Tax::firstOrCreate(['name' => $ts['name']], $ts);
        }

        $vat19 = Tax::where('name', 'VAT Standard (19%)')->first();
        $vatUk = Tax::where('name', 'UK VAT (20%)')->first();
        $withholding = Tax::where('name', 'Freelancer Withholding Tax (10%)')->first();
        $fixedCert = Tax::where('name', 'Sworn Certification Fixed Surcharge')->first();

        if ($vat19) {
            DefaultTax::firstOrCreate(
                ['object_type' => 'client', 'tax_id' => $vat19->id],
                ['amount' => 19.00, 'currency_id' => null]
            );
        }
        if ($vatUk) {
            DefaultTax::firstOrCreate(
                ['object_type' => 'client', 'tax_id' => $vatUk->id],
                ['amount' => 20.00, 'currency_id' => null]
            );
        }
        if ($withholding) {
            DefaultTax::firstOrCreate(
                ['object_type' => 'vendor', 'tax_id' => $withholding->id],
                ['amount' => 10.00, 'currency_id' => null]
            );
        }
        if ($fixedCert) {
            DefaultTax::firstOrCreate(
                ['object_type' => 'vendor', 'tax_id' => $fixedCert->id],
                ['amount' => 25.00, 'currency_id' => $eur?->id]
            );
        }

        // -------------------------------------------------------------
        // 6. SEED EMAIL TEMPLATES
        // -------------------------------------------------------------
        $quoteTemplates = [
            [
                'name' => 'Standard Translation Proposal',
                'subject' => 'Quote Proposal #{quote_code} - LinguaShine Translations',
                'body' => "Dear {contact_name},\n\nThank you for requesting a quotation with LinguaShine. We are pleased to provide proposal #{quote_code} for your review.\n\nProject: {project_name}\nTarget Language: {target_language}\nEstimated Turnaround: {delivery_days} business days\nTotal Amount: {quote_amount}\n\nPlease click the secure approval link below to accept the proposal and commence production.\n\nBest regards,\n{manager_name}\nLinguaShine Project Team",
            ],
            [
                'name' => 'Urgent Express Delivery Quotation',
                'subject' => 'URGENT: Expedited Translation Estimate #{quote_code}',
                'body' => "Dear {contact_name},\n\nRegarding your expedited translation request for #{quote_code}, our dedicated linguist team is on standby to initiate work immediately.\n\nPriority Turnaround: 24-48 Hours\nQuoted Total: {quote_amount}\n\nPlease confirm approval so we can assign your certified linguists immediately.\n\nKind regards,\n{manager_name}",
            ],
            [
                'name' => 'Follow-Up on Expiring Proposal',
                'subject' => 'Follow-Up: Quotation #{quote_code} for {project_name}',
                'body' => "Dear {contact_name},\n\nWe are following up regarding quote #{quote_code} submitted earlier this week. Please let us know if you require any adjustments to scope, deadline, or pricing.\n\nWe look forward to collaborating with you.\n\nSincerely,\n{manager_name}",
            ],
        ];

        foreach ($quoteTemplates as $qt) {
            QuoteEmailTemplate::firstOrCreate(['name' => $qt['name']], $qt);
        }

        $multiQuoteTemplates = [
            [
                'name' => 'Tiered Translation Service Options',
                'subject' => 'Multi-Option Localization Quotation #{quote_code}',
                'body' => "Dear {contact_name},\n\nWe have prepared three service tier estimates for {project_name}:\n\n1. Essential MTPE Tier: Cost-efficient machine translation with human post-editing.\n2. Professional Human Tier: Native dual-linguist translation and bilingual proofreading (ISO 17100 compliant).\n3. Certified Legal / Medical Tier: Sworn specialist translation with signed Certificate of Accuracy.\n\nPlease review the attached comparison and let us know your preferred tier.\n\nWarm regards,\n{manager_name}",
            ],
            [
                'name' => 'Global Multilingual Campaign Estimate',
                'subject' => 'Multilingual Release Proposal ({languages_count} Languages)',
                'body' => "Dear {contact_name},\n\nPlease find our consolidated localization proposal covering your multi-market release across all target territories. Volume discounts and CAT repetition savings have been applied.\n\nRespectfully,\n{manager_name}",
            ],
        ];

        foreach ($multiQuoteTemplates as $mqt) {
            MultiQuoteEmailTemplate::firstOrCreate(['name' => $mqt['name']], $mqt);
        }

        $invoiceTemplates = [
            [
                'name' => 'Standard Client Service Invoice',
                'subject' => 'Invoice #{invoice_number} from LinguaShine Translations',
                'body' => "Dear {contact_name},\n\nPlease find attached invoice #{invoice_number} for completed linguistic services.\n\nInvoice Total: {total_amount}\nPayment Due Date: {due_date}\n\nPayment may be made via bank wire transfer or online credit card portal. We appreciate your partnership.\n\nSincerely,\nFinance Department\nLinguaShine Global",
            ],
            [
                'name' => 'Friendly Due Date Reminder',
                'subject' => 'Reminder: Invoice #{invoice_number} due on {due_date}',
                'body' => "Dear {contact_name},\n\nThis is a friendly reminder that invoice #{invoice_number} ({total_amount}) is scheduled for payment on {due_date}.\n\nPlease let us know if you require any additional purchase order references or banking verification.\n\nThank you,\nLinguaShine Accounts Receivable",
            ],
            [
                'name' => 'Official Payment Receipt & Confirmation',
                'subject' => 'Payment Confirmation for Invoice #{invoice_number}',
                'body' => "Dear {contact_name},\n\nWe confirm receipt of payment for invoice #{invoice_number} in the amount of {paid_amount}. Your account is fully settled with zero balance due.\n\nThank you for choosing LinguaShine!\n\nBest regards,\nLinguaShine Billing Team",
            ],
        ];

        foreach ($invoiceTemplates as $it) {
            ClientInvoiceEmailTemplate::firstOrCreate(['name' => $it['name']], $it);
        }

        // -------------------------------------------------------------
        // 7. SEED SMTP CONFIGURATION
        // -------------------------------------------------------------
        if (SmtpSetting::count() === 0) {
            SmtpSetting::create([
                'host' => 'smtp.mailtrap.io',
                'port' => 587,
                'encryption' => 'tls',
                'authentication' => true,
                'username' => 'tbms-notifications@linguashine.com',
                'password' => 'secret_smtp_token_123',
                'from_email' => 'noreply@linguashine.com',
            ]);
        }

        // -------------------------------------------------------------
        // 8. SEED CLIENTS, CONTACTS & PRICES
        // -------------------------------------------------------------
        $services = Service::all();
        $specializations = Specialization::all();
        $units = Unit::all();
        $sourceWordUnit = $units->firstWhere('name', 'Word') ?? $units->first();
        $hourUnit = $units->firstWhere('name', 'Hour') ?? $units->last();
        $pageUnit = $units->firstWhere('name', 'Page') ?? $units->first();

        $clientStatuses = ClientStatus::all()->keyBy('name');
        $clientSources = ClientSource::all()->keyBy('name');
        $statusActive = $clientStatuses['Active'] ?? ClientStatus::first();
        $sourceWebsite = $clientSources['Website'] ?? ClientSource::first();

        $clientSpecs = [
            [
                'name' => 'PharmaCorp Global AG',
                'type' => 'company',
                'city' => 'Basel',
                'country' => $countries['Switzerland'] ?? $defaultCountry,
                'currency' => $eur,
                'tax_number' => 'CHE-109.876.543',
                'vat_number' => 'CHE-109.876.543 MWST',
                'website' => 'https://pharmacorp-example.com',
                'note' => 'Tier 1 multinational pharmaceutical enterprise. Strict compliance with EMA & FDA terminology.',
                'contacts' => [
                    ['first_name' => 'Dr. Beatrix', 'last_name' => 'Vogel', 'position' => 'Global Regulatory Lead', 'email' => 'b.vogel@pharmacorp-example.com', 'phone' => '+41 61 234 5678', 'is_primary' => true],
                    ['first_name' => 'Stefan', 'last_name' => 'Meili', 'position' => 'Accounts Payable Manager', 'email' => 'finance@pharmacorp-example.com', 'phone' => '+41 61 234 5679', 'is_primary' => false],
                ],
            ],
            [
                'name' => 'Apex Legal LLP',
                'type' => 'company',
                'city' => 'London',
                'country' => $countries['United Kingdom'] ?? $defaultCountry,
                'currency' => $gbp,
                'tax_number' => 'GB987654321',
                'vat_number' => 'GB987654321',
                'website' => 'https://apexlegal-example.co.uk',
                'note' => 'Cross-border litigation and intellectual property translation projects.',
                'contacts' => [
                    ['first_name' => 'Eleanor', 'last_name' => 'Cavendish', 'position' => 'Managing Partner', 'email' => 'e.cavendish@apexlegal-example.co.uk', 'phone' => '+44 20 7946 0123', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'TechVanguard Inc.',
                'type' => 'company',
                'city' => 'San Francisco',
                'country' => $countries['United States'] ?? $defaultCountry,
                'currency' => $usd,
                'tax_number' => 'US94-3847291',
                'website' => 'https://techvanguard-example.io',
                'note' => 'Cloud infrastructure and AI API software localization into 12 languages.',
                'contacts' => [
                    ['first_name' => 'David', 'last_name' => 'Chen', 'position' => 'Head of Global Localization', 'email' => 'd.chen@techvanguard-example.io', 'phone' => '+1 415 555 0192', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'Nippon Media Holdings',
                'type' => 'company',
                'city' => 'Tokyo',
                'country' => $countries['Japan'] ?? $defaultCountry,
                'currency' => $jpy,
                'tax_number' => 'JP1010001020304',
                'website' => 'https://nipponmedia-example.jp',
                'note' => 'Entertainment, gaming scripts, and corporate video subtitling.',
                'contacts' => [
                    ['first_name' => 'Kenzo', 'last_name' => 'Takahashi', 'position' => 'Content Director', 'email' => 'takahashi@nipponmedia-example.jp', 'phone' => '+81 3 5555 0143', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'BioHealth Diagnostics GmbH',
                'type' => 'company',
                'city' => 'Munich',
                'country' => $countries['Germany'] ?? $defaultCountry,
                'currency' => $eur,
                'tax_number' => 'DE304958271',
                'vat_number' => 'DE304958271',
                'website' => 'https://biohealth-example.de',
                'note' => 'Medical device instructions for use (IFUs) and clinical trial documentation.',
                'contacts' => [
                    ['first_name' => 'Dr. Monika', 'last_name' => 'Fischer', 'position' => 'Quality Assurance Manager', 'email' => 'm.fischer@biohealth-example.de', 'phone' => '+49 89 2030 4050', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'Nordic Energy Solutions',
                'type' => 'company',
                'city' => 'Stockholm',
                'country' => $countries['Sweden'] ?? $defaultCountry,
                'currency' => $eur,
                'tax_number' => 'SE556123456701',
                'vat_number' => 'SE556123456701',
                'website' => 'https://nordicenergy-example.se',
                'note' => 'Renewable energy reports, sustainability assessments, and technical manuals.',
                'contacts' => [
                    ['first_name' => 'Lars', 'last_name' => 'Gustafsson', 'position' => 'Communications Officer', 'email' => 'lars.g@nordicenergy-example.se', 'phone' => '+46 8 123 4567', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'EuroFinance SA',
                'type' => 'company',
                'city' => 'Luxembourg',
                'country' => $countries['Luxembourg'] ?? $defaultCountry,
                'currency' => $eur,
                'tax_number' => 'LU12345678',
                'vat_number' => 'LU12345678',
                'website' => 'https://eurofinance-example.lu',
                'note' => 'Annual financial statements, fund fact sheets, and investor prospectuses.',
                'contacts' => [
                    ['first_name' => 'Claire', 'last_name' => 'Dumont', 'position' => 'Compliance Director', 'email' => 'claire.dumont@eurofinance-example.lu', 'phone' => '+352 20 123 456', 'is_primary' => true],
                ],
            ],
            [
                'name' => 'Dr. Elena Rostova',
                'type' => 'individual',
                'city' => 'Paris',
                'country' => $countries['France'] ?? $defaultCountry,
                'currency' => $eur,
                'tax_number' => 'FR88123456789',
                'website' => '',
                'note' => 'Independent academic author publishing international research papers.',
                'contacts' => [
                    ['first_name' => 'Elena', 'last_name' => 'Rostova', 'position' => 'Author & Researcher', 'email' => 'elena.rostova@example.fr', 'phone' => '+33 1 42 68 55 00', 'is_primary' => true],
                ],
            ],
        ];

        foreach ($clientSpecs as $spec) {
            $client = Client::firstOrCreate(
                ['name' => $spec['name']],
                [
                    'type' => $spec['type'],
                    'legal_entity_id' => $defaultLegalEntity?->id,
                    'client_status_id' => $statusActive?->id,
                    'client_source_id' => $sourceWebsite?->id,
                    'country_id' => $spec['country']?->id ?? $defaultCountry?->id,
                    'city' => $spec['city'],
                    'tax_number' => $spec['tax_number'],
                    'vat_number' => $spec['vat_number'] ?? null,
                    'website' => $spec['website'],
                    'currency_id' => $spec['currency']?->id ?? $defaultCurrency?->id,
                    'note' => $spec['note'],
                ]
            );

            // Contacts
            foreach ($spec['contacts'] as $c) {
                ClientContact::firstOrCreate(
                    ['client_id' => $client->id, 'email' => $c['email']],
                    [
                        'first_name' => $c['first_name'],
                        'last_name' => $c['last_name'],
                        'position' => $c['position'],
                        'phone' => $c['phone'],
                        'timezone_id' => $defaultTimezone?->id,
                        'is_primary' => $c['is_primary'],
                        'is_active' => true,
                        'is_invoicing' => true,
                    ]
                );
            }

            // Client Prices across multiple services and pairs
            foreach ($allLangPairs->take(4) as $lp) {
                foreach ($services->take(2) as $srv) {
                    ClientPrice::firstOrCreate(
                        [
                            'client_id' => $client->id,
                            'language_pair_id' => $lp->id,
                            'service_id' => $srv->id,
                        ],
                        [
                            'specialization_id' => $specializations->first()?->id,
                            'unit_id' => $sourceWordUnit?->id,
                            'unit_price' => rand(15, 24) / 100, // 0.15 - 0.24 per word
                            'currency_id' => $client->currency_id,
                            'note' => 'Standard contracted client rate for ' . $srv->name,
                            'updated_by' => $manager?->id,
                        ]
                    );
                }
            }
        }

        // -------------------------------------------------------------
        // 9. SEED DIVERSE VENDORS, PRICES & APPLICATIONS
        // -------------------------------------------------------------
        $vendorSpecs = [
            [
                'vendor_code' => 'VEND-0005',
                'name' => 'Maria Garcia Morales',
                'type' => 'individual',
                'city' => 'Madrid',
                'country' => $countries['Spain'] ?? $defaultCountry,
                'currency' => $eur,
                'payment_terms' => 30,
                'note' => 'Native European Spanish translator specializing in marketing, medical, and corporate PR.',
                'tags' => ['Native Translator', 'Medical Specialist'],
                'contacts' => [
                    ['first_name' => 'Maria', 'last_name' => 'Garcia', 'designation' => 'Senior Linguist', 'email' => 'm.garcia@traducciones-es.example', 'phone' => '+34 91 123 4567'],
                ],
            ],
            [
                'vendor_code' => 'VEND-0006',
                'name' => 'Jean-Luc Picard Translations',
                'type' => 'individual',
                'city' => 'Lyon',
                'country' => $countries['France'] ?? $defaultCountry,
                'currency' => $eur,
                'payment_terms' => 30,
                'note' => 'Certified French sworn translator with 15+ years experience in legal litigation and contracts.',
                'tags' => ['Certified / Sworn', 'Legal Specialist'],
                'contacts' => [
                    ['first_name' => 'Jean-Luc', 'last_name' => 'Picard', 'designation' => 'Sworn Court Translator', 'email' => 'j.picard@traduction-lyon.example', 'phone' => '+33 4 72 00 11 22'],
                ],
            ],
            [
                'vendor_code' => 'VEND-0007',
                'name' => 'Kenji Sato',
                'type' => 'individual',
                'city' => 'Osaka',
                'country' => $countries['Japan'] ?? $defaultCountry,
                'currency' => $usd,
                'payment_terms' => 15,
                'note' => 'Precision Japanese technical translator specializing in automotive, robotics, and patent filings.',
                'tags' => ['Native Translator', 'Technical & IT'],
                'contacts' => [
                    ['first_name' => 'Kenji', 'last_name' => 'Sato', 'designation' => 'Technical Translator', 'email' => 'kenji.sato@osaka-tech.example', 'phone' => '+81 6 6000 1234'],
                ],
            ],
            [
                'vendor_code' => 'VEND-0008',
                'name' => 'LinguaNordic Translation Oy',
                'type' => 'company',
                'city' => 'Helsinki',
                'country' => $countries['Finland'] ?? $defaultCountry,
                'currency' => $eur,
                'payment_terms' => 30,
                'note' => 'Full-service localization agency for Finnish, Swedish, Norwegian, and Danish language projects.',
                'tags' => ['Technical & IT', 'Proofreader / QA'],
                'contacts' => [
                    ['first_name' => 'Mika', 'last_name' => 'Hakkinen', 'designation' => 'Partner Relations', 'email' => 'mika@linguanordic-example.fi', 'phone' => '+358 9 1234 5678'],
                ],
            ],
            [
                'vendor_code' => 'VEND-0009',
                'name' => 'Chen Wei & Partners',
                'type' => 'individual',
                'city' => 'Shanghai',
                'country' => $countries['China'] ?? $defaultCountry,
                'currency' => $usd,
                'payment_terms' => 30,
                'note' => 'Chinese Simplified & Traditional finance, fintech, and pharmaceutical localized translations.',
                'tags' => ['Native Translator', 'Medical Specialist'],
                'contacts' => [
                    ['first_name' => 'Wei', 'last_name' => 'Chen', 'designation' => 'Lead Mandarin Translator', 'email' => 'chen.wei@shanghai-lingua.example', 'phone' => '+86 21 6000 8888'],
                ],
            ],
            [
                'vendor_code' => 'VEND-0010',
                'name' => 'Dr. Alexander Schmidt',
                'type' => 'individual',
                'city' => 'Berlin',
                'country' => $countries['Germany'] ?? $defaultCountry,
                'currency' => $eur,
                'payment_terms' => 30,
                'note' => 'German medical, pharmaceutical, and biochemistry expert linguist.',
                'tags' => ['Medical Specialist', 'Proofreader / QA'],
                'contacts' => [
                    ['first_name' => 'Alexander', 'last_name' => 'Schmidt', 'designation' => 'Medical Linguist', 'email' => 'a.schmidt@berlin-medtrans.example', 'phone' => '+49 30 9876 5432'],
                ],
            ],
            [
                'vendor_code' => 'VEND-0011',
                'name' => 'Hans Zimmer Media Localization',
                'type' => 'company',
                'city' => 'Frankfurt',
                'country' => $countries['Germany'] ?? $defaultCountry,
                'currency' => $eur,
                'payment_terms' => 30,
                'note' => 'Subtitling, voiceover dubbing, and video game localization studio.',
                'tags' => ['Technical & IT', 'Native Translator'],
                'contacts' => [
                    ['first_name' => 'Hans', 'last_name' => 'Zimmer', 'designation' => 'Audio/Media Director', 'email' => 'hans@frankfurt-media.example', 'phone' => '+49 69 9876 5432'],
                ],
            ],
        ];

        foreach ($vendorSpecs as $vspec) {
            $vendor = Vendor::firstOrCreate(
                ['vendor_code' => $vspec['vendor_code']],
                [
                    'name' => $vspec['name'],
                    'type' => $vspec['type'],
                    'status' => 'active',
                    'legal_entity_id' => $defaultLegalEntity?->id,
                    'country_id' => $vspec['country']?->id ?? $defaultCountry?->id,
                    'city' => $vspec['city'],
                    'currency_id' => $vspec['currency']?->id ?? $defaultCurrency?->id,
                    'payment_terms' => $vspec['payment_terms'],
                    'manager_id' => $manager?->id,
                    'is_assignable' => true,
                    'can_access_finances' => true,
                    'note' => $vspec['note'],
                ]
            );

            // Contacts
            foreach ($vspec['contacts'] as $vc) {
                VendorContact::firstOrCreate(
                    ['vendor_id' => $vendor->id, 'email' => $vc['email']],
                    [
                        'first_name' => $vc['first_name'],
                        'last_name' => $vc['last_name'],
                        'designation' => $vc['designation'],
                        'phone' => $vc['phone'],
                        'timezone_id' => $defaultTimezone?->id,
                        'gender' => 'other',
                        'is_primary' => true,
                    ]
                );
            }

            // Tags
            foreach ($vspec['tags'] as $tagName) {
                $tag = VendorTag::where('name', $tagName)->first();
                if ($tag && method_exists($vendor, 'tags')) {
                    $vendor->tags()->syncWithoutDetaching([$tag->id]);
                }
            }

            // Vendor Prices across multiple pairs
            foreach ($allLangPairs->take(3) as $lp) {
                foreach ($services->take(2) as $srv) {
                    VendorPrice::firstOrCreate(
                        [
                            'vendor_id' => $vendor->id,
                            'language_pair_id' => $lp->id,
                            'service_id' => $srv->id,
                        ],
                        [
                            'specialization_id' => $specializations->first()?->id,
                            'unit_id' => $sourceWordUnit?->id,
                            'unit_price' => rand(7, 12) / 100, // 0.07 - 0.12 per word
                            'currency_id' => $vendor->currency_id,
                            'note' => 'Standard linguist supply rate for ' . $srv->name,
                            'updated_by' => $manager?->id,
                        ]
                    );
                }
            }
        }

        // -------------------------------------------------------------
        // 10. SEED VENDOR APPLICATIONS
        // -------------------------------------------------------------
        $appData = [
            ['application_number' => 'APP-2026-001', 'name' => 'Carlos Mendez', 'email' => 'carlos.m@example.es', 'native_language' => 'Spanish', 'services_offered' => 'Translation, Proofreading', 'language_pairs_offered' => 'EN -> ES, FR -> ES', 'status' => 'new'],
            ['application_number' => 'APP-2026-002', 'name' => 'Giulia Rossi', 'email' => 'giulia.rossi@example.it', 'native_language' => 'Italian', 'services_offered' => 'Translation, MTPE', 'language_pairs_offered' => 'EN -> IT, DE -> IT', 'status' => 'under_review'],
            ['application_number' => 'APP-2026-003', 'name' => 'Lars Lindqvist', 'email' => 'lars.l@example.se', 'native_language' => 'Swedish', 'services_offered' => 'Translation, DTP', 'language_pairs_offered' => 'EN -> SV', 'status' => 'approved'],
            ['application_number' => 'APP-2026-004', 'name' => 'Ananya Sharma', 'email' => 'ananya.s@example.in', 'native_language' => 'Hindi', 'services_offered' => 'Translation, Subtitling', 'language_pairs_offered' => 'EN -> HI', 'status' => 'new'],
            ['application_number' => 'APP-2026-005', 'name' => 'Dmitri Ivanov', 'email' => 'dmitri.i@example.ru', 'native_language' => 'Russian', 'services_offered' => 'Translation, Engineering', 'language_pairs_offered' => 'EN -> RU, DE -> RU', 'status' => 'under_review'],
        ];

        foreach ($appData as $app) {
            VendorApplication::firstOrCreate(
                ['application_number' => $app['application_number']],
                [
                    'name' => $app['name'],
                    'type' => 'individual',
                    'email' => $app['email'],
                    'phone' => '+1 555 987 6543',
                    'country_id' => $defaultCountry?->id,
                    'native_language' => $app['native_language'],
                    'services_offered' => $app['services_offered'],
                    'language_pairs_offered' => $app['language_pairs_offered'],
                    'status' => $app['status'],
                    'note' => 'Strong translation credentials and CAT tool proficiency.',
                    'reviewed_by' => $manager?->id,
                    'reviewed_at' => now()->subDays(rand(1, 15)),
                ]
            );
        }

        // -------------------------------------------------------------
        // 11. SEED VENDOR PAYABLES, INVOICES & PAYMENTS
        // -------------------------------------------------------------
        $allVendors = Vendor::all();

        $payableJobs = [
            ['number' => 'PAY-2026-001', 'job' => 'JOB-2026-01', 'project' => 'PRJ-2026-001 Clinical Trial Protocol', 'desc' => 'Translation of Phase III trial documents', 'qty' => 8500, 'rate' => 0.09, 'amount' => 765.00, 'status' => 'paid'],
            ['number' => 'PAY-2026-002', 'job' => 'JOB-2026-02', 'project' => 'PRJ-2026-001 Clinical Trial Protocol', 'desc' => 'Independent medical proofreading & QA', 'qty' => 8500, 'rate' => 0.04, 'amount' => 340.00, 'status' => 'paid'],
            ['number' => 'PAY-2026-003', 'job' => 'JOB-2026-03', 'project' => 'PRJ-2026-002 Patent Infringement Filing', 'desc' => 'German legal patent claims translation', 'qty' => 12400, 'rate' => 0.11, 'amount' => 1364.00, 'status' => 'invoiced'],
            ['number' => 'PAY-2026-004', 'job' => 'JOB-2026-04', 'project' => 'PRJ-2026-003 Cloud API Docs Localization', 'desc' => 'Japanese API documentation translation', 'qty' => 15200, 'rate' => 0.10, 'amount' => 1520.00, 'status' => 'invoiced'],
            ['number' => 'PAY-2026-005', 'job' => 'JOB-2026-05', 'project' => 'PRJ-2026-004 Annual ESG Sustainability Report', 'desc' => 'French sustainability text MTPE', 'qty' => 18000, 'rate' => 0.06, 'amount' => 1080.00, 'status' => 'approved'],
            ['number' => 'PAY-2026-006', 'job' => 'JOB-2026-06', 'project' => 'PRJ-2026-005 Medical Ultrasound IFU Manual', 'desc' => 'IFU compliance review and DTP layout', 'qty' => 45, 'rate' => 35.00, 'amount' => 1575.00, 'status' => 'draft'],
            ['number' => 'PAY-2026-007', 'job' => 'JOB-2026-07', 'project' => 'PRJ-2026-006 Arbitration Memorial Transcripts', 'desc' => 'Spanish sworn translation of court evidence', 'qty' => 9800, 'rate' => 0.10, 'amount' => 980.00, 'status' => 'approved'],
            ['number' => 'PAY-2026-008', 'job' => 'JOB-2026-08', 'project' => 'PRJ-2026-007 Mobile Banking App Localization', 'desc' => 'Chinese Simplified string QA & testing', 'qty' => 25, 'rate' => 40.00, 'amount' => 1000.00, 'status' => 'paid'],
        ];

        foreach ($payableJobs as $index => $pj) {
            $assignedVendor = $allVendors[$index % $allVendors->count()];
            VendorPayable::firstOrCreate(
                ['payable_number' => $pj['number']],
                [
                    'vendor_id' => $assignedVendor->id,
                    'job_code' => $pj['job'],
                    'project_name' => $pj['project'],
                    'description' => $pj['desc'],
                    'quantity' => $pj['qty'],
                    'unit_id' => ($pj['qty'] > 100) ? $sourceWordUnit?->id : $hourUnit?->id,
                    'rate' => $pj['rate'],
                    'amount' => $pj['amount'],
                    'currency_id' => $assignedVendor->currency_id ?? $defaultCurrency?->id,
                    'due_date' => now()->addDays(rand(5, 30)),
                    'status' => $pj['status'],
                ]
            );
        }

        // Vendor Invoices
        $invoicesData = [
            ['inv_num' => 'INV-SUP-2026-01', 'vendor_idx' => 0, 'subtotal' => 1105.00, 'tax' => 209.95, 'total' => 1314.95, 'paid' => 1314.95, 'status' => 'paid'],
            ['inv_num' => 'INV-SUP-2026-02', 'vendor_idx' => 1, 'subtotal' => 1364.00, 'tax' => 0.00, 'total' => 1364.00, 'paid' => 0.00, 'status' => 'approved'],
            ['inv_num' => 'INV-SUP-2026-03', 'vendor_idx' => 2, 'subtotal' => 1520.00, 'tax' => 152.00, 'total' => 1672.00, 'paid' => 1000.00, 'status' => 'partially_paid'],
            ['inv_num' => 'INV-SUP-2026-04', 'vendor_idx' => 3, 'subtotal' => 1080.00, 'tax' => 0.00, 'total' => 1080.00, 'paid' => 1080.00, 'status' => 'paid'],
            ['inv_num' => 'INV-SUP-2026-05', 'vendor_idx' => 4, 'subtotal' => 1000.00, 'tax' => 100.00, 'total' => 1100.00, 'paid' => 1100.00, 'status' => 'paid'],
        ];

        foreach ($invoicesData as $inv) {
            $v = $allVendors[$inv['vendor_idx'] % $allVendors->count()];
            $invoice = VendorInvoice::firstOrCreate(
                ['invoice_number' => $inv['inv_num']],
                [
                    'vendor_id' => $v->id,
                    'invoice_date' => now()->subDays(rand(10, 25)),
                    'due_date' => now()->addDays(rand(5, 20)),
                    'currency_id' => $v->currency_id ?? $defaultCurrency?->id,
                    'subtotal' => $inv['subtotal'],
                    'tax' => $inv['tax'],
                    'discount' => 0,
                    'total' => $inv['total'],
                    'paid_amount' => $inv['paid'],
                    'status' => $inv['status'],
                    'notes' => 'Supplier invoice for monthly translation purchase orders.',
                ]
            );

            // Record payment for paid invoice
            if ($inv['paid'] > 0) {
                VendorPayment::firstOrCreate(
                    ['payment_number' => 'PMT-' . $inv['inv_num']],
                    [
                        'vendor_id' => $v->id,
                        'payment_date' => now()->subDays(rand(1, 7)),
                        'amount' => $inv['paid'],
                        'currency_id' => $v->currency_id ?? $defaultCurrency?->id,
                        'payment_method_id' => $wireTransfer?->id,
                        'reference_number' => 'SWIFT-TRANS-' . rand(100000, 999999),
                        'notes' => 'Settlement for invoice ' . $inv['inv_num'],
                        'status' => 'completed',
                    ]
                );
            }
        }
    }
}
