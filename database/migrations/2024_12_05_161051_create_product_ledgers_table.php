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
        Schema::create('product_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->bigInteger('chart_of_account_id');
            $table->bigInteger('chart_of_account_code');
            $table->string('payment_method');
            $table->date('entry_date');
            $table->string('narration')->nullable();
            $table->string('type')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('branch_id');
            $table->bigInteger('product_id');
            $table->string('consignee_name')->nullable();
            $table->string('quantity');
            // $table->string('price')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('batch')->nullable();
            $table->string('requisition_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ledgers');
    }
};
