<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('serial_no')->unique();
            $table->enum('status', ['available', 'sold', 'returned', 'scrapped'])->default('available');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('item_id')->references('id')->on('products');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('batch_id')->references('id')->on('batches');
            $table->index(['item_id', 'warehouse_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serials');
    }
};
