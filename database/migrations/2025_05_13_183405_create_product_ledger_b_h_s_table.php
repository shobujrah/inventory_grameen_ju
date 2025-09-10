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
        Schema::create('product_ledger_b_h_s', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date')->nullable();
            $table->string('narration')->nullable();
            $table->string('type')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->string('consignee_name')->nullable();
            $table->string('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('requisition_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ledger_b_h_s');
    }
    
};
