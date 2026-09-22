<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Account payment methods used on client invoices
     * (Protemos: Settings > Payment methods). Type-specific fields are held
     * in the `details` JSON column since they differ widely per type.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // bank_transfer | paypal | skrill | custom
            $table->text('note')->nullable();
            $table->json('details')->nullable(); // type-specific fields
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_default')->default(false); // default in client portal settings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
