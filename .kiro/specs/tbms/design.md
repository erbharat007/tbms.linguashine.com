# TBMS — Design

## Architecture

Standard Laravel 12 monolith, server-rendered with Blade and Livewire for
interactive CRUD. No SPA layer. This keeps the whole stack in PHP, which matches
the team's skills and keeps debugging simple.

```
Browser
  │
  ├── Blade layouts (admin template: Tailwind)
  │     └── Livewire components (interactive tables, forms)
  │
Laravel (routes → controllers / Livewire → Eloquent models → MySQL)
```

### Key decisions

- **Auth:** Laravel Breeze (Blade stack). Gives login, registration, password
  reset, profile, and a `dashboard` route out of the box.
- **Roles:** A simple `role` column on `users` plus Gate/Policy checks. We avoid
  a heavy permissions package for now to keep things simple (constraint #2/#3).
  Roles enum: `admin`, `pm`, `accountant`.
- **UI:** Free open-source Tailwind admin template layered into Breeze's Blade
  layout. Livewire drives listing tables and forms.
- **Money:** stored as `decimal(15,4)`; every amount is paired with a currency.
- **Soft deletes:** on clients, vendors, projects (history-sensitive entities).

## Data Model (Milestone 1)

### users (extend Breeze default)
- id, name, email, password (default)
- role: enum('admin','pm','accountant') default 'pm'
- is_active: boolean default true
- timestamps

### languages
- id, name, code (ISO 639-1, unique), timestamps

### currencies
- id, code (ISO 4217, unique), symbol, name, timestamps

### services
- id, name (unique), description nullable, is_active, timestamps

### units
- id, name (unique)  // word, page, hour, flat
- timestamps

### clients
- id, name, email nullable, phone nullable
- address nullable, country nullable
- currency_id (FK → currencies) nullable
- status: enum('active','inactive') default 'active'
- notes text nullable
- soft deletes, timestamps

### client_contacts
- id, client_id (FK → clients, cascade)
- name, email nullable, phone nullable, position nullable
- is_primary boolean default false
- timestamps

### client_rates
- id, client_id (FK → clients, cascade)
- service_id (FK → services)
- source_language_id (FK → languages) nullable
- target_language_id (FK → languages) nullable
- unit_id (FK → units)
- rate decimal(15,4)
- currency_id (FK → currencies)
- timestamps

## Relationships

- Client hasMany ClientContact
- Client hasMany ClientRate
- Client belongsTo Currency
- ClientRate belongsTo Service, Unit, Currency, and two Languages
  (source/target)

## Module Structure & Conventions

- Eloquent models in `app/Models`.
- Resourceful routes; thin controllers where non-Livewire.
- Livewire components in `app/Livewire/<Module>` for interactive CRUD
  (e.g. `App\Livewire\Clients\ClientTable`, `ClientForm`).
- Form validation via `FormRequest` for controller actions, and inline
  `rules()` for Livewire components.
- Authorization via Policies + a `role` helper on the User model.
- Seeders for reference data (common languages, currencies, services, units)
  and a default admin user.

## Navigation (admin layout sidebar)

- Dashboard
- Clients
- Vendors (later)
- Projects (later)
- Finance (later)
- Settings
  - Users
  - Languages
  - Currencies
  - Services
  - Units

## Frontend Template Choice

Use a free, MIT/open-source Tailwind admin template compatible with the Breeze
Blade + Tailwind setup. Candidate: a lightweight Tailwind dashboard layout
(sidebar + topbar) adapted into `resources/views/layouts/app.blade.php`. We keep
it minimal and readable rather than pulling a heavy template with lots of unused
assets.
