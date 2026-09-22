<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * One row per business object type, holding the code prefix and next
     * sequence number used to auto-generate reference codes
     * (Protemos: Settings > Object codes). next_id is null for objects that
     * are numbered within their parent (Quote job, Job).
     */
    public function up(): void
    {
        Schema::create('object_codes', function (Blueprint $table) {
            $table->id();
            $table->string('object_key')->unique();      // e.g. quote, project, job
            $table->string('label');                     // e.g. Quote, Project
            $table->string('prefix')->nullable();        // e.g. Q, P, PR
            $table->unsignedInteger('next_id')->nullable(); // null = numbered within parent
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_codes');
    }
};
