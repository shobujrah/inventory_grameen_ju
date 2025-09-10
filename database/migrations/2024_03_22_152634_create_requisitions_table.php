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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('branch_id');
            $table->string('project_id');
            $table->foreignId('user_id');
            $table->string('status')->nullable();
            $table->string('alldone_status')->nullable();
            $table->string('partial_delivery')->nullable();
            $table->string('partial_reject')->nullable();
            $table->string('partial_stock')->nullable();
            $table->string('partial_purchase')->nullable();
            $table->string('document')->nullable()->default(null);
            $table->string('date_from');
            $table->string('reject_note')->nullable();
            $table->string('pending_purchase_status')->nullable();
            $table->string('purchase_approve')->nullable();
            $table->string('purchase_reject')->nullable();
            $table->string('purchaseteam_reject_note')->nullable();
            $table->string('pending_approval_status_headoffice')->nullable();
            $table->string('headoffice_approve')->nullable();
            $table->string('headoffice_reject')->nullable();
            $table->string('headoffice_reject_note')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
