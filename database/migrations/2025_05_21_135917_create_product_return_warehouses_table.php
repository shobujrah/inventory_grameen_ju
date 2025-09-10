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
        Schema::create('product_return_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('product_id');
            $table->string('return_quantity');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('reason')->nullable();
            $table->date('date');
            $table->string('user_id');
            $table->string('status')->nullable()->default(null);
            $table->string('notification_status')->nullable()->default(null);
            $table->string('deny_status')->nullable()->default(null);
            $table->string('deny_reason_note')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_return_warehouses');
    }
};
