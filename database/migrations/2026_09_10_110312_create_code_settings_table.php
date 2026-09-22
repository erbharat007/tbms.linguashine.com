<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Single-row table for global code-generation options
     * (Protemos: Object codes > Other settings).
     */
    public function up(): void
    {
        Schema::create('code_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('include_year_in_invoice_codes')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_settings');
    }
};
