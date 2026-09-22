<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Singleton settings record (Protemos: Settings > Client portal, also used by
     * the Payment methods "Set default payment method" button — same form).
     */
    public function up(): void
    {
        Schema::create('client_portal_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('default_payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->unsignedInteger('default_payment_terms')->default(30);
            $table->boolean('allow_clients_create_projects')->default(false);
            $table->foreignId('default_client_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('notification_recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_portal_settings');
    }
};
