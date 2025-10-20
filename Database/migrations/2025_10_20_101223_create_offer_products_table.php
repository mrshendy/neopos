<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offer_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('min_qty')->nullable(); // حد أدنى اختياري
            $table->timestampsTz();

            $table->unique(['offer_id','product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_products');
    }
};
