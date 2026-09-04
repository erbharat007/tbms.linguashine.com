
> **Target:** MySQL 8.0+
> **Application:** Laravel + MySQL
> **Database:** `translation_management_system`

---

# 1. Complete Database SQL

```sql
/* =========================================================
   TRANSLATION MANAGEMENT SYSTEM
   Protemos-like TMS
   MySQL 8.0+
   ========================================================= */

CREATE DATABASE IF NOT EXISTS translation_management_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE translation_management_system;


/* =========================================================
   01. USERS / ACCESS CONTROL
   ========================================================= */

CREATE TABLE tbl_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NULL,
    timezone VARCHAR(100) NULL,
    status ENUM('active','inactive','blocked') NOT NULL DEFAULT 'active',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    module VARCHAR(100) NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_role_user (
    role_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (role_id, user_id),

    CONSTRAINT fk_role_user_role
        FOREIGN KEY (role_id)
        REFERENCES tbl_roles(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_role_user_user
        FOREIGN KEY (user_id)
        REFERENCES tbl_users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE tbl_permission_role (
    permission_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,

    PRIMARY KEY (permission_id, role_id),

    CONSTRAINT fk_permission_role_permission
        FOREIGN KEY (permission_id)
        REFERENCES tbl_permissions(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_permission_role_role
        FOREIGN KEY (role_id)
        REFERENCES tbl_roles(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


/* =========================================================
   02. MASTER DATA
   ========================================================= */

CREATE TABLE tbl_currencies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    symbol VARCHAR(10) NULL,
    decimal_places TINYINT UNSIGNED NOT NULL DEFAULT 2,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_languages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_language_pairs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_language_id BIGINT UNSIGNED NOT NULL,
    target_language_id BIGINT UNSIGNED NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_language_pair (
        source_language_id,
        target_language_id
    ),

    CONSTRAINT fk_language_pair_source
        FOREIGN KEY (source_language_id)
        REFERENCES tbl_languages(id),

    CONSTRAINT fk_language_pair_target
        FOREIGN KEY (target_language_id)
        REFERENCES tbl_languages(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(50) NULL UNIQUE,
    description TEXT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_specializations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(30) NOT NULL UNIQUE,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NULL UNIQUE,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_taxes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rate DECIMAL(10,4) NOT NULL DEFAULT 0,
    type ENUM('percentage','fixed') NOT NULL DEFAULT 'percentage',
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


/* =========================================================
   03. CLIENTS
   ========================================================= */

CREATE TABLE tbl_clients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    client_code VARCHAR(50) NOT NULL UNIQUE,
    company_name VARCHAR(200) NOT NULL,

    email VARCHAR(191) NULL,
    phone VARCHAR(30) NULL,

    address TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    country VARCHAR(100) NULL,
    postal_code VARCHAR(30) NULL,

    currency_id BIGINT UNSIGNED NULL,
    payment_terms INT UNSIGNED NOT NULL DEFAULT 0,
    tax_number VARCHAR(100) NULL,

    manager_id BIGINT UNSIGNED NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    notes TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_client_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id),

    CONSTRAINT fk_client_manager
        FOREIGN KEY (manager_id)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_client_contacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    client_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(150) NOT NULL,
    email VARCHAR(191) NULL,
    phone VARCHAR(30) NULL,
    designation VARCHAR(100) NULL,

    is_primary BOOLEAN NOT NULL DEFAULT FALSE,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_client_contact_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE tbl_client_prices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    client_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    language_pair_id BIGINT UNSIGNED NULL,
    specialization_id BIGINT UNSIGNED NULL,
    unit_id BIGINT UNSIGNED NULL,
    currency_id BIGINT UNSIGNED NOT NULL,

    rate DECIMAL(15,4) NOT NULL DEFAULT 0,

    effective_from DATE NULL,
    effective_to DATE NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_client_price_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_client_price_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id),

    CONSTRAINT fk_client_price_language
        FOREIGN KEY (language_pair_id)
        REFERENCES tbl_language_pairs(id),

    CONSTRAINT fk_client_price_specialization
        FOREIGN KEY (specialization_id)
        REFERENCES tbl_specializations(id),

    CONSTRAINT fk_client_price_unit
        FOREIGN KEY (unit_id)
        REFERENCES tbl_units(id),

    CONSTRAINT fk_client_price_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


/* =========================================================
   04. VENDORS
   ========================================================= */

CREATE TABLE tbl_vendors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vendor_code VARCHAR(50) NOT NULL UNIQUE,

    name VARCHAR(200) NOT NULL,

    vendor_type ENUM('individual','company')
        NOT NULL DEFAULT 'individual',

    email VARCHAR(191) NULL,
    phone VARCHAR(30) NULL,

    address TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    country VARCHAR(100) NULL,
    postal_code VARCHAR(30) NULL,

    currency_id BIGINT UNSIGNED NULL,

    tax_number VARCHAR(100) NULL,
    payment_terms INT UNSIGNED NOT NULL DEFAULT 0,

    manager_id BIGINT UNSIGNED NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    notes TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id),

    CONSTRAINT fk_vendor_manager
        FOREIGN KEY (manager_id)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_vendor_contacts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vendor_id BIGINT UNSIGNED NOT NULL,

    name VARCHAR(150) NOT NULL,
    email VARCHAR(191) NULL,
    phone VARCHAR(30) NULL,
    designation VARCHAR(100) NULL,

    is_primary BOOLEAN NOT NULL DEFAULT FALSE,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_contact_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE tbl_vendor_prices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vendor_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED NOT NULL,
    language_pair_id BIGINT UNSIGNED NULL,
    specialization_id BIGINT UNSIGNED NULL,
    unit_id BIGINT UNSIGNED NULL,
    currency_id BIGINT UNSIGNED NOT NULL,

    rate DECIMAL(15,4) NOT NULL DEFAULT 0,

    effective_from DATE NULL,
    effective_to DATE NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_price_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_vendor_price_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id),

    CONSTRAINT fk_vendor_price_language
        FOREIGN KEY (language_pair_id)
        REFERENCES tbl_language_pairs(id),

    CONSTRAINT fk_vendor_price_specialization
        FOREIGN KEY (specialization_id)
        REFERENCES tbl_specializations(id),

    CONSTRAINT fk_vendor_price_unit
        FOREIGN KEY (unit_id)
        REFERENCES tbl_units(id),

    CONSTRAINT fk_vendor_price_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


/* =========================================================
   05. QUOTES
   ========================================================= */

CREATE TABLE tbl_quotes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    quote_number VARCHAR(50) NOT NULL UNIQUE,

    client_id BIGINT UNSIGNED NOT NULL,
    contact_id BIGINT UNSIGNED NULL,
    manager_id BIGINT UNSIGNED NULL,

    quote_date DATE NOT NULL,
    valid_until DATE NULL,

    currency_id BIGINT UNSIGNED NOT NULL,

    subtotal DECIMAL(18,2) NOT NULL DEFAULT 0,
    discount DECIMAL(18,2) NOT NULL DEFAULT 0,
    tax DECIMAL(18,2) NOT NULL DEFAULT 0,
    total DECIMAL(18,2) NOT NULL DEFAULT 0,

    status ENUM(
        'draft',
        'sent',
        'accepted',
        'rejected',
        'expired',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',

    notes TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_quote_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_quote_contact
        FOREIGN KEY (contact_id)
        REFERENCES tbl_client_contacts(id),

    CONSTRAINT fk_quote_manager
        FOREIGN KEY (manager_id)
        REFERENCES tbl_users(id),

    CONSTRAINT fk_quote_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_quote_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    quote_id BIGINT UNSIGNED NOT NULL,

    service_id BIGINT UNSIGNED NULL,
    language_pair_id BIGINT UNSIGNED NULL,
    specialization_id BIGINT UNSIGNED NULL,
    unit_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    quantity DECIMAL(15,4) NOT NULL DEFAULT 1,
    rate DECIMAL(15,4) NOT NULL DEFAULT 0,
    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_quote_item_quote
        FOREIGN KEY (quote_id)
        REFERENCES tbl_quotes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_quote_item_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id),

    CONSTRAINT fk_quote_item_language
        FOREIGN KEY (language_pair_id)
        REFERENCES tbl_language_pairs(id),

    CONSTRAINT fk_quote_item_specialization
        FOREIGN KEY (specialization_id)
        REFERENCES tbl_specializations(id),

    CONSTRAINT fk_quote_item_unit
        FOREIGN KEY (unit_id)
        REFERENCES tbl_units(id)
) ENGINE=InnoDB;


/* =========================================================
   06. MULTI QUOTES
   ========================================================= */

CREATE TABLE tbl_multi_quotes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    multi_quote_number VARCHAR(50) NOT NULL UNIQUE,

    client_id BIGINT UNSIGNED NOT NULL,
    manager_id BIGINT UNSIGNED NULL,

    title VARCHAR(255) NULL,
    description TEXT NULL,

    status ENUM(
        'draft',
        'sent',
        'accepted',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_multi_quote_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_multi_quote_manager
        FOREIGN KEY (manager_id)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_multi_quote_quotes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    multi_quote_id BIGINT UNSIGNED NOT NULL,
    quote_id BIGINT UNSIGNED NOT NULL,

    option_name VARCHAR(150) NULL,
    sequence_no INT UNSIGNED NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_multi_quote_quote (
        multi_quote_id,
        quote_id
    ),

    CONSTRAINT fk_multi_quote_quotes_multi
        FOREIGN KEY (multi_quote_id)
        REFERENCES tbl_multi_quotes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_multi_quote_quotes_quote
        FOREIGN KEY (quote_id)
        REFERENCES tbl_quotes(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


/* =========================================================
   07. PROJECTS
   ========================================================= */

CREATE TABLE tbl_projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    project_number VARCHAR(50) NOT NULL UNIQUE,

    client_id BIGINT UNSIGNED NOT NULL,
    quote_id BIGINT UNSIGNED NULL,
    manager_id BIGINT UNSIGNED NULL,

    name VARCHAR(255) NOT NULL,
    description TEXT NULL,

    service_id BIGINT UNSIGNED NULL,
    language_pair_id BIGINT UNSIGNED NULL,
    specialization_id BIGINT UNSIGNED NULL,

    start_date DATE NULL,
    due_date DATE NULL,
    completed_at DATETIME NULL,

    currency_id BIGINT UNSIGNED NULL,

    status ENUM(
        'draft',
        'active',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_project_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_project_quote
        FOREIGN KEY (quote_id)
        REFERENCES tbl_quotes(id),

    CONSTRAINT fk_project_manager
        FOREIGN KEY (manager_id)
        REFERENCES tbl_users(id),

    CONSTRAINT fk_project_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id),

    CONSTRAINT fk_project_language
        FOREIGN KEY (language_pair_id)
        REFERENCES tbl_language_pairs(id),

    CONSTRAINT fk_project_specialization
        FOREIGN KEY (specialization_id)
        REFERENCES tbl_specializations(id),

    CONSTRAINT fk_project_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


/* =========================================================
   08. WORKFLOW TEMPLATES
   ========================================================= */

CREATE TABLE tbl_workflow_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    description TEXT NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_workflow_template_steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    workflow_template_id BIGINT UNSIGNED NOT NULL,

    sequence_no INT UNSIGNED NOT NULL,

    service_id BIGINT UNSIGNED NULL,

    name VARCHAR(150) NOT NULL,
    description TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_workflow_step_template
        FOREIGN KEY (workflow_template_id)
        REFERENCES tbl_workflow_templates(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_workflow_step_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id)
) ENGINE=InnoDB;


/* =========================================================
   09. PROJECT WORKFLOW
   ========================================================= */

CREATE TABLE tbl_project_workflows (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    project_id BIGINT UNSIGNED NOT NULL,
    workflow_template_id BIGINT UNSIGNED NULL,

    status ENUM(
        'pending',
        'in_progress',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_project_workflow_project
        FOREIGN KEY (project_id)
        REFERENCES tbl_projects(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_project_workflow_template
        FOREIGN KEY (workflow_template_id)
        REFERENCES tbl_workflow_templates(id)
) ENGINE=InnoDB;


/* =========================================================
   10. JOBS
   ========================================================= */

CREATE TABLE tbl_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    job_number VARCHAR(50) NOT NULL UNIQUE,

    project_id BIGINT UNSIGNED NOT NULL,
    vendor_id BIGINT UNSIGNED NULL,
    manager_id BIGINT UNSIGNED NULL,

    parent_job_id BIGINT UNSIGNED NULL,

    service_id BIGINT UNSIGNED NULL,
    language_pair_id BIGINT UNSIGNED NULL,
    specialization_id BIGINT UNSIGNED NULL,
    unit_id BIGINT UNSIGNED NULL,

    description TEXT NULL,
    instructions TEXT NULL,

    start_date DATE NULL,
    due_date DATE NULL,

    quantity DECIMAL(15,4) NOT NULL DEFAULT 0,
    rate DECIMAL(15,4) NOT NULL DEFAULT 0,
    total DECIMAL(18,2) NOT NULL DEFAULT 0,

    status ENUM(
        'new',
        'assigned',
        'in_progress',
        'submitted',
        'accepted',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'new',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_job_project
        FOREIGN KEY (project_id)
        REFERENCES tbl_projects(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_job_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id),

    CONSTRAINT fk_job_manager
        FOREIGN KEY (manager_id)
        REFERENCES tbl_users(id),

    CONSTRAINT fk_job_parent
        FOREIGN KEY (parent_job_id)
        REFERENCES tbl_jobs(id),

    CONSTRAINT fk_job_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id),

    CONSTRAINT fk_job_language
        FOREIGN KEY (language_pair_id)
        REFERENCES tbl_language_pairs(id),

    CONSTRAINT fk_job_specialization
        FOREIGN KEY (specialization_id)
        REFERENCES tbl_specializations(id),

    CONSTRAINT fk_job_unit
        FOREIGN KEY (unit_id)
        REFERENCES tbl_units(id)
) ENGINE=InnoDB;


/* =========================================================
   11. RECEIVABLES
   ========================================================= */

CREATE TABLE tbl_receivables (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    client_id BIGINT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    quantity DECIMAL(15,4) NOT NULL DEFAULT 1,
    rate DECIMAL(15,4) NOT NULL DEFAULT 0,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    currency_id BIGINT UNSIGNED NOT NULL,

    due_date DATE NULL,

    status ENUM(
        'open',
        'partially_invoiced',
        'invoiced',
        'paid',
        'written_off'
    ) NOT NULL DEFAULT 'open',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_receivable_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_receivable_project
        FOREIGN KEY (project_id)
        REFERENCES tbl_projects(id),

    CONSTRAINT fk_receivable_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


/* =========================================================
   12. CLIENT INVOICES
   ========================================================= */

CREATE TABLE tbl_client_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_number VARCHAR(50) NOT NULL UNIQUE,

    client_id BIGINT UNSIGNED NOT NULL,

    invoice_date DATE NOT NULL,
    due_date DATE NULL,

    currency_id BIGINT UNSIGNED NOT NULL,

    subtotal DECIMAL(18,2) NOT NULL DEFAULT 0,
    discount DECIMAL(18,2) NOT NULL DEFAULT 0,
    tax DECIMAL(18,2) NOT NULL DEFAULT 0,
    total DECIMAL(18,2) NOT NULL DEFAULT 0,

    paid_amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    status ENUM(
        'draft',
        'sent',
        'partially_paid',
        'paid',
        'overdue',
        'cancelled',
        'written_off'
    ) NOT NULL DEFAULT 'draft',

    notes TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_client_invoice_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_client_invoice_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_client_invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_id BIGINT UNSIGNED NOT NULL,

    description TEXT NOT NULL,

    quantity DECIMAL(15,4) NOT NULL DEFAULT 1,
    unit_id BIGINT UNSIGNED NULL,

    rate DECIMAL(15,4) NOT NULL DEFAULT 0,
    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    tax_amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_client_invoice_item_invoice
        FOREIGN KEY (invoice_id)
        REFERENCES tbl_client_invoices(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_client_invoice_item_unit
        FOREIGN KEY (unit_id)
        REFERENCES tbl_units(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_client_invoice_receivables (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_id BIGINT UNSIGNED NOT NULL,
    receivable_id BIGINT UNSIGNED NOT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    UNIQUE KEY uq_invoice_receivable (
        invoice_id,
        receivable_id
    ),

    CONSTRAINT fk_invoice_receivable_invoice
        FOREIGN KEY (invoice_id)
        REFERENCES tbl_client_invoices(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_invoice_receivable_receivable
        FOREIGN KEY (receivable_id)
        REFERENCES tbl_receivables(id)
) ENGINE=InnoDB;


/* =========================================================
   13. CLIENT PAYMENTS
   ========================================================= */

CREATE TABLE tbl_client_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    payment_number VARCHAR(50) NOT NULL UNIQUE,

    client_id BIGINT UNSIGNED NOT NULL,

    payment_date DATE NOT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    currency_id BIGINT UNSIGNED NOT NULL,

    payment_method_id BIGINT UNSIGNED NULL,

    reference_number VARCHAR(150) NULL,

    notes TEXT NULL,

    status ENUM(
        'received',
        'cancelled'
    ) NOT NULL DEFAULT 'received',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_client_payment_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_client_payment_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id),

    CONSTRAINT fk_client_payment_method
        FOREIGN KEY (payment_method_id)
        REFERENCES tbl_payment_methods(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_client_payment_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    payment_id BIGINT UNSIGNED NOT NULL,
    invoice_id BIGINT UNSIGNED NOT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    UNIQUE KEY uq_client_payment_invoice (
        payment_id,
        invoice_id
    ),

    CONSTRAINT fk_client_payment_invoice_payment
        FOREIGN KEY (payment_id)
        REFERENCES tbl_client_payments(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_client_payment_invoice_invoice
        FOREIGN KEY (invoice_id)
        REFERENCES tbl_client_invoices(id)
) ENGINE=InnoDB;


/* =========================================================
   14. PAYABLES
   ========================================================= */

CREATE TABLE tbl_payables (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vendor_id BIGINT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED NULL,
    job_id BIGINT UNSIGNED NULL,

    description TEXT NULL,

    quantity DECIMAL(15,4) NOT NULL DEFAULT 1,
    rate DECIMAL(15,4) NOT NULL DEFAULT 0,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    currency_id BIGINT UNSIGNED NOT NULL,

    due_date DATE NULL,

    status ENUM(
        'open',
        'invoiced',
        'paid',
        'written_off'
    ) NOT NULL DEFAULT 'open',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_payable_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id),

    CONSTRAINT fk_payable_project
        FOREIGN KEY (project_id)
        REFERENCES tbl_projects(id),

    CONSTRAINT fk_payable_job
        FOREIGN KEY (job_id)
        REFERENCES tbl_jobs(id),

    CONSTRAINT fk_payable_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


/* =========================================================
   15. VENDOR INVOICES
   ========================================================= */

CREATE TABLE tbl_vendor_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_number VARCHAR(50) NOT NULL UNIQUE,

    vendor_id BIGINT UNSIGNED NOT NULL,

    invoice_date DATE NOT NULL,
    due_date DATE NULL,

    currency_id BIGINT UNSIGNED NOT NULL,

    subtotal DECIMAL(18,2) NOT NULL DEFAULT 0,
    tax DECIMAL(18,2) NOT NULL DEFAULT 0,
    discount DECIMAL(18,2) NOT NULL DEFAULT 0,
    total DECIMAL(18,2) NOT NULL DEFAULT 0,

    paid_amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    status ENUM(
        'draft',
        'received',
        'partially_paid',
        'paid',
        'overdue',
        'cancelled',
        'written_off'
    ) NOT NULL DEFAULT 'draft',

    notes TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_invoice_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id),

    CONSTRAINT fk_vendor_invoice_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_vendor_invoice_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_id BIGINT UNSIGNED NOT NULL,

    description TEXT NOT NULL,

    quantity DECIMAL(15,4) NOT NULL DEFAULT 1,
    unit_id BIGINT UNSIGNED NULL,

    rate DECIMAL(15,4) NOT NULL DEFAULT 0,
    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    tax_amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_invoice_item_invoice
        FOREIGN KEY (invoice_id)
        REFERENCES tbl_vendor_invoices(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_vendor_invoice_item_unit
        FOREIGN KEY (unit_id)
        REFERENCES tbl_units(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_vendor_invoice_payables (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    invoice_id BIGINT UNSIGNED NOT NULL,
    payable_id BIGINT UNSIGNED NOT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    UNIQUE KEY uq_vendor_invoice_payable (
        invoice_id,
        payable_id
    ),

    CONSTRAINT fk_vendor_invoice_payable_invoice
        FOREIGN KEY (invoice_id)
        REFERENCES tbl_vendor_invoices(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_vendor_invoice_payable_payable
        FOREIGN KEY (payable_id)
        REFERENCES tbl_payables(id)
) ENGINE=InnoDB;


/* =========================================================
   16. VENDOR PAYMENTS
   ========================================================= */

CREATE TABLE tbl_vendor_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    payment_number VARCHAR(50) NOT NULL UNIQUE,

    vendor_id BIGINT UNSIGNED NOT NULL,

    payment_date DATE NOT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    currency_id BIGINT UNSIGNED NOT NULL,

    payment_method_id BIGINT UNSIGNED NULL,

    reference_number VARCHAR(150) NULL,

    notes TEXT NULL,

    status ENUM(
        'paid',
        'cancelled'
    ) NOT NULL DEFAULT 'paid',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_vendor_payment_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id),

    CONSTRAINT fk_vendor_payment_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id),

    CONSTRAINT fk_vendor_payment_method
        FOREIGN KEY (payment_method_id)
        REFERENCES tbl_payment_methods(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_vendor_payment_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    payment_id BIGINT UNSIGNED NOT NULL,
    invoice_id BIGINT UNSIGNED NOT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    UNIQUE KEY uq_vendor_payment_invoice (
        payment_id,
        invoice_id
    ),

    CONSTRAINT fk_vendor_payment_invoice_payment
        FOREIGN KEY (payment_id)
        REFERENCES tbl_vendor_payments(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_vendor_payment_invoice_invoice
        FOREIGN KEY (invoice_id)
        REFERENCES tbl_vendor_invoices(id)
) ENGINE=InnoDB;


/* =========================================================
   17. FILES
   ========================================================= */

CREATE TABLE tbl_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    uploaded_by BIGINT UNSIGNED NULL,

    fileable_type VARCHAR(100) NOT NULL,
    fileable_id BIGINT UNSIGNED NOT NULL,

    original_name VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path TEXT NOT NULL,

    mime_type VARCHAR(150) NULL,
    file_size BIGINT UNSIGNED NULL,

    version INT UNSIGNED NOT NULL DEFAULT 1,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_fileable (
        fileable_type,
        fileable_id
    ),

    CONSTRAINT fk_file_uploaded_by
        FOREIGN KEY (uploaded_by)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_file_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    file_id BIGINT UNSIGNED NOT NULL,

    version INT UNSIGNED NOT NULL,

    file_name VARCHAR(255) NOT NULL,
    file_path TEXT NOT NULL,

    file_size BIGINT UNSIGNED NULL,
    mime_type VARCHAR(150) NULL,

    uploaded_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_file_version_file
        FOREIGN KEY (file_id)
        REFERENCES tbl_files(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_file_version_user
        FOREIGN KEY (uploaded_by)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


/* =========================================================
   18. MESSAGES
   ========================================================= */

CREATE TABLE tbl_messages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    sender_id BIGINT UNSIGNED NOT NULL,

    messageable_type VARCHAR(100) NULL,
    messageable_id BIGINT UNSIGNED NULL,

    parent_id BIGINT UNSIGNED NULL,

    subject VARCHAR(255) NULL,
    message LONGTEXT NOT NULL,

    is_read BOOLEAN NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_messageable (
        messageable_type,
        messageable_id
    ),

    CONSTRAINT fk_message_sender
        FOREIGN KEY (sender_id)
        REFERENCES tbl_users(id),

    CONSTRAINT fk_message_parent
        FOREIGN KEY (parent_id)
        REFERENCES tbl_messages(id)
) ENGINE=InnoDB;


/* =========================================================
   19. TO-DOS
   ========================================================= */

CREATE TABLE tbl_todos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    created_by BIGINT UNSIGNED NOT NULL,
    assigned_to BIGINT UNSIGNED NULL,

    todoable_type VARCHAR(100) NULL,
    todoable_id BIGINT UNSIGNED NULL,

    title VARCHAR(255) NOT NULL,
    description TEXT NULL,

    priority ENUM(
        'low',
        'medium',
        'high',
        'urgent'
    ) NOT NULL DEFAULT 'medium',

    due_date DATETIME NULL,

    status ENUM(
        'pending',
        'in_progress',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    completed_at DATETIME NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_todoable (
        todoable_type,
        todoable_id
    ),

    CONSTRAINT fk_todo_created_by
        FOREIGN KEY (created_by)
        REFERENCES tbl_users(id),

    CONSTRAINT fk_todo_assigned_to
        FOREIGN KEY (assigned_to)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


/* =========================================================
   20. NOTIFICATIONS
   ========================================================= */

CREATE TABLE tbl_notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    type VARCHAR(100) NOT NULL,

    title VARCHAR(255) NOT NULL,
    message TEXT NULL,

    related_type VARCHAR(100) NULL,
    related_id BIGINT UNSIGNED NULL,

    read_at DATETIME NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_notification_related (
        related_type,
        related_id
    ),

    CONSTRAINT fk_notification_user
        FOREIGN KEY (user_id)
        REFERENCES tbl_users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


/* =========================================================
   21. EXPENSES
   ========================================================= */

CREATE TABLE tbl_expense_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_expenses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    category_id BIGINT UNSIGNED NULL,

    project_id BIGINT UNSIGNED NULL,
    client_id BIGINT UNSIGNED NULL,

    expense_date DATE NOT NULL,

    description TEXT NULL,

    amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    currency_id BIGINT UNSIGNED NOT NULL,

    tax_amount DECIMAL(18,2) NOT NULL DEFAULT 0,

    payment_method_id BIGINT UNSIGNED NULL,

    status ENUM(
        'draft',
        'approved',
        'cancelled'
    ) NOT NULL DEFAULT 'draft',

    created_by BIGINT UNSIGNED NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_expense_category
        FOREIGN KEY (category_id)
        REFERENCES tbl_expense_categories(id),

    CONSTRAINT fk_expense_project
        FOREIGN KEY (project_id)
        REFERENCES tbl_projects(id),

    CONSTRAINT fk_expense_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id),

    CONSTRAINT fk_expense_currency
        FOREIGN KEY (currency_id)
        REFERENCES tbl_currencies(id),

    CONSTRAINT fk_expense_payment_method
        FOREIGN KEY (payment_method_id)
        REFERENCES tbl_payment_methods(id),

    CONSTRAINT fk_expense_created_by
        FOREIGN KEY (created_by)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


/* =========================================================
   22. PROJECT TEMPLATES
   ========================================================= */

CREATE TABLE tbl_project_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    description TEXT NULL,

    service_id BIGINT UNSIGNED NULL,
    language_pair_id BIGINT UNSIGNED NULL,
    specialization_id BIGINT UNSIGNED NULL,

    workflow_template_id BIGINT UNSIGNED NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_project_template_service
        FOREIGN KEY (service_id)
        REFERENCES tbl_services(id),

    CONSTRAINT fk_project_template_language
        FOREIGN KEY (language_pair_id)
        REFERENCES tbl_language_pairs(id),

    CONSTRAINT fk_project_template_specialization
        FOREIGN KEY (specialization_id)
        REFERENCES tbl_specializations(id),

    CONSTRAINT fk_project_template_workflow
        FOREIGN KEY (workflow_template_id)
        REFERENCES tbl_workflow_templates(id)
) ENGINE=InnoDB;


/* =========================================================
   23. EMAIL / PDF TEMPLATES
   ========================================================= */

CREATE TABLE tbl_email_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    code VARCHAR(100) NOT NULL UNIQUE,

    subject VARCHAR(255) NULL,
    body LONGTEXT NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE tbl_pdf_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    code VARCHAR(100) NOT NULL UNIQUE,

    document_type ENUM(
        'quote',
        'client_invoice',
        'vendor_invoice',
        'job',
        'other'
    ) NOT NULL,

    template_content LONGTEXT NULL,

    status ENUM('active','inactive') NOT NULL DEFAULT 'active',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


/* =========================================================
   24. COMPANY / SYSTEM SETTINGS
   ========================================================= */

CREATE TABLE tbl_company_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    company_name VARCHAR(255) NULL,
    legal_name VARCHAR(255) NULL,

    email VARCHAR(191) NULL,
    phone VARCHAR(50) NULL,

    address TEXT NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(100) NULL,
    country VARCHAR(100) NULL,
    postal_code VARCHAR(30) NULL,

    tax_number VARCHAR(100) NULL,

    logo VARCHAR(255) NULL,

    default_currency_id BIGINT UNSIGNED NULL,

    timezone VARCHAR(100) NULL DEFAULT 'Asia/Kolkata',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_company_default_currency
        FOREIGN KEY (default_currency_id)
        REFERENCES tbl_currencies(id)
) ENGINE=InnoDB;


CREATE TABLE tbl_system_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    setting_key VARCHAR(150) NOT NULL UNIQUE,
    setting_value LONGTEXT NULL,

    setting_type VARCHAR(50) NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


/* =========================================================
   25. AUDIT LOGS
   ========================================================= */

CREATE TABLE tbl_audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NULL,

    auditable_type VARCHAR(100) NOT NULL,
    auditable_id BIGINT UNSIGNED NOT NULL,

    action VARCHAR(50) NOT NULL,

    old_values JSON NULL,
    new_values JSON NULL,

    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_auditable (
        auditable_type,
        auditable_id
    ),

    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


/* =========================================================
   26. JOB STATUS HISTORY
   ========================================================= */

CREATE TABLE tbl_job_status_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    job_id BIGINT UNSIGNED NOT NULL,

    old_status VARCHAR(50) NULL,
    new_status VARCHAR(50) NOT NULL,

    changed_by BIGINT UNSIGNED NULL,

    remarks TEXT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_job_history_job
        FOREIGN KEY (job_id)
        REFERENCES tbl_jobs(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_job_history_user
        FOREIGN KEY (changed_by)
        REFERENCES tbl_users(id)
) ENGINE=InnoDB;


/* =========================================================
   27. PROJECT FILES / JOB FILES / QUOTE FILES
   =========================================================
   These optional mapping tables provide cleaner reporting
   while tbl_files remains the physical file repository.
   ========================================================= */

CREATE TABLE tbl_project_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    project_id BIGINT UNSIGNED NOT NULL,
    file_id BIGINT UNSIGNED NOT NULL,

    file_type ENUM(
        'source',
        'reference',
        'final',
        'other'
    ) NOT NULL DEFAULT 'other',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_project_file (
        project_id,
        file_id
    ),

    CONSTRAINT fk_project_file_project
        FOREIGN KEY (project_id)
        REFERENCES tbl_projects(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_project_file_file
        FOREIGN KEY (file_id)
        REFERENCES tbl_files(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE tbl_job_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    job_id BIGINT UNSIGNED NOT NULL,
    file_id BIGINT UNSIGNED NOT NULL,

    file_type ENUM(
        'input',
        'output',
        'reference',
        'other'
    ) NOT NULL DEFAULT 'other',

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_job_file (
        job_id,
        file_id
    ),

    CONSTRAINT fk_job_file_job
        FOREIGN KEY (job_id)
        REFERENCES tbl_jobs(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_job_file_file
        FOREIGN KEY (file_id)
        REFERENCES tbl_files(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE tbl_quote_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    quote_id BIGINT UNSIGNED NOT NULL,
    file_id BIGINT UNSIGNED NOT NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_quote_file (
        quote_id,
        file_id
    ),

    CONSTRAINT fk_quote_file_quote
        FOREIGN KEY (quote_id)
        REFERENCES tbl_quotes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_quote_file_file
        FOREIGN KEY (file_id)
        REFERENCES tbl_files(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


/* =========================================================
   28. CLIENT / VENDOR DOCUMENT MAPPING
   ========================================================= */

CREATE TABLE tbl_client_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    client_id BIGINT UNSIGNED NOT NULL,
    file_id BIGINT UNSIGNED NOT NULL,

    document_type VARCHAR(100) NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_client_document (
        client_id,
        file_id
    ),

    CONSTRAINT fk_client_document_client
        FOREIGN KEY (client_id)
        REFERENCES tbl_clients(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_client_document_file
        FOREIGN KEY (file_id)
        REFERENCES tbl_files(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE tbl_vendor_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    vendor_id BIGINT UNSIGNED NOT NULL,
    file_id BIGINT UNSIGNED NOT NULL,

    document_type VARCHAR(100) NULL,

    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_vendor_document (
        vendor_id,
        file_id
    ),

    CONSTRAINT fk_vendor_document_vendor
        FOREIGN KEY (vendor_id)
        REFERENCES tbl_vendors(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_vendor_document_file
        FOREIGN KEY (file_id)
        REFERENCES tbl_files(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


/* =========================================================
   END
   ========================================================= */
```

---

# 2. Number of Tables

This structure contains approximately **57 tables** covering the core TMS.

### Authentication

```text
tbl_users
tbl_roles
tbl_permissions
tbl_role_user
tbl_permission_role
```

### Master

```text
tbl_currencies
tbl_languages
tbl_language_pairs
tbl_services
tbl_specializations
tbl_units
tbl_payment_methods
tbl_taxes
```

### CRM

```text
tbl_clients
tbl_client_contacts
tbl_client_prices
tbl_client_documents

tbl_vendors
tbl_vendor_contacts
tbl_vendor_prices
tbl_vendor_documents
```

### Quotes

```text
tbl_quotes
tbl_quote_items
tbl_quote_files

tbl_multi_quotes
tbl_multi_quote_quotes
```

### Projects / Workflow

```text
tbl_projects
tbl_project_workflows
tbl_project_templates

tbl_workflow_templates
tbl_workflow_template_steps
```

### Jobs

```text
tbl_jobs
tbl_job_files
tbl_job_status_history
```

### Client Finance

```text
tbl_receivables
tbl_client_invoices
tbl_client_invoice_items
tbl_client_invoice_receivables
tbl_client_payments
tbl_client_payment_invoices
```

### Vendor Finance

```text
tbl_payables
tbl_vendor_invoices
tbl_vendor_invoice_items
tbl_vendor_invoice_payables
tbl_vendor_payments
tbl_vendor_payment_invoices
```

### Files / Communication

```text
tbl_files
tbl_file_versions
tbl_messages
tbl_todos
tbl_notifications
```

### Expenses

```text
tbl_expense_categories
tbl_expenses
```

### Templates / Settings

```text
tbl_email_templates
tbl_pdf_templates
tbl_company_settings
tbl_system_settings
```

### Audit

```text
tbl_audit_logs
```

---

# 3. Core ER Diagram

The following is the **actual relationship model** represented by the SQL above.

```text
                                      ┌──────────────────┐
                                      │    tbl_users     │
                                      └────────┬─────────┘
                                               │
                          ┌────────────────────┼────────────────────┐
                          │                    │                    │
                          ▼                    ▼                    ▼
                    tbl_clients          tbl_quotes          tbl_projects
                          │                    │                    │
          ┌───────────────┼──────────────┐     │          ┌─────────┼──────────┐
          │               │              │     │          │         │          │
          ▼               ▼              ▼     ▼          ▼         ▼          ▼
 client_contacts   client_prices   client_documents  quote_items  jobs   receivables
                                                            │        │          │
                                                            │        │          │
                                                            │        ▼          │
                                                            │     tbl_vendors    │
                                                            │        │          │
                                                            │        ▼          │
                                                            │    payables        │
                                                            │        │          │
                                                            │        ▼          │
                                                            │ vendor_invoices    │
                                                            │        │          │
                                                            │        ▼          │
                                                            │ vendor_payments    │
                                                            │                   │
                                                            │                   │
                                                            │                   ▼
                                                            │             client_invoices
                                                            │                   │
                                                            │                   ▼
                                                            │             client_payments
```

---

# 4. More Accurate Financial ER

This is the most important part of the database:

                         CLIENT
                           │
             ┌─────────────┼──────────────┐
             │             │              │
             ▼             ▼              ▼
           QUOTE        PROJECT        INVOICE
             │             │              │
             │             │              │
             │             ▼              │
             │           JOBS             │
             │             │              │
             │             ▼              │
             │          VENDOR            │
             │             │              │
             │             ▼              │
             │         PAYABLES            │
             │             │              │
             │             ▼              │
             │     VENDOR INVOICE         │
             │             │              │
             │             ▼              │
             │     VENDOR PAYMENT         │
             │                            │
             │                            │
             ▼                            ▼
        PROJECT                         CLIENT
                                        PAYMENT


But the actual invoice relationship is:


PROJECT
   │
   │ 1:N
   ▼
RECEIVABLE
   │
   │ N:M
   ▼
tbl_client_invoice_receivables
   │
   ▼
CLIENT INVOICE
   │
   │ N:M
   ▼
tbl_client_payment_invoices
   │
   ▼
CLIENT PAYMENT


And vendor side:


PROJECT
   │
   ▼
JOB
   │
   ▼
PAYABLE
   │
   │ N:M
   ▼
tbl_vendor_invoice_payables
   │
   ▼
VENDOR INVOICE
   │
   │ N:M
   ▼
tbl_vendor_payment_invoices
   │
   ▼
VENDOR PAYMENT
```

---

# 5. Complete Core ER Diagram — Mermaid

For documentation, GitHub, GitLab, Notion, VS Code extensions, etc., I recommend keeping this Mermaid version.

```mermaid
erDiagram

    tbl_users ||--o{ tbl_clients : manages
    tbl_users ||--o{ tbl_vendors : manages
    tbl_users ||--o{ tbl_quotes : manages
    tbl_users ||--o{ tbl_projects : manages
    tbl_users ||--o{ tbl_jobs : manages

    tbl_clients ||--o{ tbl_client_contacts : has
    tbl_clients ||--o{ tbl_client_prices : has
    tbl_clients ||--o{ tbl_client_documents : has

    tbl_vendors ||--o{ tbl_vendor_contacts : has
    tbl_vendors ||--o{ tbl_vendor_prices : has
    tbl_vendors ||--o{ tbl_vendor_documents : has

    tbl_languages ||--o{ tbl_language_pairs : source
    tbl_languages ||--o{ tbl_language_pairs : target

    tbl_clients ||--o{ tbl_quotes : requests
    tbl_quotes ||--o{ tbl_quote_items : contains
    tbl_quotes ||--o{ tbl_quote_files : contains

    tbl_clients ||--o{ tbl_projects : owns
    tbl_quotes ||--o| tbl_projects : converts_to

    tbl_projects ||--o{ tbl_jobs : contains
    tbl_projects ||--o{ tbl_receivables : generates

    tbl_vendors ||--o{ tbl_jobs : receives
    tbl_jobs ||--o{ tbl_payables : generates

    tbl_projects ||--o{ tbl_project_workflows : has
    tbl_workflow_templates ||--o{ tbl_project_workflows : used_by

    tbl_receivables ||--o{ tbl_client_invoice_receivables : linked
    tbl_client_invoices ||--o{ tbl_client_invoice_receivables : contains

    tbl_client_invoices ||--o{ tbl_client_payment_invoices : paid_by
    tbl_client_payments ||--o{ tbl_client_payment_invoices : applies_to

    tbl_payables ||--o{ tbl_vendor_invoice_payables : linked
    tbl_vendor_invoices ||--o{ tbl_vendor_invoice_payables : contains

    tbl_vendor_invoices ||--o{ tbl_vendor_payment_invoices : paid_by
    tbl_vendor_payments ||--o{ tbl_vendor_payment_invoices : applies_to

    tbl_projects ||--o{ tbl_project_files : has
    tbl_jobs ||--o{ tbl_job_files : has
    tbl_quotes ||--o{ tbl_quote_files : has
    tbl_files ||--o{ tbl_file_versions : versions

    tbl_projects ||--o{ tbl_expenses : incurs
    tbl_clients ||--o{ tbl_expenses : associated

    tbl_users ||--o{ tbl_messages : sends
    tbl_users ||--o{ tbl_todos : creates
    tbl_users ||--o{ tbl_todos : assigned
    tbl_users ||--o{ tbl_notifications : receives
    tbl_users ||--o{ tbl_audit_logs : performs

