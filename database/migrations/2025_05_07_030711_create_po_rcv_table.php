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
        Schema::create('po_rcv', function (Blueprint $table) {
            $table->id();
            $table->string('po_no')->nullable();
            $table->string('rcv_no')->nullable();
            $table->date('posting_date')->nullable();
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
        Schema::dropIfExists('po_rcv');
    }
};
