<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Sync the permissions table with the screen registry in
     * config/permissions.php.
     */
    public function run(): void
    {
        $groups = config('permissions.groups', []);

        foreach ($groups as $group => $permissions) {
            foreach ($permissions as $key => $label) {
                Permission::updateOrCreate(
                    ['key' => $key],
                    ['label' => $label, 'group' => $group],
                );
            }
        }
    }
}
