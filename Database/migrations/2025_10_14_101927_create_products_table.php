<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->unique()->nullable();
            $table->json('name');          // ar/en
            $table->json('description')->nullable(); // ar/en
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->decimal('tax_rate', 8, 3)->default(0); // %
            $table->integer('opening_stock')->default(0);
            $table->enum('status', ['active','inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status','category_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
