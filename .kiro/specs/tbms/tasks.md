# TBMS — Implementation Tasks

## Milestone 1 — Foundation + Clients

### Environment
- [ ] 1.1 Create `.env` (MySQL) and `.env.example`; generate app key.
- [ ] 1.2 Create the `tbms_linguashine` MySQL database.

### Authentication
- [ ] 2.1 Install Laravel Breeze (Blade) and its npm deps.
- [ ] 2.2 Verify login / registration / dashboard scaffolding.

### Admin template
- [ ] 3.1 Integrate an open-source Tailwind admin layout (sidebar + topbar)
      into `layouts/app.blade.php`.

### Users & Roles
- [ ] 4.1 Add `role` and `is_active` to `users` (migration + model cast/enum).
- [ ] 4.2 Add role helper + Gate/Policy scaffolding.
- [ ] 4.3 Users CRUD (admin only) via Livewire.
- [ ] 4.4 Seed a default admin user.

### Reference data
- [ ] 5.1 Migrations + models: languages, currencies, services, units.
- [ ] 5.2 Seeders with common data.
- [ ] 5.3 Livewire CRUD for each under Settings.

### Clients
- [ ] 6.1 Migrations + models: clients, client_contacts, client_rates.
- [ ] 6.2 Client listing (Livewire table with search/pagination).
- [ ] 6.3 Client create/edit form (Livewire) with contacts.
- [ ] 6.4 Client detail page (overview + contacts + rates tabs).
- [ ] 6.5 Client rates management (Livewire).

### Wrap-up
- [ ] 7.1 Run migrations + seeders.
- [ ] 7.2 `npm run build`; smoke-test routes and dashboard.

## Future Milestones (tracked, not started)
- Milestone 2: Vendors
- Milestone 3: Projects + Jobs
- Milestone 4: Quotes
- Milestone 5: Finance (invoices, POs, payments)
- Milestone 6: Reports
