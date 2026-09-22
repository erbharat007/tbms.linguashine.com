<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Contact persons for a client. Fields mirror the Protemos
     * "Primary user" section. The primary contact is flagged is_primary.
     */
    public function up(): void
    {
        Schema::create('client_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->foreignId('timezone_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gender')->nullable(); // male | female
            $table->string('phone')->nullable();
            $table->string('skype')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('can_login')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_contacts');
    }
};
