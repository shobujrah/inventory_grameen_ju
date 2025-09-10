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
        Schema::create('branch__products', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('product_id');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('stock');
            $table->integer('batch')->nullable();
            $table->text('details_stockin')->nullable();
            $table->text('remain_details')->nullable();
            $table->text('details_stockout')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch__products');
    }
};
