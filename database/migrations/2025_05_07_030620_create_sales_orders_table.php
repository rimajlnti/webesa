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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('SO');
            $table->string('CustPO');
            $table->string('Customer');
            $table->string('ShipTo');
            $table->string('CompletelyShipped');
            $table->date('OrderDate');
            $table->date('DocDate');
            $table->date('PostingDate');
            $table->string('SalesPerson');
            $table->string('No_');
            $table->string('PartNo');
            $table->string('Description');
            $table->string('InfoItem');
            $table->integer('Qty');
            $table->string('UOM');
            $table->integer('OutstandingQty');
            $table->decimal('UnitPrice', 15, 2);
            $table->decimal('Disc', 8, 2);
            $table->decimal('TotalAmount', 18, 2);
            $table->string('PR');
            $table->string('PO');
            $table->string('Notes');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
