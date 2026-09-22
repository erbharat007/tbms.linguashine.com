<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Vendor agreement fields (Protemos: single agreement per vendor portal).
     * Co-located on the vendor_portal_settings singleton; the public agreement
     * view URL reuses the existing application_token.
     */
    public function up(): void
    {
        Schema::table('vendor_portal_settings', function (Blueprint $table) {
            $table->text('agreement_text')->nullable()->after('application_notification_recipient_id');
            $table->timestamp('agreement_updated_at')->nullable()->after('agreement_text');
            // Explicit short FK name — auto-generated name exceeds MySQL's 64-char limit.
            $table->foreignId('agreement_updated_by')->nullable()->after('agreement_updated_at')
                ->constrained('users', indexName: 'vps_agreement_updated_by_fk')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_portal_settings', function (Blueprint $table) {
            $table->dropForeign('vps_agreement_updated_by_fk');
            $table->dropColumn(['agreement_text', 'agreement_updated_at', 'agreement_updated_by']);
        });
    }
};
