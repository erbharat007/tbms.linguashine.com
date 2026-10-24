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
        Schema::create('job_tenders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('project_job_id')->constrained('project_jobs')->cascadeOnDelete();
            $table->string('type')->default('first_claimer'); // first_claimer, manager_selection_fixed, manager_selection_negotiable
            $table->string('status')->default('active'); // draft, active, completed, cancelled
            $table->dateTime('started_at')->nullable();
            $table->dateTime('deadline_at')->nullable(); // Vendor application deadline at
            $table->dateTime('ended_at')->nullable();
            $table->integer('participating_vendors_count')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_tenders');
    }
};
