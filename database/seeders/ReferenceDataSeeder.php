<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Service;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ReferenceDataSeeder extends Seeder
{
    /**
     * Seed common reference data used across the system.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'French', 'code' => 'fr'],
            ['name' => 'German', 'code' => 'de'],
            ['name' => 'Spanish', 'code' => 'es'],
            ['name' => 'Italian', 'code' => 'it'],
            ['name' => 'Portuguese', 'code' => 'pt'],
            ['name' => 'Dutch', 'code' => 'nl'],
            ['name' => 'Russian', 'code' => 'ru'],
            ['name' => 'Chinese', 'code' => 'zh'],
            ['name' => 'Japanese', 'code' => 'ja'],
            ['name' => 'Korean', 'code' => 'ko'],
            ['name' => 'Arabic', 'code' => 'ar'],
            ['name' => 'Hindi', 'code' => 'hi'],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(['code' => $language['code']], $language);
        }

        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_system' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'is_system' => false],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'is_system' => false],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'is_system' => false],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'is_system' => false],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'is_system' => false],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'is_system' => false],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency + ['is_enabled' => true],
            );
        }

        $services = ['Translation', 'Editing', 'Proofreading', 'Localization', 'Machine Translation Post-Editing', 'DTP', 'Transcription', 'Interpretation'];

        foreach ($services as $name) {
            Service::updateOrCreate(['name' => $name], ['is_enabled' => true]);
        }

        $units = [
            ['name' => 'Word', 'is_word' => true],
            ['name' => 'Page', 'is_word' => false],
            ['name' => 'Hour', 'is_word' => false],
            ['name' => 'Line', 'is_word' => false],
            ['name' => 'Character', 'is_word' => false],
            ['name' => 'Flat rate', 'is_word' => false],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['name' => $unit['name']],
                ['is_word' => $unit['is_word'], 'is_enabled' => true],
            );
        }
    }
}
