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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('client_contact_id')->nullable()->constrained('client_contacts')->nullOnDelete();
            $table->foreignId('legal_entity_id')->nullable()->constrained('legal_entities')->nullOnDelete();
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->nullOnDelete();
            $table->foreignId('primary_manager_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('secondary_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('deadline_at')->nullable();
            $table->string('status')->default('in_progress'); // draft, in_progress, completed, cancelled
            $table->text('description')->nullable();
            $table->text('internal_note')->nullable();
            $table->decimal('total_to_receive', 12, 2)->default(0.00);
            $table->decimal('total_to_pay', 12, 2)->default(0.00);
            $table->decimal('profit', 12, 2)->default(0.00);
            $table->decimal('profit_margin', 5, 2)->default(0.00);
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_language_pair', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('language_pair_id')->constrained('language_pairs')->cascadeOnDelete();
            $table->unique(['project_id', 'language_pair_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_language_pair');
        Schema::dropIfExists('projects');
    }
};
