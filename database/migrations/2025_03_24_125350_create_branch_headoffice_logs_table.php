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
        Schema::create('branch_headoffice_logs', function (Blueprint $table) {
            $table->id(); 
            $table->string('branch_id')->nullable();
            $table->string('requisition_id')->nullable();
            $table->string('product_id')->nullable();
            $table->text('price_quantity')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_headoffice_logs');
    }
};
