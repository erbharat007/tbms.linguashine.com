<?php

/*
|--------------------------------------------------------------------------
| Sidebar Navigation (mirrors Protemos)
|--------------------------------------------------------------------------
|
| Single source of truth for the sidebar menu. The structure mirrors the
| Protemos top navigation and its sub-items so the client feels at home.
|
| Each item supports:
|   label      - text shown in the sidebar
|   route      - named route (null => rendered as a disabled "Soon" item)
|   permission - screen permission key required to see it (null => always shown
|                to authenticated users; locked admins always pass)
|   match      - route pattern for active-state highlighting (defaults to route)
|   icon       - key resolved by the sidebar-icon component (top level only)
|   children   - array of sub-items (same shape, without icon)
|
| To activate a "Soon" item later: add its route, and (if access-controlled)
| register the permission key in config/permissions.php + reseed.
|
*/

return [

    ['label' => 'Dashboard', 'route' => 'dashboard', 'permission' => null, 'icon' => 'dashboard'],

    // Team management (our custom screens; Protemos calls this Access control).
    ['label' => 'Access Control', 'icon' => 'access-control', 'children' => [
        ['label' => 'Users', 'route' => 'users.index', 'permission' => 'users.view', 'match' => 'users.*'],
        ['label' => 'Roles', 'route' => 'roles.index', 'permission' => 'roles.view', 'match' => 'roles.*'],
    ]],

    ['label' => 'Settings', 'icon' => 'settings', 'children' => [
        ['label' => 'Account', 'route' => null, 'permission' => null],
        ['label' => 'Legal entities', 'route' => 'legal-entities.index', 'permission' => 'legal-entities.view', 'match' => 'legal-entities.*'],
        ['label' => 'Object codes', 'route' => 'object-codes.index', 'permission' => 'object-codes.view', 'match' => 'object-codes.*'],
        ['label' => 'Payment methods', 'route' => 'payment-methods.index', 'permission' => 'payment-methods.view', 'match' => 'payment-methods.*'],
        ['label' => 'Tax Configuration', 'route' => 'taxes.index', 'permission' => 'taxes.view', 'match' => 'taxes.*'],
        ['label' => 'CAT log conversion schemes', 'route' => null, 'permission' => null],
        ['label' => 'Reminders', 'route' => null, 'permission' => null],
        ['label' => 'SMTP server', 'route' => 'smtp.show', 'permission' => 'smtp.view', 'match' => 'smtp.*'],
        ['label' => 'Client portal', 'route' => 'client-portal.show', 'permission' => 'client-portal.view', 'match' => 'client-portal.*'],
        ['label' => 'Vendor portal', 'route' => 'vendor-portal.show', 'permission' => 'vendor-portal.view', 'match' => 'vendor-portal.*'],
        ['label' => 'PDF templates', 'route' => 'pdf-templates.index', 'permission' => 'pdf-templates.view', 'match' => 'pdf-templates.*'],
    ]],

    // Reference data (Protemos groups these under "System values").
    ['label' => 'System Values', 'icon' => 'system-values', 'children' => [
        ['label' => 'Language Pairs', 'route' => 'language-pairs.index', 'permission' => 'language-pairs.view', 'match' => 'language-pairs.*'],
        ['label' => 'Languages', 'route' => 'languages.index', 'permission' => 'languages.view', 'match' => 'languages.*'],
        ['label' => 'Currencies', 'route' => 'currencies.index', 'permission' => 'currencies.view', 'match' => 'currencies.*'],
        ['label' => 'Services', 'route' => 'services.index', 'permission' => 'services.view', 'match' => 'services.*'],
        ['label' => 'Specializations', 'route' => 'specializations.index', 'permission' => 'specializations.view', 'match' => 'specializations.*'],
        ['label' => 'Units', 'route' => 'units.index', 'permission' => 'units.view', 'match' => 'units.*'],
    ]],

    // Email templates (Protemos groups these under Settings > Email templates).
    ['label' => 'Email Templates', 'icon' => 'email-templates', 'children' => [
        ['label' => 'Quote templates', 'route' => 'quote-email-templates.index', 'permission' => 'quote-email-templates.view', 'match' => 'quote-email-templates.*'],
        ['label' => 'Multi-quote templates', 'route' => 'multi-quote-email-templates.index', 'permission' => 'multi-quote-email-templates.view', 'match' => 'multi-quote-email-templates.*'],
        ['label' => 'Client invoice templates', 'route' => 'client-invoice-email-templates.index', 'permission' => 'client-invoice-email-templates.view', 'match' => 'client-invoice-email-templates.*'],
    ]],

    ['label' => 'Clients', 'icon' => 'clients', 'children' => [
        ['label' => 'Clients', 'route' => 'clients.index', 'permission' => 'clients.view', 'match' => 'clients.*'],
        ['label' => 'New client', 'route' => 'clients.create', 'permission' => 'clients.view'],
        ['label' => 'Receivables', 'route' => null, 'permission' => null],
        ['label' => 'Invoices', 'route' => null, 'permission' => null],
        ['label' => 'Payments', 'route' => null, 'permission' => null],
        ['label' => 'Contacts', 'route' => null, 'permission' => null],
        ['label' => 'Prices', 'route' => 'client-prices.index', 'permission' => 'client-prices.view', 'match' => 'client-prices.*'],
        ['label' => 'Tags', 'route' => null, 'permission' => null],
        ['label' => 'Statuses', 'route' => null, 'permission' => null],
        ['label' => 'Sources', 'route' => null, 'permission' => null],
    ]],

    ['label' => 'Vendors', 'icon' => 'vendors', 'children' => [
        ['label' => 'Vendors', 'route' => null, 'permission' => null],
        ['label' => 'New vendor', 'route' => null, 'permission' => null],
        ['label' => 'Applications', 'route' => null, 'permission' => null],
        ['label' => 'Payables', 'route' => null, 'permission' => null],
        ['label' => 'Invoices', 'route' => null, 'permission' => null],
        ['label' => 'Payments', 'route' => null, 'permission' => null],
        ['label' => 'Contacts', 'route' => null, 'permission' => null],
        ['label' => 'Prices', 'route' => null, 'permission' => null],
        ['label' => 'Tags', 'route' => null, 'permission' => null],
        ['label' => 'Rating categories', 'route' => null, 'permission' => null],
    ]],

    ['label' => 'Projects', 'route' => null, 'permission' => null, 'icon' => 'projects'],

    ['label' => 'Jobs', 'route' => null, 'permission' => null, 'icon' => 'jobs'],

    ['label' => 'To-dos', 'route' => null, 'permission' => null, 'icon' => 'todos'],

    ['label' => 'Expenses', 'route' => null, 'permission' => null, 'icon' => 'expenses'],

    ['label' => 'Files', 'route' => null, 'permission' => null, 'icon' => 'files'],

    ['label' => 'Quotes', 'route' => null, 'permission' => null, 'icon' => 'quotes'],

    ['label' => 'Reports', 'icon' => 'reports', 'children' => [
        ['label' => 'General finances', 'route' => null, 'permission' => null],
        ['label' => 'Quote report', 'route' => null, 'permission' => null],
        ['label' => 'Project report', 'route' => null, 'permission' => null],
        ['label' => 'Job report', 'route' => null, 'permission' => null],
        ['label' => 'Managers', 'route' => null, 'permission' => null],
        ['label' => 'Clients', 'route' => null, 'permission' => null],
        ['label' => 'Vendors', 'route' => null, 'permission' => null],
        ['label' => 'Language pairs', 'route' => null, 'permission' => null],
        ['label' => 'Services', 'route' => null, 'permission' => null],
        ['label' => 'Specializations', 'route' => null, 'permission' => null],
        ['label' => 'Work amount', 'route' => null, 'permission' => null],
        ['label' => 'To-do report', 'route' => null, 'permission' => null],
    ]],

];
