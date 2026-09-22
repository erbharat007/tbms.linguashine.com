<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Single-row table holding the account's custom SMTP server config
     * (Protemos: Settings > SMTP server). Password is stored encrypted.
     */
    public function up(): void
    {
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('host');
            $table->unsignedInteger('port');
            $table->string('encryption')->default('none');     // none | starttls | tls
            $table->string('authentication')->default('auto'); // auto | plain | login | ntlm | xoauth2
            $table->string('username');
            $table->text('password'); // encrypted at rest
            $table->string('from_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
