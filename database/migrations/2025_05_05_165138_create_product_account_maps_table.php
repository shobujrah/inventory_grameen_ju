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
        Schema::create('product_account_maps', function (Blueprint $table) {
            $table->id();
            $table->integer('product_category_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('account_asset_inventory_code')->nullable();
            $table->integer('account_expense_code')->nullable();
            $table->integer('account_income_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_account_maps');
    }
};
