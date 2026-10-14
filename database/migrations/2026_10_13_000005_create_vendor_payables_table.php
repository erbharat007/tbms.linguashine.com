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
        Schema::create('vendor_payables', function (Blueprint $table) {
            $table->id();
            $table->string('payable_number')->unique();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('job_code')->nullable();
            $table->string('project_name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('quantity', 12, 4)->default(1);
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('rate', 15, 4)->default(0);
            $table->decimal('amount', 18, 2)->default(0);
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->date('due_date')->nullable();
            $table->string('status')->default('open'); // open | invoiced | paid | cancelled
            $table->foreignId('vendor_invoice_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payables');
    }
};
