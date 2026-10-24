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

    ['label' => 'Settings', 'icon' => 'settings', 'children' => [
        ['label' => 'Account', 'route' => 'account.index', 'permission' => 'account.view', 'match' => 'account.*'],
        ['label' => 'License', 'route' => 'license.index', 'permission' => 'license.view', 'match' => 'license.*'],
        ['label' => 'Legal entities', 'route' => 'legal-entities.index', 'permission' => 'legal-entities.view', 'match' => 'legal-entities.*'],
        ['label' => 'Access control', 'children' => [
            ['label' => 'Users', 'route' => 'users.index', 'permission' => 'users.view', 'match' => 'users.*'],
            ['label' => 'Roles', 'route' => 'roles.index', 'permission' => 'roles.view', 'match' => 'roles.*'],
        ]],
        ['label' => 'System values', 'route' => 'system-values.index', 'children' => [
            ['label' => 'Overview', 'route' => 'system-values.index', 'permission' => 'system-values.view', 'match' => 'system-values.*'],
            ['label' => 'Language pairs', 'route' => 'language-pairs.index', 'permission' => 'language-pairs.view', 'match' => 'language-pairs.*'],
            ['label' => 'Services', 'route' => 'services.index', 'permission' => 'services.view', 'match' => 'services.*'],
            ['label' => 'Specializations', 'route' => 'specializations.index', 'permission' => 'specializations.view', 'match' => 'specializations.*'],
            ['label' => 'Units', 'route' => 'units.index', 'permission' => 'units.view', 'match' => 'units.*'],
            ['label' => 'Currencies', 'route' => 'currencies.index', 'permission' => 'currencies.view', 'match' => 'currencies.*'],
            ['label' => 'Languages', 'route' => 'languages.index', 'permission' => 'languages.view', 'match' => 'languages.*'],
        ]],
        ['label' => 'Object codes', 'route' => 'object-codes.index', 'permission' => 'object-codes.view', 'match' => 'object-codes.*'],
        ['label' => 'Payment methods', 'route' => 'payment-methods.index', 'permission' => 'payment-methods.view', 'match' => 'payment-methods.*'],
        ['label' => 'Taxes', 'children' => [
            ['label' => 'Tax Configuration', 'route' => 'taxes.index', 'permission' => 'taxes.view', 'match' => 'taxes.*'],
            ['label' => 'Default taxes', 'route' => 'default-taxes.index', 'permission' => 'taxes.view', 'match' => 'default-taxes.*'],
        ]],
        ['label' => 'CAT log conversion schemes', 'route' => 'cat-log-templates.index', 'permission' => 'cat-log-templates.view', 'match' => 'cat-log-templates.*'],
        ['label' => 'Reminders', 'route' => 'reminders.index', 'permission' => 'reminders.view', 'match' => 'reminders.*'],
        ['label' => 'SMTP server', 'route' => 'smtp.show', 'permission' => 'smtp.view', 'match' => 'smtp.*'],
        ['label' => 'Client portal', 'route' => 'client-portal.show', 'permission' => 'client-portal.view', 'match' => 'client-portal.*'],
        ['label' => 'Vendor portal', 'route' => 'vendor-portal.show', 'permission' => 'vendor-portal.view', 'match' => 'vendor-portal.*'],
        ['label' => 'PDF templates', 'route' => 'pdf-templates.index', 'permission' => 'pdf-templates.view', 'match' => 'pdf-templates.*'],
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
        ['label' => 'Vendors', 'route' => 'vendors.index', 'permission' => 'vendors.view', 'match' => 'vendors.*'],
        ['label' => 'New vendor', 'route' => 'vendors.create', 'permission' => 'vendors.view'],
        ['label' => 'Applications', 'route' => 'vendor-applications.index', 'permission' => 'vendor-applications.view', 'match' => 'vendor-applications.*'],
        ['label' => 'Payables', 'route' => 'vendor-payables.index', 'permission' => 'vendor-payables.view', 'match' => 'vendor-payables.*'],
        ['label' => 'Invoices', 'route' => 'vendor-invoices.index', 'permission' => 'vendor-invoices.view', 'match' => 'vendor-invoices.*'],
        ['label' => 'Payments', 'route' => 'vendor-payments.index', 'permission' => 'vendor-payments.view', 'match' => 'vendor-payments.*'],
        ['label' => 'Contacts', 'route' => 'vendor-contacts.index', 'permission' => 'vendor-contacts.view', 'match' => 'vendor-contacts.*'],
        ['label' => 'Prices', 'route' => 'vendor-prices.index', 'permission' => 'vendor-prices.view', 'match' => 'vendor-prices.*'],
        ['label' => 'Tags', 'route' => 'vendor-tags.index', 'permission' => 'vendor-tags.view', 'match' => 'vendor-tags.*'],
        ['label' => 'Rating categories', 'route' => 'vendor-rating-categories.index', 'permission' => 'vendor-rating-categories.view', 'match' => 'vendor-rating-categories.*'],
    ]],

    ['label' => 'Projects', 'icon' => 'projects', 'children' => [
        ['label' => 'Projects', 'route' => 'projects.index', 'permission' => 'projects.view', 'match' => 'projects.*'],
        ['label' => 'New project', 'route' => 'projects.create', 'permission' => 'projects.create'],
        ['label' => 'Project templates', 'route' => 'project-templates.index', 'permission' => 'project-templates.view', 'match' => 'project-templates.*'],
        ['label' => 'Tags', 'route' => 'project-tags.index', 'permission' => 'project-tags.view', 'match' => 'project-tags.*'],
    ]],

    ['label' => 'Jobs', 'icon' => 'jobs', 'children' => [
        ['label' => 'Jobs', 'route' => 'jobs.index', 'permission' => 'jobs.view', 'match' => 'jobs.*'],
        ['label' => 'Tenders', 'route' => 'job-tenders.index', 'permission' => 'job-tenders.view', 'match' => 'job-tenders.*'],
        ['label' => 'Job chain templates', 'route' => 'job-chain-templates.index', 'permission' => 'job-chain-templates.view', 'match' => 'job-chain-templates.*'],
        ['label' => 'Checklists', 'route' => 'job-checklists.index', 'permission' => 'job-checklists.view', 'match' => 'job-checklists.*'],
        ['label' => 'Tags', 'route' => 'job-tags.index', 'permission' => 'job-tags.view', 'match' => 'job-tags.*'],
    ]],

    ['label' => 'To-dos', 'route' => null, 'permission' => null, 'icon' => 'todos'],

    ['label' => 'Expenses', 'route' => null, 'permission' => null, 'icon' => 'expenses'],

    ['label' => 'Files', 'route' => null, 'permission' => null, 'icon' => 'files'],

    ['label' => 'Quotes', 'route' => null, 'permission' => null, 'icon' => 'quotes'],

    ['label' => 'Reports', 'icon' => 'reports', 'children' => [
        ['label' => 'General finances', 'children' => [
            ['label' => 'General finances by receivables and payables', 'route' => 'reports.general-by-receivables-and-payables', 'permission' => null],
            ['label' => 'General finances by invoices', 'route' => 'reports.general-by-invoices', 'permission' => null],
            ['label' => 'General finances by payments', 'route' => 'reports.general-by-payments', 'permission' => null],
            ['label' => 'Business expenses by category', 'route' => 'reports.expenses-by-category', 'permission' => null],
        ]],
        ['label' => 'Quote report', 'route' => 'reports.quote', 'permission' => null],
        ['label' => 'Project report', 'route' => 'reports.project', 'permission' => null],
        ['label' => 'Job report', 'route' => 'reports.job', 'permission' => null],
        ['label' => 'Managers', 'children' => [
            ['label' => 'Finances by receivables and payables', 'route' => 'reports.manager-finances', 'permission' => null],
        ]],
        ['label' => 'Clients', 'children' => [
            ['label' => 'Finances by receivables and payables', 'route' => 'reports.client-finances', 'permission' => null],
            ['label' => 'Income by invoices', 'route' => 'reports.client-income-by-invoices', 'permission' => null],
            ['label' => 'Income by payments', 'route' => 'reports.client-income-by-payments', 'permission' => null],
            ['label' => 'Average finances', 'route' => 'reports.client-average', 'permission' => null],
        ]],
        ['label' => 'Vendors', 'children' => [
            ['label' => 'Expenses by payables', 'route' => 'reports.vendor-expenses-by-payables', 'permission' => null],
            ['label' => 'Expenses by invoices', 'route' => 'reports.vendor-expenses-by-invoices', 'permission' => null],
            ['label' => 'Expenses by payments', 'route' => 'reports.vendor-expenses-by-payments', 'permission' => null],
        ]],
        ['label' => 'Language pairs', 'children' => [
            ['label' => 'Finances by receivables and payables', 'route' => 'reports.lang-finances', 'permission' => null],
        ]],
        ['label' => 'Services', 'children' => [
            ['label' => 'Finances by receivables and payables', 'route' => 'reports.service-finances', 'permission' => null],
        ]],
        ['label' => 'Specializations', 'children' => [
            ['label' => 'Finances by receivables and payables', 'route' => 'reports.spec-finances', 'permission' => null],
        ]],
        ['label' => 'Work amount', 'children' => [
            ['label' => 'Work amount by receivables', 'route' => 'reports.work-amount-by-receivables', 'permission' => null],
            ['label' => 'Work amount by payables', 'route' => 'reports.work-amount-by-payables', 'permission' => null],
        ]],
        ['label' => 'To-do report', 'route' => 'reports.todo', 'permission' => null],
    ]],

];
