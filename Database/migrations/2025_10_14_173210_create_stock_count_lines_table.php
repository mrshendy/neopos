<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// stock_count_lines
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_count_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_count_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->decimal('system_qty', 18, 6)->default(0);
            $table->decimal('counted_qty', 18, 6)->default(0);
            $table->decimal('difference_qty', 18, 6)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stock_count_id')->references('id')->on('stock_counts');
            $table->foreign('item_id')->references('id')->on('products');
            $table->foreign('batch_id')->references('id')->on('batches');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_count_lines');
    }
};
