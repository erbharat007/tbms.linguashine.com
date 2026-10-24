<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('client_contact_id')->nullable()->constrained('client_contacts')->nullOnDelete();
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->nullOnDelete();
            $table->foreignId('primary_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('secondary_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('internal_note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('project_template_language_pair', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_template_id')->constrained('project_templates')->cascadeOnDelete();
            $table->foreignId('language_pair_id')->constrained('language_pairs')->cascadeOnDelete();
            $table->unique(['project_template_id', 'language_pair_id'], 'pt_lp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_template_language_pair');
        Schema::dropIfExists('project_templates');
    }
};
