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
        Schema::create('product_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('consignee_name');
            $table->date('expense_date');
            $table->string('user_id');
            $table->string('product_id');
            $table->string('expense_amount');
            $table->text('expense_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_expenses');
    }
};
