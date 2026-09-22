<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extra fields for the Contact persons tab (Protemos parity):
     * position, note, is_active, is_invoicing.
     */
    public function up(): void
    {
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->string('position')->nullable()->after('last_name');
            $table->text('note')->nullable()->after('skype');
            $table->boolean('is_active')->default(true)->after('is_primary');
            $table->boolean('is_invoicing')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_contacts', function (Blueprint $table) {
            $table->dropColumn(['position', 'note', 'is_active', 'is_invoicing']);
        });
    }
};
