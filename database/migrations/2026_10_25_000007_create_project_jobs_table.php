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
        Schema::create('project_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('language_pair_id')->constrained('language_pairs')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->foreignId('vendor_contact_id')->nullable()->constrained('vendor_contacts')->nullOnDelete();
            $table->string('status')->default('draft'); // draft, offered, in_progress, completed, cancelled
            $table->dateTime('started_at')->nullable();
            $table->dateTime('deadline_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('work_amount', 12, 2)->default(0.00);
            $table->decimal('price_per_unit', 12, 4)->default(0.0000);
            $table->decimal('payables', 12, 2)->default(0.00);
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
            $table->foreignId('primary_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('checklist_id')->nullable()->constrained('job_checklists')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_job_job_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_job_id')->constrained('project_jobs')->cascadeOnDelete();
            $table->foreignId('job_tag_id')->constrained('job_tags')->cascadeOnDelete();
            $table->unique(['project_job_id', 'job_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_job_job_tag');
        Schema::dropIfExists('project_jobs');
    }
};
