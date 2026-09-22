<?php

namespace Database\Seeders;

use App\Models\ClientSource;
use App\Models\ClientStatus;
use Illuminate\Database\Seeder;

class ClientLookupSeeder extends Seeder
{
    /**
     * Seed client lookup data: client statuses and sources (Protemos defaults).
     */
    public function run(): void
    {
        // Protemos default client statuses, in order.
        $statuses = ['New', 'Contacted', 'Quote offered', 'Active', 'Rejected', 'Inactive'];
        foreach ($statuses as $index => $name) {
            ClientStatus::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index],
            );
        }

        // Common client sources (starter set; editable later via Sources screen).
        $sources = ['Website', 'Referral', 'Email campaign', 'Social media', 'Trade show', 'Other'];
        foreach ($sources as $index => $name) {
            ClientSource::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $index],
            );
        }
    }
}
