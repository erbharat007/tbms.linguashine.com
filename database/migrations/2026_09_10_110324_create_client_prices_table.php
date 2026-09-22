<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Client prices (Protemos: Clients > Prices). A price row ties a
     * service + language pair + specialization + unit to a price/currency.
     * client_id is nullable: null means the price applies to all clients
     * (a general price-book entry), matching Protemos's "price for all clients".
     */
    public function up(): void
    {
        Schema::create('client_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()
                ->constrained('clients', indexName: 'cp_client_fk')->nullOnDelete();
            $table->foreignId('service_id')
                ->constrained('services', indexName: 'cp_service_fk')->cascadeOnDelete();
            $table->foreignId('language_pair_id')
                ->constrained('language_pairs', indexName: 'cp_lang_pair_fk')->cascadeOnDelete();
            $table->foreignId('specialization_id')
                ->constrained('specializations', indexName: 'cp_spec_fk')->cascadeOnDelete();
            $table->foreignId('unit_id')
                ->constrained('units', indexName: 'cp_unit_fk')->cascadeOnDelete();
            $table->decimal('unit_price', 12, 4);
            $table->foreignId('currency_id')
                ->constrained('currencies', indexName: 'cp_currency_fk')->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users', indexName: 'cp_updated_by_fk')->nullOnDelete();
            $table->timestamps();

            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_prices');
    }
};
