<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\LegalEntity;
use Illuminate\Database\Seeder;

class LegalEntitySeeder extends Seeder
{
    /**
     * Seed a default legal entity for the agency.
     */
    public function run(): void
    {
        LegalEntity::updateOrCreate(
            ['name' => 'LinguaShine'],
            [
                'country_id' => Country::where('iso2', 'IN')->value('id'),
                'first_client_invoice_number' => 1,
                'first_vendor_invoice_number' => 1,
            ],
        );
    }
}
