<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_transaction_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('serial_id')->nullable();
            $table->decimal('qty', 18, 6);
            $table->string('uom', 50)->default('unit');
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stock_transaction_id')->references('id')->on('stock_transactions');
            $table->foreign('item_id')->references('id')->on('products');
            $table->foreign('batch_id')->references('id')->on('batches');
            $table->foreign('serial_id')->references('id')->on('serials');
            $table->index(['stock_transaction_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transaction_lines');
    }
};
