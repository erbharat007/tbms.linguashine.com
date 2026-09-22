# TBMS — Requirements

## Overview

A Translation Business Management System (TBMS) for a translation agency
(LinguaShine), modeled on Protemos (Agency/Enterprise edition). The system helps
the agency manage clients, vendors (translators/freelancers), translation
projects, the jobs inside those projects, quotes, and finances (client invoices,
client payments, vendor purchase orders, vendor payments), plus reporting.

Stack: Laravel 12, PHP 8.2, MySQL 8, Blade + Livewire, Tailwind admin template,
Laravel Breeze for authentication.

Guiding constraints (from the project owner):
1. Use a nice open-source admin template rather than a hand-built design.
2. Follow standard Laravel conventions unless there is a real reason not to.
3. Keep the code simple and readable so bugs are easy to trace later.

## Glossary

- **Client** — a company (or person) the agency sells translation services to.
- **Client Contact** — an individual person working at a client company.
- **Vendor** — a translator/freelancer (or subcontractor company) the agency buys
  services from.
- **Language Pair** — source language → target language (e.g. EN → FR).
- **Service** — a type of work (Translation, Editing, Proofreading, DTP, etc.).
- **Unit** — how work is measured/priced (word, page, hour, project/flat).
- **Project** — a piece of work for one client; contains jobs and files.
- **Job** — a single task inside a project assigned to a vendor.
- **Quote** — a cost estimate offered to a client before work is confirmed.
- **Invoice** — a bill sent to a client for completed work.
- **Purchase Order (PO)** — what the agency owes a vendor for a job.
- **Payment** — money received from a client or paid to a vendor.

## Roles

- **Admin** — full access, manages users, reference data, and all modules.
- **Project Manager (PM)** — manages clients, vendors, projects, jobs, quotes.
- **Accountant** — manages invoices, POs, and payments; read access to projects.

## Functional Requirements

### R1 — Authentication & Users
- R1.1 Users log in with email + password (Laravel Breeze).
- R1.2 Each user has a role (Admin, PM, Accountant).
- R1.3 Admin can create, edit, deactivate team members.
- R1.4 Access to modules is controlled by role.

### R2 — Reference Data
- R2.1 Manage Languages (name, ISO code).
- R2.2 Manage Currencies (code, symbol, name).
- R2.3 Manage Services (name, description).
- R2.4 Manage Units (name — e.g. word, page, hour, flat).
- R2.5 Reference data is used as lookups throughout the system.

### R3 — Clients
- R3.1 Create/edit/list client companies (name, email, phone, address,
  country, default currency, status, notes).
- R3.2 Manage multiple contacts per client (name, email, phone, position).
- R3.3 Maintain client rates per service + language pair + unit.
- R3.4 View a client's projects, invoices, and payments from the client page.

### R4 — Vendors (later milestone)
- R4.1 Create/edit/list vendors with contact info and status.
- R4.2 Vendor specializations (services + language pairs).
- R4.3 Vendor rates per service + language pair + unit.
- R4.4 Vendor job history and payment records.

### R5 — Projects (later milestone)
- R5.1 Create projects tied to a client, with reference number, languages,
  service, deadlines, status, and PM.
- R5.2 Attach files/documents to a project.
- R5.3 Add jobs to a project and assign each to a vendor with agreed rate/volume.
- R5.4 Track project and job status.

### R6 — Quotes (later milestone)
- R6.1 Create a quote for a client based on services/rates/volumes.
- R6.2 Convert an accepted quote into a project.

### R7 — Finance (later milestone)
- R7.1 Client invoices generated from completed projects/jobs.
- R7.2 Record client payments against invoices.
- R7.3 Vendor purchase orders generated from jobs.
- R7.4 Record vendor payments against POs.
- R7.5 Track outstanding receivables and payables.

### R8 — Reports (later milestone)
- R8.1 Profit per project (client revenue − vendor cost).
- R8.2 Income vs. expenses over a period.
- R8.3 Outstanding receivables / payables.

## Non-Functional Requirements

- N1 Follow Laravel conventions (Eloquent, migrations, resource controllers,
  form requests, policies).
- N2 Simple, readable code with clear naming; avoid premature abstraction.
- N3 Multi-currency aware (amounts store their currency).
- N4 All money stored as decimals with a defined precision.
- N5 Soft-deletes on core business entities where deletion would break history.

## Milestone Plan

- **Milestone 1 (current):** Environment, admin template, auth, users/roles,
  reference data (languages, currencies, services, units), Clients module.
- **Milestone 2:** Vendors module.
- **Milestone 3:** Projects + Jobs.
- **Milestone 4:** Quotes.
- **Milestone 5:** Finance (invoices, POs, payments).
- **Milestone 6:** Reports.
