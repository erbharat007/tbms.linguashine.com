<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the default roles and their screen permissions.
     * Assumes PermissionSeeder has already run.
     */
    public function run(): void
    {
        // Administrator: locked, always full access (permissions implied).
        Role::updateOrCreate(
            ['name' => 'Administrator'],
            ['description' => 'Full system access', 'is_locked' => true],
        );

        // Project Manager: operations screens.
        $pm = Role::updateOrCreate(
            ['name' => 'Project Manager'],
            ['description' => 'Manages clients, vendors and projects', 'is_locked' => false],
        );
        $pm->permissions()->sync(
            Permission::whereIn('key', [
                'clients.view',
            ])->pluck('id')
        );

        // Accountant: finance-oriented; for now sees clients (billing context).
        $accountant = Role::updateOrCreate(
            ['name' => 'Accountant'],
            ['description' => 'Manages invoices and payments', 'is_locked' => false],
        );
        $accountant->permissions()->sync(
            Permission::whereIn('key', [
                'clients.view',
            ])->pluck('id')
        );
    }
}
