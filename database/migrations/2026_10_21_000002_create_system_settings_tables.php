<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('cat_log_templates')) {
            Schema::create('cat_log_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->boolean('is_default_client_account')->default(false);
                $table->boolean('is_default_client_profile')->default(false);
                $table->boolean('is_default_vendor_account')->default(false);
                $table->boolean('is_default_vendor_profile')->default(false);
                $table->decimal('rate_repetitions', 5, 2)->default(100.00);
                $table->decimal('rate_100', 5, 2)->default(100.00);
                $table->decimal('rate_95_99', 5, 2)->default(100.00);
                $table->decimal('rate_85_94', 5, 2)->default(100.00);
                $table->decimal('rate_75_84', 5, 2)->default(100.00);
                $table->decimal('rate_50_74', 5, 2)->default(100.00);
                $table->decimal('rate_no_match', 5, 2)->default(100.00);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('account_settings')) {
            Schema::create('account_settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Linguashine TBMS');
                $table->string('type')->default('Enterprise');
                $table->string('api_key')->nullable();
                $table->string('timezone')->default('Asia/Kolkata');
                $table->foreignId('legal_entity_id')->nullable()->constrained('legal_entities')->nullOnDelete();
                $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
                $table->foreignId('client_cat_log_template_id')->nullable()->constrained('cat_log_templates')->nullOnDelete();
                $table->foreignId('vendor_cat_log_template_id')->nullable()->constrained('cat_log_templates')->nullOnDelete();
                $table->string('default_quote_pdf_template')->default('Standard');
                $table->string('default_client_invoice_pdf_template')->default('Standard');
                $table->string('default_vendor_invoice_pdf_template')->default('Standard');
                $table->string('default_job_po_pdf_template')->default('Standard');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('reminder_settings')) {
            Schema::create('reminder_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('project_deadline')->default(true);
                $table->boolean('job_deadline')->default(true);
                $table->boolean('client_invoice_due')->default(true);
                $table->boolean('vendor_invoice_due')->default(true);
                $table->boolean('quote_expiration')->default(true);
                $table->unsignedInteger('project_deadline_days')->default(1);
                $table->unsignedInteger('job_deadline_days')->default(1);
                $table->unsignedInteger('client_invoice_days')->default(3);
                $table->unsignedInteger('vendor_invoice_days')->default(3);
                $table->unsignedInteger('quote_expiration_days')->default(2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('license_settings')) {
            Schema::create('license_settings', function (Blueprint $table) {
                $table->id();
                $table->string('status')->default('Active');
                $table->string('type')->default('Enterprise');
                $table->string('managers_limit')->default('Unlimited');
                $table->boolean('api_access')->default(true);
                $table->date('expires_at')->nullable();
                $table->string('license_key')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_settings');
        Schema::dropIfExists('reminder_settings');
        Schema::dropIfExists('account_settings');
        Schema::dropIfExists('cat_log_templates');
    }
};
