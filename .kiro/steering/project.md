# Project: TBMS (Translation Business Management System)

This workspace is a Protemos-style Translation Business Management System for a
translation agency (LinguaShine), built in Laravel 12.

## Stack
- Laravel 12, PHP 8.2, MySQL 8
- Blade + Livewire (no SPA)
- Laravel Breeze (Blade) for authentication
- Tailwind CSS with an open-source admin template

## Working agreements
1. Follow standard Laravel conventions unless there is a real reason not to.
2. Keep code simple and readable so bugs are easy to trace later. Avoid
   premature abstraction and heavy packages when a simple approach works.
3. Use a nice open-source admin template rather than hand-built design.
4. Build module by module in milestone order. See `.kiro/specs/tbms/`.

## Milestone order
1. Foundation + Clients (auth, users/roles, reference data, clients)
2. Vendors
3. Projects + Jobs
4. Quotes
5. Finance (invoices, POs, payments)
6. Reports

Refer to `.kiro/specs/tbms/requirements.md`, `design.md`, and `tasks.md` for
detail before implementing a module.
