<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A legal entity is one of the agency's own billing identities
     * (Protemos: Settings > Legal entities). Fields mirror the Protemos
     * "New legal entity" form.
     */
    public function up(): void
    {
        Schema::create('legal_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Company/brand name for use in invoices
            $table->string('website')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->string('region')->nullable();       // Region / State
            $table->string('postal_code')->nullable();  // Postal / Zip code
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo_path')->nullable();
            // Invoice numbering (used later by the Finance milestone).
            $table->unsignedInteger('first_client_invoice_number')->default(1);
            $table->unsignedInteger('first_vendor_invoice_number')->default(1);
            $table->string('client_invoice_prefix')->nullable();
            $table->string('vendor_invoice_prefix')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entities');
    }
};
