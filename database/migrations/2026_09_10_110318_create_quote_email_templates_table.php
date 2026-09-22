<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Quote email templates (Protemos: Settings > Email templates > Quote templates).
     * Subject/body may contain {VARIABLE} placeholders substituted when sending.
     */
    public function up(): void
    {
        Schema::create('quote_email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_email_templates');
    }
};
