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
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('product_description')->nullable();
            $table->string('single_product_name')->nullable();
            $table->string('price')->nullable();
            $table->text('newprice_qty')->nullable();
            $table->string('demand_amount')->nullable();
            $table->string('delivery')->default('0');
            $table->string('reject')->default('0');
            $table->string('purchase')->default('0');
            $table->string('stock_status')->default('0');
            $table->string('purchase_team_reject')->default('0');
            $table->string('headoffice_approval')->nullable();
            $table->string('headoffice_rejected')->default('0');
            $table->string('total_price')->nullable();
            $table->string('reject_note')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};
