<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Generic PDF templates (Protemos: Settings > PDF templates). One table for
     * all object types (client invoice, vendor invoice, quote, job PO) since the
     * layout fields are identical; object_type distinguishes them.
     */
    public function up(): void
    {
        Schema::create('pdf_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('object_type', ['client_invoice', 'vendor_invoice', 'quote', 'job_po']);
            $table->string('name');
            $table->string('page_size')->default('A4');           // A4, Letter
            $table->string('page_orientation')->default('portrait'); // portrait, landscape
            $table->unsignedSmallInteger('margin_top')->default(15);
            $table->unsignedSmallInteger('margin_bottom')->default(15);
            $table->string('locale')->default('en_US');
            $table->text('header')->nullable();
            $table->text('footer')->nullable();
            $table->text('common_note')->nullable();
            $table->boolean('include_cat_log')->default(false);
            $table->timestamps();

            $table->index('object_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_templates');
    }
};
