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
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'is_assignable')) {
                $table->boolean('is_assignable')->default(true)->after('note');
            }
            if (!Schema::hasColumn('vendors', 'can_access_finances')) {
                $table->boolean('can_access_finances')->default(true)->after('is_assignable');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['is_assignable', 'can_access_finances']);
        });
    }
};
