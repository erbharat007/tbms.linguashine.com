<?php

namespace Database\Seeders;

use App\Models\PdfTemplate;
use Illuminate\Database\Seeder;

class PdfTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // A default template for each object type.
        foreach (array_keys(PdfTemplate::objectTypes()) as $objectType) {
            PdfTemplate::updateOrCreate(
                ['object_type' => $objectType, 'name' => 'Template 1'],
                [
                    'page_size' => 'A4',
                    'page_orientation' => 'portrait',
                    'margin_top' => 15,
                    'margin_bottom' => 15,
                    'locale' => 'en_US',
                    'header' => null,
                    'footer' => null,
                    'common_note' => null,
                    'include_cat_log' => true,
                ]
            );
        }
    }
}
