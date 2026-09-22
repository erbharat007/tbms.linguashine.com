<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::updateOrCreate(
            ['name' => 'Cash payment'],
            [
                'type' => 'custom',
                'note' => null,
                'details' => [
                    'custom_type' => 'Cash',
                    'details' => 'Cash payment on delivery.',
                ],
                'is_enabled' => true,
                'is_default' => true,
            ]
        );
    }
}
