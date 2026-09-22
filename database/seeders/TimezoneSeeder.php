<?php

namespace Database\Seeders;

use App\Models\Timezone;
use DateTimeZone;
use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Seed the timezones master table from PHP's IANA identifier list,
     * building a "(UTC+hh:mm) Region/City" label for each.
     */
    public function run(): void
    {
        foreach (DateTimeZone::listIdentifiers() as $name) {
            Timezone::updateOrCreate(
                ['name' => $name],
                ['label' => $this->label($name)],
            );
        }
    }

    /**
     * Build a display label like "(UTC+05:30) Asia/Kolkata".
     */
    private function label(string $name): string
    {
        $offset = (new DateTimeZone($name))->getOffset(new \DateTime('now', new DateTimeZone('UTC')));
        $sign = $offset < 0 ? '-' : '+';
        $abs = abs($offset);
        $hours = str_pad((string) intdiv($abs, 3600), 2, '0', STR_PAD_LEFT);
        $minutes = str_pad((string) intdiv($abs % 3600, 60), 2, '0', STR_PAD_LEFT);

        return "(UTC{$sign}{$hours}:{$minutes}) {$name}";
    }
}
