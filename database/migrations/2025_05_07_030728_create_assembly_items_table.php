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
        Schema::create('assembly_items', function (Blueprint $table) {
            $table->id();
            $table->string('so')->nullable(); // Sales Order
            $table->string('ass_item')->nullable(); // Assembly Item
            $table->string('assembly_document_no')->nullable();
            $table->string('item_no')->nullable();
            $table->string('description')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('uom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assembly_items');
    }
};
