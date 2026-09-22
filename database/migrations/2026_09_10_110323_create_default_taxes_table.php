<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Default taxes set for clients / vendors (Protemos: Settings > Taxes >
     * "Default taxes set for clients" and "... for vendors"). Consolidated into
     * one table keyed by object_type. Each row assigns a default value (and,
     * for absolute taxes, a currency) to an existing tax.
     */
    public function up(): void
    {
        Schema::create('default_taxes', function (Blueprint $table) {
            $table->id();
            $table->enum('object_type', ['client', 'vendor']);
            $table->foreignId('tax_id')->constrained('taxes')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            // Only set for absolute taxes; percentage taxes leave this null.
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->timestamps();

            $table->index('object_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_taxes');
    }
};
