<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_no')->unique(); // INV-TRX-YYYY-####
            $table->enum('type',['sales_issue','sales_return','adjustment','transfer','purchase_receive']);
            $table->dateTime('trx_date');

            $table->unsignedBigInteger('warehouse_from_id')->nullable();
            $table->unsignedBigInteger('warehouse_to_id')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ref_type')->nullable(); // invoice/return/count/transfer
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status',['draft','posted','cancelled'])->default('draft');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('warehouse_from_id')->references('id')->on('warehouses');
            $table->foreign('warehouse_to_id')->references('id')->on('warehouses');
            $table->index(['type','status','trx_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('stock_transactions'); }
};

