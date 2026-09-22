<?php

/*
|--------------------------------------------------------------------------
| Screen Permissions Registry
|--------------------------------------------------------------------------
|
| This is the single source of truth for the screens (permissions) available
| in the system. Roles are granted access to screens by key. When you add a
| new module/screen, register it here and run `php artisan db:seed
| --class=PermissionSeeder` (or migrate:fresh --seed) to sync it.
|
| Each permission has:
|   key   - stable identifier stored in the database and checked in code
|   label - human-friendly name shown in the role editor
| Grouped by module for display in the role editor.
|
*/

return [

    'groups' => [
        'Access Control' => [
            'users.view' => 'Users',
            'roles.view' => 'Roles',
        ],

        'Settings' => [
            'legal-entities.view' => 'Legal entities',
            'object-codes.view' => 'Object codes',
            'payment-methods.view' => 'Payment methods',
            'taxes.view' => 'Tax Configuration',
            'client-portal.view' => 'Client portal',
            'vendor-portal.view' => 'Vendor portal',
            'pdf-templates.view' => 'PDF templates',
            'smtp.view' => 'SMTP server',
        ],

        'System Values' => [
            'language-pairs.view' => 'Language Pairs',
            'languages.view' => 'Languages',
            'currencies.view' => 'Currencies',
            'services.view' => 'Services',
            'specializations.view' => 'Specializations',
            'units.view' => 'Units',
        ],

        'Email Templates' => [
            'quote-email-templates.view' => 'Quote templates',
            'multi-quote-email-templates.view' => 'Multi-quote templates',
            'client-invoice-email-templates.view' => 'Client invoice templates',
        ],

        'Clients' => [
            'clients.view' => 'Clients',
            'client-prices.view' => 'Prices',
        ],
    ],

];
