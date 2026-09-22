<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Seed the default specializations (Protemos defaults).
     */
    public function run(): void
    {
        $specializations = [
            'Engineering',
            'Financial',
            'Information technology',
            'Legal',
            'Marketing',
            'Medical',
            'Other',
        ];

        foreach ($specializations as $name) {
            Specialization::updateOrCreate(['name' => $name], ['is_enabled' => true]);
        }
    }
}
