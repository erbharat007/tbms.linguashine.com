<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Singleton settings record (Protemos: Settings > Vendor portal).
     * The application_token backs the public vendor application form URL:
     *   /vendor-app/create?token=<application_token>
     */
    public function up(): void
    {
        Schema::create('vendor_portal_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('default_payment_terms')->default(30);
            $table->boolean('allow_vendors_create_invoices')->default(false);
            // Explicit short FK names — auto-generated names exceed MySQL's 64-char limit.
            $table->foreignId('invoice_notification_recipient_id')->nullable()
                ->constrained('users', indexName: 'vps_invoice_recipient_fk')->nullOnDelete();
            $table->boolean('application_form_enabled')->default(true);
            $table->string('application_token', 64)->unique();
            $table->foreignId('application_notification_recipient_id')->nullable()
                ->constrained('users', indexName: 'vps_app_recipient_fk')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_portal_settings');
    }
};
