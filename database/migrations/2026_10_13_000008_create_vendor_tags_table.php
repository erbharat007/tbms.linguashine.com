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
        Schema::create('vendor_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('blue'); // badge color
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_tag_vendor', function (Blueprint $table) {
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['vendor_id', 'vendor_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_tag_vendor');
        Schema::dropIfExists('vendor_tags');
    }
};
