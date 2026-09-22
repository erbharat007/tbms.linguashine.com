<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A language pair is a source language -> target language combination
     * (Protemos: Settings > System values > Language pairs). Built from the
     * existing languages master table.
     */
    public function up(): void
    {
        Schema::create('language_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_language_id')->constrained('languages')->cascadeOnDelete();
            $table->foreignId('target_language_id')->constrained('languages')->cascadeOnDelete();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['source_language_id', 'target_language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_pairs');
    }
};
